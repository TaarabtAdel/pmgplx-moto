<?php

namespace App\Services\PMGPLXMOTO;

use App\Models\PMGPLXMOTO\KhoaHoc as KhoaHocMoi;
use App\Models\PMGPLXMOTO\NguoiLXHoSo as NguoiLXHoSoMoi;
use App\Models\PMGPLXMOTOOLD\KhoaHoc;
use App\Models\PMGPLXMOTOOLD\NguoiLX;
use App\Support\PMGPLX\MaDkBanCu;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KetQuaDaoTaoBanCuService
{
  /**
   * @return array{
   *     rows: list<array<string, mixed>>,
   *     meta: array<string, mixed>
   * }
   */
  public function listByCourse(string $maKhoaHoc, ?string $ketQua = null): array
  {
      $maKhoaHoc = trim($maKhoaHoc);
      if ($maKhoaHoc === '') {
          return ['rows' => [], 'meta' => $this->emptyMeta($maKhoaHoc)];
      }

      $query = $this->baseQuery($maKhoaHoc);
      $this->applyKetQuaFilter($query, $ketQua);

      $rows = [];
      $stt = 0;
      $dat = 0;
      $khongDat = 0;

      foreach ($query->get() as $row) {
          $stt++;
          $ket = $this->ketLuanValue($row->KetLuanCSDT ?? null);
          if ($ket === 1) {
              $dat++;
          } elseif ($ket === 0) {
              $khongDat++;
          }

          $rows[] = $this->mapRow($row, $stt);
      }

      $khoa = KhoaHoc::query()->find($maKhoaHoc, ['MaKH', 'TenKH']);

      return [
          'rows' => $rows,
          'meta' => [
              'ma_khoa_hoc' => $maKhoaHoc,
              'ten_khoa_hoc' => $khoa?->TenKH,
              'tong' => count($rows),
              'dat' => $dat,
              'khong_dat' => $khongDat,
              'ket_qua' => $ketQua ?? '',
          ],
      ];
  }

  /**
   * @return array{
   *     rows: list<array<string, mixed>>,
   *     meta: array<string, mixed>
   * }
   */
  public function studentsForUpdate(string $maKhoaHoc): array
  {
      $list = $this->listByCourse($maKhoaHoc, null);
      foreach ($list['rows'] as &$row) {
          $row['ket_luan_text'] = $this->ketLuanLabel($row['ket_luan'] ?? null);
      }
      unset($row);

      return $list;
  }

  /**
   * @param  list<string>  $maDks
   * @return array{updated: int, skipped: int}
   */
  public function updateSelected(
      string $maKhoaHoc,
      array $maDks,
      ?string $tgBatDau,
      ?string $tgKetThuc,
      ?int $ketLuan
  ): array {
      $maKhoaHoc = trim($maKhoaHoc);
      $maDks = array_values(array_filter(array_unique(array_map('strval', $maDks))));

      if ($maKhoaHoc === '' || $maDks === []) {
          throw new \InvalidArgumentException('Chọn ít nhất một học viên và mã khóa học.');
      }

      if ($tgBatDau === null && $tgKetThuc === null && $ketLuan === null) {
          throw new \InvalidArgumentException('Nhập ít nhất một trong: TG bắt đầu, TG kết thúc, Kết luận CSDT.');
      }

      $payload = [];
      if ($tgBatDau !== null && $tgBatDau !== '') {
          $payload['TGBatDau'] = self::normalizeYmd($tgBatDau);
      }
      if ($tgKetThuc !== null && $tgKetThuc !== '') {
          $payload['TGKetThuc'] = self::normalizeYmd($tgKetThuc);
      }
      if ($ketLuan !== null) {
          $payload['KetLuanCSDT'] = $ketLuan ? 1 : 0;
      }

      if ($payload === []) {
          throw new \InvalidArgumentException('Không có giá trị hợp lệ để cập nhật.');
      }

      $db = DB::connection('sqlsrv_moto_old');
      $updated = 0;
      $skipped = 0;

      foreach (array_chunk($maDks, 200) as $chunk) {
          foreach ($chunk as $maDk) {
              $affected = $db->table('NguoiLX_HoSo')
                  ->where('MaDK', $maDk)
                  ->where('MaKhoaHoc', $maKhoaHoc)
                  ->update($payload);

              $affected > 0 ? $updated++ : $skipped++;
          }
      }

      return ['updated' => $updated, 'skipped' => $skipped];
  }

  /**
   * Lấy TGBatDau, TGKetThuc, KetLuanCSDT từ bản mới (cùng quy tắc map MaDK) và ghi lên bản cũ.
   *
   * @param  list<string>|null  $onlyOldMaDks  Mã ĐK bản cũ; null = toàn khóa đích
   * @return array{updated: int, skipped: int, total: int}
   */
  public function copyKetQuaFromNewCourse(
      string $maKhoaHocNguon,
      string $maKhoaHocDich,
      ?array $onlyOldMaDks = null
  ): array {
      $maKhoaHocNguon = trim($maKhoaHocNguon);
      $maKhoaHocDich = trim($maKhoaHocDich);

      if ($maKhoaHocNguon === '' || $maKhoaHocDich === '') {
          throw new \InvalidArgumentException('Cần mã khóa nguồn (mới) và khóa đích (cũ).');
      }

      if (! KhoaHocMoi::query()->where('MaKH', $maKhoaHocNguon)->exists()) {
          throw new \InvalidArgumentException('Mã khóa nguồn không tồn tại trên bản mới.');
      }

      if (! KhoaHoc::query()->where('MaKH', $maKhoaHocDich)->exists()) {
          throw new \InvalidArgumentException('Mã khóa đích không tồn tại trên bản cũ.');
      }

      $oldStudents = $this->baseQuery($maKhoaHocDich)->get(['n.MaDK']);
      if ($onlyOldMaDks !== null) {
          $filter = array_flip($onlyOldMaDks);
          $oldStudents = $oldStudents->filter(fn ($r) => isset($filter[(string) $r->MaDK]));
      }

      if ($oldStudents->isEmpty()) {
          return ['updated' => 0, 'skipped' => 0, 'total' => 0];
      }

      $dbOld = DB::connection('sqlsrv_moto_old');
      $updated = 0;
      $skipped = 0;

      foreach ($oldStudents as $oldRow) {
          $oldMaDk = (string) $oldRow->MaDK;
          $dash = strpos($oldMaDk, '-');
          if ($dash === false) {
              $skipped++;

              continue;
          }

          $suffix = substr($oldMaDk, $dash + 1);
          $sourcePrefix = MaDkBanCu::prefixFromMaKhoaHoc($maKhoaHocNguon);
          $sourceMaDk = $sourcePrefix.'-'.$suffix;

          $hoSoMoi = NguoiLXHoSoMoi::query()
              ->where('MaDK', $sourceMaDk)
              ->where('MaKhoaHoc', $maKhoaHocNguon)
              ->first(['TGBatDau', 'TGKetThuc', 'KetLuanCSDT']);

          if ($hoSoMoi === null) {
              $skipped++;

              continue;
          }

          $payload = array_filter([
              'TGBatDau' => $hoSoMoi->TGBatDau ?? null,
              'TGKetThuc' => $hoSoMoi->TGKetThuc ?? null,
              'KetLuanCSDT' => $hoSoMoi->KetLuanCSDT ?? null,
          ], static fn ($v) => $v !== null && $v !== '');

          if ($payload === []) {
              $skipped++;

              continue;
          }

          if (isset($payload['KetLuanCSDT'])) {
              $payload['KetLuanCSDT'] = (int) $payload['KetLuanCSDT'] ? 1 : 0;
          }

          $affected = $dbOld->table('NguoiLX_HoSo')
              ->where('MaDK', $oldMaDk)
              ->where('MaKhoaHoc', $maKhoaHocDich)
              ->update($payload);

          $affected > 0 ? $updated++ : $skipped++;
      }

      return [
          'updated' => $updated,
          'skipped' => $skipped,
          'total' => $oldStudents->count(),
      ];
  }

  public static function normalizeYmd(mixed $value): ?string
  {
      if ($value === null || $value === '') {
          return null;
      }

      $digits = preg_replace('/\D/', '', (string) $value) ?? '';
      if (strlen($digits) === 8) {
          return $digits;
      }

      try {
          return Carbon::parse((string) $value)->format('Ymd');
      } catch (\Throwable) {
          $trim = trim((string) $value);

          return $trim !== '' ? $trim : null;
      }
  }

  private function baseQuery(string $maKhoaHoc)
  {
      $cols = [
          'n.MaDK',
          'n.HoDemNLX',
          'n.TenNLX',
          'n.HoVaTen',
          'n.NgaySinh',
          'n.SoCMT',
          'n.TrangThai',
          'h.SoHoSo',
          'h.HangDaoTao',
          'h.HangGPLX',
      ];

      foreach (['TGBatDau', 'TGKetThuc', 'KetLuanCSDT'] as $col) {
          if (Schema::connection('sqlsrv_moto_old')->hasColumn('NguoiLX_HoSo', $col)) {
              $cols[] = 'h.'.$col;
          }
      }

      return NguoiLX::query()
          ->from('NguoiLX as n')
          ->join('NguoiLX_HoSo as h', 'h.MaDK', '=', 'n.MaDK')
          ->where('h.MaKhoaHoc', $maKhoaHoc)
          ->orderBy('n.MaDK')
          ->select($cols);
  }

  /**
   * @param  \Illuminate\Database\Eloquent\Builder<\App\Models\PMGPLXMOTOOLD\NguoiLX>  $query
   */
  private function applyKetQuaFilter($query, ?string $ketQua): void
  {
      $ketQua = strtoupper(trim((string) $ketQua));
      if ($ketQua === '') {
          return;
      }

      if (! Schema::connection('sqlsrv_moto_old')->hasColumn('NguoiLX_HoSo', 'KetLuanCSDT')) {
          return;
      }

      if ($ketQua === 'DAT') {
          $query->where('h.KetLuanCSDT', 1);
      } elseif ($ketQua === 'KHONGDAT') {
          $query->where('h.KetLuanCSDT', 0);
      }
  }

  /**
   * @return array<string, mixed>
   */
  private function mapRow(object $row, int $stt): array
  {
      $hoTen = trim((string) ($row->HoVaTen ?: trim(($row->HoDemNLX ?? '').' '.($row->TenNLX ?? ''))));
      $ket = $this->ketLuanValue($row->KetLuanCSDT ?? null);

      return [
          'stt' => $stt,
          'ma_dk' => $row->MaDK,
          'so_bd' => $row->SoHoSo ?? '',
          'ho_ten' => $hoTen,
          'ngay_sinh' => self::formatDate($row->NgaySinh ?? null),
          'so_cmt' => $row->SoCMT ?? '',
          'hang_dao_tao' => trim((string) ($row->HangDaoTao ?? $row->HangGPLX ?? '')),
          'tg_bat_dau' => $row->TGBatDau ?? null,
          'tg_bat_dau_text' => self::formatDate($row->TGBatDau ?? null),
          'tg_ket_thuc' => $row->TGKetThuc ?? null,
          'tg_ket_thuc_text' => self::formatDate($row->TGKetThuc ?? null),
          'ket_luan' => $ket,
          'ket_qua_text' => $this->ketLuanLabel($ket),
          'ket_qua_css' => $ket === 1 ? 'dat' : ($ket === 0 ? 'khongdat' : ''),
          'trang_thai' => (int) ($row->TrangThai ?? 0),
      ];
  }

  private function ketLuanValue(mixed $value): ?int
  {
      if ($value === null || $value === '') {
          return null;
      }

      return (int) $value ? 1 : 0;
  }

  private function ketLuanLabel(?int $ket): string
  {
      if ($ket === 1) {
          return 'Đạt';
      }
      if ($ket === 0) {
          return 'Không đạt';
      }

      return '—';
  }

  private static function formatDate(mixed $value): string
  {
      if ($value === null || $value === '') {
          return '';
      }

      $digits = preg_replace('/\D/', '', (string) $value) ?? '';
      if (strlen($digits) === 8 && ctype_digit($digits)) {
          return substr($digits, 6, 2).'/'.substr($digits, 4, 2).'/'.substr($digits, 0, 4);
      }

      try {
          return Carbon::parse((string) $value)->format('d/m/Y');
      } catch (\Throwable) {
          return (string) $value;
      }
  }

  /**
   * @return array<string, mixed>
   */
  private function emptyMeta(string $maKhoaHoc): array
  {
      return [
          'ma_khoa_hoc' => $maKhoaHoc,
          'ten_khoa_hoc' => null,
          'tong' => 0,
          'dat' => 0,
          'khong_dat' => 0,
          'ket_qua' => '',
      ];
  }
}
