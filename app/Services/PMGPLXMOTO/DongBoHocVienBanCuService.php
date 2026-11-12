<?php

namespace App\Services\PMGPLXMOTO;

use App\Models\PMGPLXMOTO\KhoaHoc;
use App\Models\PMGPLXMOTO\NguoiLX;
use App\Models\PMGPLXMOTO\NguoiLXHoSo;
use App\Models\PMGPLXMOTOOLD\KhoaHoc as KhoaHocOld;
use App\Support\PMGPLX\DmDvhcTenDayDu;
use App\Support\PMGPLX\DonViCapGPLXBanCu;
use App\Support\PMGPLX\DonViGtvtBanCu;
use App\Support\PMGPLX\MaDkBanCu;
use App\Support\PMGPLXMOTO\CopyBanCuColumnLists;
use App\Support\PMGPLXMOTO\DuongDanAnhPmGplx;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DongBoHocVienBanCuService
{
    public const SESSION_LAST_BATCH = 'pmgplxmoto.dong_bo_hoc_vien.last_batch';

    /**
     * @return Collection<int, object{
     *     MaDK: string,
     *     HoVaTen: ?string,
     *     HoDemNLX: ?string,
     *     TenNLX: ?string,
     *     NgaySinh: mixed,
     *     GioiTinh: ?string,
     *     SoGPLXDaCo: ?string,
     *     DonViCapGPLXDaCo: ?string,
     *     NgayTTGPLXDaCo: mixed,
     *     MaKhoaHoc: ?string,
     *     HangGPLX: ?string,
     *     TrangThai: ?string
     * }>
     */
    public function studentsInCourse(string $maKhoaHoc): Collection
    {
        return NguoiLX::query()
            ->from('NguoiLX as n')
            ->join('NguoiLX_HoSo as h', 'h.MaDK', '=', 'n.MaDK')
            ->where('h.MaKhoaHoc', $maKhoaHoc)
            ->orderBy('n.MaDK')
            ->get([
                'n.MaDK',
                'n.HoDemNLX',
                'n.TenNLX',
                'n.HoVaTen',
                'n.NgaySinh',
                'n.GioiTinh',
                'n.TrangThai',
                'h.SoGPLXDaCo',
                'h.DonViCapGPLXDaCo',
                'h.NgayTTGPLXDaCo',
                'h.MaKhoaHoc',
                'h.HangGPLX',
                'h.DuongDanAnh',
            ]);
    }

    /**
     * @return array{
     *     source: list<array<string, mixed>>,
     *     planned: list<array<string, mixed>>,
     *     meta: array<string, mixed>
     * }
     */
    public function buildPreview(string $maKhoaHocNguon, ?string $maKhoaHocDich): array
    {
        $students = $this->studentsInCourse($maKhoaHocNguon);

        $source = [];
        $planned = [];
        $plannedMaDks = [];

        foreach ($students->values() as $row) {
            $hoTen = trim((string) ($row->HoVaTen ?: trim(($row->HoDemNLX ?? '').' '.($row->TenNLX ?? ''))));

            $soGplx = trim((string) ($row->SoGPLXDaCo ?? ''));
            $donViCapGoc = trim((string) ($row->DonViCapGPLXDaCo ?? ''));
            $dvcTuSoGplx = DonViCapGPLXBanCu::prefixFromSoGPLX($soGplx);

            $duongDanAnh = trim((string) ($row->DuongDanAnh ?? ''));

            $source[] = [
                'ma_dk' => $row->MaDK,
                'ho_ten' => $hoTen,
                'ngay_sinh' => $row->NgaySinh,
                'gioi_tinh' => $row->GioiTinh,
                'so_gplx_da_co' => $row->SoGPLXDaCo,
                'don_vi_cap_gplx_da_co' => $donViCapGoc,
                'ma_khoa_hoc' => $row->MaKhoaHoc,
                'hang_gplx' => $row->HangGPLX,
                'duong_danh_anh' => $duongDanAnh,
                'anh_url' => DuongDanAnhPmGplx::previewUrl($duongDanAnh),
            ];

            $mappedMaDk = $maKhoaHocDich
                ? MaDkBanCu::mapFromSource((string) $row->MaDK, $maKhoaHocDich, $maKhoaHocNguon)
                : null;

            if ($mappedMaDk) {
                $plannedMaDks[] = $mappedMaDk;
            }

            $planned[] = [
                'ma_dk' => $mappedMaDk,
                'ma_dk_nguon' => $row->MaDK,
                'ho_ten' => $hoTen,
                'ngay_sinh' => $row->NgaySinh,
                'gioi_tinh' => $row->GioiTinh,
                'so_gplx_da_co' => $row->SoGPLXDaCo,
                'ngay_tt_gplx_da_co' => DonViCapGPLXBanCu::formatNgayTT($row->NgayTTGPLXDaCo),
                'ngay_tt_sau_moc' => DonViCapGPLXBanCu::isNgayTTAfterCutoff($row->NgayTTGPLXDaCo),
                'dvc_tu_so_gplx' => $dvcTuSoGplx,
                'don_vi_cap_gplx_da_co_goc' => $donViCapGoc,
                'don_vi_cap_gplx_da_co' => $donViCapGoc,
                'don_vi_cap_doi' => false,
                'hang_gplx' => $row->HangGPLX,
                'duong_danh_anh' => $duongDanAnh,
                'anh_url' => DuongDanAnhPmGplx::previewUrl($duongDanAnh !== '' ? $duongDanAnh : null),
                'ton_tai' => false,
            ];
        }

        $existingMaDks = $maKhoaHocDich ? $this->findExistingMaDks($plannedMaDks) : [];
        $conflicts = [];

        foreach ($planned as $index => &$row) {
            $maDk = $row['ma_dk'];
            if (! $maDk) {
                continue;
            }

            if (isset($existingMaDks[$maDk])) {
                $row['ton_tai'] = true;
                $conflicts[] = [
                    'ma_dk' => $maDk,
                    'ma_dk_nguon' => $row['ma_dk_nguon'],
                    'ho_ten' => $row['ho_ten'],
                ];
            }
        }
        unset($row);

        $khoaNguon = KhoaHoc::query()->find($maKhoaHocNguon, ['MaKH', 'TenKH']);
        $khoaDich = $maKhoaHocDich
            ? KhoaHocOld::query()->find($maKhoaHocDich, ['MaKH', 'TenKH'])
            : null;

        $baoCaoINguon = $this->countBaoCaoIOnSource($maKhoaHocNguon);
        $baoCaoIDich = $maKhoaHocDich ? $this->countBaoCaoIOnTarget($maKhoaHocDich) : null;

        $prefixNguon = MaDkBanCu::prefixFromMaKhoaHoc($maKhoaHocNguon);
        $prefixDich = $maKhoaHocDich ? MaDkBanCu::prefixFromMaKhoaHoc($maKhoaHocDich) : null;

        $this->enrichPreviewDmLabels($source, $planned);

        return [
            'source' => $source,
            'planned' => $planned,
            'meta' => [
                'count' => $students->count(),
                'ma_kh_nguon' => $maKhoaHocNguon,
                'ten_kh_nguon' => $khoaNguon?->TenKH,
                'ma_kh_dich' => $maKhoaHocDich,
                'ten_kh_dich' => $khoaDich?->TenKH,
                'ma_dk_prefix_nguon' => $prefixNguon,
                'ma_dk_prefix_dich' => $prefixDich,
                'conflict_count' => count($conflicts),
                'update_count' => count($conflicts),
                'conflicts' => $conflicts,
                'syncable_count' => count(array_filter(
                    $planned,
                    fn ($row) => ! empty($row['ma_dk'])
                )),
                'test_student' => $this->firstTestableStudent($planned),
                'bao_cao_i_nguon' => $baoCaoINguon,
                'bao_cao_i_dich' => $baoCaoIDich,
            ],
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $planned
     * @return array<string, mixed>|null
     */
    public function firstTestableStudent(array $planned): ?array
    {
        foreach ($planned as $row) {
            if (empty($row['ma_dk']) || empty($row['ma_dk_nguon'])) {
                continue;
            }

            return [
                'ma_dk_nguon' => $row['ma_dk_nguon'],
                'ma_dk' => $row['ma_dk'],
                'ho_ten' => $row['ho_ten'],
            ];
        }

        return null;
    }

    /**
     * @return array{
     *     inserted: int,
     *     updated: int,
     *     failed: int,
     *     total: int,
     *     skipped: int,
     *     inserted_ma_dks: list<string>,
     *     source_ma_dk?: string|null,
     *     target_ma_dk?: string|null,
     *     ho_ten?: string
     * }
     */
    public function sync(string $maKhoaHocNguon, string $maKhoaHocDich, ?string $onlySourceMaDk = null): array
    {
        $students = $this->studentsInCourse($maKhoaHocNguon);

        if ($onlySourceMaDk !== null) {
            $students = $students
                ->filter(fn ($row) => (string) $row->MaDK === $onlySourceMaDk)
                ->values();
        }

        $oldPrefix = MaDkBanCu::prefixFromMaKhoaHoc($maKhoaHocNguon);
        $students = $students
            ->filter(fn ($row) => MaDkBanCu::maDkMatchesCopyFilter((string) $row->MaDK, $oldPrefix))
            ->values();

        $khoaHocBanCu = KhoaHocOld::query()
            ->where('MaKH', $maKhoaHocDich)
            ->first(['MaKH', 'MaCSDT', 'MaSoGTVT']);

        if ($khoaHocBanCu === null) {
            throw new \InvalidArgumentException('Mã khóa học đích không tồn tại trên bản cũ.');
        }

        if ($students->isEmpty()) {
            return $this->syncCourseMetadataOnly($maKhoaHocNguon, $maKhoaHocDich, $khoaHocBanCu);
        }

        $plannedMaDks = $students
            ->map(fn ($row) => MaDkBanCu::mapFromSource((string) $row->MaDK, $maKhoaHocDich, $maKhoaHocNguon))
            ->all();

        $existingMaDks = $this->findExistingMaDks($plannedMaDks);

        $maDks = $students->pluck('MaDK')->all();

        [$colsNguoi, $colsHoSo] = $this->mappableColumns();

        $nguoiRows = NguoiLX::query()->whereIn('MaDK', $maDks)->get()->keyBy('MaDK');
        $hoSoRows = NguoiLXHoSo::query()->whereIn('MaDK', $maDks)->get()->keyBy('MaDK');

        $oldDb = DB::connection('sqlsrv_moto_old');
        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $failed = 0;
        $syncedMaDks = [];

        $oldDb->beginTransaction();

        $baoCaoIInserted = 0;
        $baoCaoIUpdated = 0;
        $khoaHocSynced = false;

        try {
            $this->setTableConstraintChecks(false);

            $khoaHocSynced = $this->syncKhoaHocFromSource(
                $maKhoaHocNguon,
                $maKhoaHocDich,
                $oldDb
            );

            [$baoCaoIInserted, $baoCaoIUpdated] = $this->syncBaoCaoIFromCourse(
                $maKhoaHocNguon,
                $maKhoaHocDich,
                $khoaHocBanCu,
                $oldDb
            );
            $this->normalizeBaoCaoIForMaKhDich($maKhoaHocNguon, $maKhoaHocDich, $oldDb);

            foreach ($students->values() as $index => $row) {
                $sourceMaDk = $row->MaDK;
                $targetMaDk = $plannedMaDks[$index] ?? MaDkBanCu::mapFromSource(
                    (string) $sourceMaDk,
                    $maKhoaHocDich,
                    $maKhoaHocNguon
                );

                if (! $targetMaDk) {
                    $failed++;
                    continue;
                }

                $nguoi = $nguoiRows->get($sourceMaDk);
                if (! $nguoi) {
                    $failed++;
                    continue;
                }

                if (isset($existingMaDks[$targetMaDk])) {
                    $skipped++;
                    continue;
                }

                $payloadNguoi = $this->onlyColumns($nguoi->getAttributes(), $colsNguoi);
                $payloadNguoi['MaDK'] = $targetMaDk;

                $payloadHoSo = null;
                $hoSo = $hoSoRows->get($sourceMaDk);
                if ($hoSo) {
                    $payloadHoSo = $this->onlyColumns($hoSo->getAttributes(), $colsHoSo);
                    $payloadHoSo['MaDK'] = $targetMaDk;
                    $payloadHoSo['MaKhoaHoc'] = $maKhoaHocDich;
                }

                [$payloadNguoi, $payloadHoSo] = DonViGtvtBanCu::applyFromKhoaHocDich(
                    $payloadNguoi,
                    $payloadHoSo,
                    $khoaHocBanCu
                );

                $payloadNguoi = $this->clipPayloadToOldColumnLengths('NguoiLX', $payloadNguoi);
                if ($payloadHoSo !== null) {
                    $payloadHoSo = $this->clipPayloadToOldColumnLengths('NguoiLX_HoSo', $payloadHoSo);
                }

                try {
                    $oldDb->table('NguoiLX')->insert($payloadNguoi);

                    if ($payloadHoSo !== null) {
                        $oldDb->table('NguoiLX_HoSo')->insert($payloadHoSo);
                    }
                } catch (\Throwable $e) {
                    throw new \RuntimeException(
                        "Ghi học viên {$sourceMaDk} → {$targetMaDk} thất bại: ".$e->getMessage(),
                        (int) $e->getCode(),
                        $e
                    );
                }

                $syncedMaDks[] = $targetMaDk;
                $inserted++;
            }

            $this->alignKhoaHocWithBaoCaoI($maKhoaHocDich, $oldDb);
            $this->refreshTongSoHocVienOnKhoa($maKhoaHocDich, $oldDb);

            $oldDb->commit();
        } catch (\Throwable $e) {
            $oldDb->rollBack();

            throw $e;
        } finally {
            $this->setTableConstraintChecks(true);
        }

        return [
            'inserted' => $inserted,
            'updated' => $updated,
            'failed' => $failed,
            'total' => $students->count(),
            'skipped' => $skipped,
            'bao_cao_i_inserted' => $baoCaoIInserted,
            'bao_cao_i_updated' => $baoCaoIUpdated,
            'khoa_hoc_synced' => $khoaHocSynced,
            'inserted_ma_dks' => $syncedMaDks,
            'source_ma_dk' => $onlySourceMaDk ?? ($students->first()->MaDK ?? null),
            'target_ma_dk' => $syncedMaDks[0] ?? null,
            'ho_ten' => $students->isEmpty()
                ? ''
                : trim((string) ($students->first()->HoVaTen ?: trim(($students->first()->HoDemNLX ?? '').' '.($students->first()->TenNLX ?? '')))),
        ];
    }

    /**
     * @param  list<string>  $maDks
     * @return array<string, true>
     */
    public function findExistingMaDks(array $maDks): array
    {
        $maDks = array_values(array_unique(array_filter($maDks)));

        if ($maDks === []) {
            return [];
        }

        return DB::connection('sqlsrv_moto_old')
            ->table('NguoiLX')
            ->whereIn('MaDK', $maDks)
            ->pluck('MaDK')
            ->mapWithKeys(fn ($maDk) => [(string) $maDk => true])
            ->all();
    }

    /**
     * Khôi phục = xóa học viên đã đồng bộ khỏi bản cũ.
     *
     * @param  array{ma_kh_nguon?: string, ma_kh_dich?: string, ma_dks?: list<string>}  $batch
     * @return array{deleted: int, total: int}
     */
    public function restore(array $batch): array
    {
        $maDks = array_values(array_unique(array_filter($batch['ma_dks'] ?? [])));

        if ($maDks === []) {
            throw new \InvalidArgumentException('Không có dữ liệu để khôi phục.');
        }

        $oldDb = DB::connection('sqlsrv_moto_old');
        $deleted = 0;

        $oldDb->beginTransaction();

        try {
            foreach (array_chunk($maDks, 500) as $chunk) {
                $oldDb->table('NguoiLXHS_GiayTo')->whereIn('MaDK', $chunk)->delete();
                $oldDb->table('NguoiLX_HoSo')->whereIn('MaDK', $chunk)->delete();
                $deleted += $oldDb->table('NguoiLX')->whereIn('MaDK', $chunk)->delete();
            }

            $oldDb->commit();
        } catch (\Throwable $e) {
            $oldDb->rollBack();

            throw $e;
        }

        return [
            'deleted' => $deleted,
            'total' => count($maDks),
        ];
    }

    /**
     * @param  array<string, mixed>  $result
     * @return array<string, mixed>
     */
    public function buildSessionBatch(string $maKhoaHocNguon, string $maKhoaHocDich, array $result, bool $isTest = false): array
    {
        return [
            'ma_kh_nguon' => $maKhoaHocNguon,
            'ma_kh_dich' => $maKhoaHocDich,
            'ma_dks' => $result['inserted_ma_dks'] ?? [],
            'count' => (int) (($result['inserted'] ?? 0) + ($result['updated'] ?? 0)),
            'synced_at' => now()->format('d/m/Y H:i:s'),
            'is_test' => $isTest,
            'ho_ten' => $result['ho_ten'] ?? null,
            'source_ma_dk' => $result['source_ma_dk'] ?? null,
            'target_ma_dk' => $result['target_ma_dk'] ?? null,
        ];
    }

    /**
     * Cột INSERT = 03-copy-khoa-hoc-makh-dich.sql (lọc cột có trên cả hai DB).
     *
     * @return array{0: list<string>, 1: list<string>}
     */
    public function mappableColumns(): array
    {
        return [
            CopyBanCuColumnLists::forTable('NguoiLX'),
            CopyBanCuColumnLists::forTable('NguoiLX_HoSo'),
        ];
    }

    /**
     * Giữ tương thích chỗ gọi cũ — không biến đổi cột hồ sơ.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function mapHoSoPayloadForBanCu(array $payload): array
    {
        return $payload;
    }

    /**
     * @param  list<array<string, mixed>>  $source
     * @param  list<array<string, mixed>>  $planned
     */
    private function enrichPreviewDmLabels(array &$source, array &$planned): void
    {
        $dvhcCodesNguon = [];
        $hangCodesNguon = [];
        $dvhcCodesDich = [];
        $hangCodesDich = [];

        foreach ($source as $row) {
            if (trim((string) ($row['don_vi_cap_gplx_da_co'] ?? '')) !== '') {
                $dvhcCodesNguon[] = $row['don_vi_cap_gplx_da_co'];
            }
            if (trim((string) ($row['hang_gplx'] ?? '')) !== '') {
                $hangCodesNguon[] = $row['hang_gplx'];
            }
        }

        foreach ($planned as $row) {
            foreach (['don_vi_cap_gplx_da_co_goc', 'don_vi_cap_gplx_da_co', 'dvc_tu_so_gplx'] as $key) {
                if (trim((string) ($row[$key] ?? '')) !== '') {
                    $dvhcCodesDich[] = $row[$key];
                }
            }
            if (trim((string) ($row['hang_gplx'] ?? '')) !== '') {
                $hangCodesDich[] = $row['hang_gplx'];
            }
        }

        $dvhcMapNguon = DmDvhcTenDayDu::mapDonViCapGplx($dvhcCodesNguon, 'sqlsrv_moto');
        $dvhcMapDich = DmDvhcTenDayDu::mapDonViCapGplx($dvhcCodesDich, 'sqlsrv_moto_old');
        $hangMapNguon = $this->hangGplxLabelMap($hangCodesNguon, 'sqlsrv_moto');
        $hangMapDich = $this->hangGplxLabelMap($hangCodesDich, 'sqlsrv_moto_old');

        foreach ($source as &$row) {
            $row['don_vi_cap_hien_thi'] = DmDvhcTenDayDu::formatMaDonVi($row['don_vi_cap_gplx_da_co'] ?? null, $dvhcMapNguon);
            $row['hang_gplx_hien_thi'] = $this->formatHangGplxLabel($row['hang_gplx'] ?? null, $hangMapNguon);
        }
        unset($row);

        foreach ($planned as &$row) {
            $row['don_vi_cap_goc_hien_thi'] = DmDvhcTenDayDu::formatMaDonVi($row['don_vi_cap_gplx_da_co_goc'] ?? null, $dvhcMapDich);
            $row['don_vi_cap_ban_cu_hien_thi'] = DmDvhcTenDayDu::formatMaDonVi($row['don_vi_cap_gplx_da_co'] ?? null, $dvhcMapDich);
            $row['dvc_tu_so_hien_thi'] = DmDvhcTenDayDu::formatMaDonVi($row['dvc_tu_so_gplx'] ?? null, $dvhcMapDich);
            $row['hang_gplx_hien_thi'] = $this->formatHangGplxLabel($row['hang_gplx'] ?? null, $hangMapDich);
        }
        unset($row);
    }

    /**
     * @param  list<string>  $maHangs
     * @return array<string, string>
     */
    private function hangGplxLabelMap(array $maHangs, string $connection = 'sqlsrv_moto'): array
    {
        $maHangs = array_values(array_unique(array_filter(array_map('strval', $maHangs))));
        if ($maHangs === []) {
            return [];
        }

        if (! Schema::connection($connection)->hasTable('DM_HangGPLX')) {
            return [];
        }

        return DB::connection($connection)
            ->table('DM_HangGPLX')
            ->whereIn('MaHang', $maHangs)
            ->pluck('TenHang', 'MaHang')
            ->map(fn ($t) => trim((string) $t))
            ->filter()
            ->all();
    }

    /**
     * @param  array<string, string>  $map
     */
    private function formatHangGplxLabel(?string $maHang, array $map): string
    {
        $maHang = trim((string) $maHang);
        if ($maHang === '') {
            return '—';
        }

        $ten = $map[$maHang] ?? '';
        if ($ten === '') {
            return $maHang;
        }

        return $maHang.' — '.$ten;
    }

    /**
     * @return list<string>
     */
    private function commonColumns(string $table): array
    {
        $cols = array_values(array_intersect(
            Schema::connection('sqlsrv_moto')->getColumnListing($table),
            Schema::connection('sqlsrv_moto_old')->getColumnListing($table)
        ));

        return array_values(array_diff($cols, $this->identityColumns('sqlsrv_moto_old', $table)));
    }

    /**
     * @return list<string>
     */
    private function identityColumns(string $connection, string $table): array
    {
        static $cache = [];

        $key = $connection.'::'.$table;
        if (! array_key_exists($key, $cache)) {
            $rows = DB::connection($connection)->select(
                'SELECT c.name FROM sys.columns c INNER JOIN sys.tables t ON c.object_id = t.object_id WHERE t.name = ? AND c.is_identity = 1',
                [$table]
            );
            $cache[$key] = array_map(static fn ($row) => (string) $row->name, $rows);
        }

        return $cache[$key];
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  list<string>  $columns
     * @return array<string, mixed>
     */
    private function onlyColumns(array $attributes, array $columns): array
    {
        $out = [];
        foreach ($columns as $col) {
            if (! array_key_exists($col, $attributes)) {
                continue;
            }
            $val = $attributes[$col];
            if ($val instanceof \DateTimeInterface) {
                $val = $val->format('Y-m-d H:i:s');
            }
            $out[$col] = $val;
        }

        return $out;
    }

    /**
     * Chỉ đồng bộ metadata khóa + BaoCaoI (HV đã đủ hoặc không có HV trên nguồn).
     *
     * @return array<string, mixed>
     */
    private function syncCourseMetadataOnly(
        string $maKhoaHocNguon,
        string $maKhoaHocDich,
        object $khoaHocBanCu
    ): array {
        $oldDb = DB::connection('sqlsrv_moto_old');
        $baoCaoIInserted = 0;
        $baoCaoIUpdated = 0;
        $khoaHocSynced = false;

        $oldDb->beginTransaction();

        try {
            $khoaHocSynced = $this->syncKhoaHocFromSource($maKhoaHocNguon, $maKhoaHocDich, $oldDb);
            [$baoCaoIInserted, $baoCaoIUpdated] = $this->syncBaoCaoIFromCourse(
                $maKhoaHocNguon,
                $maKhoaHocDich,
                $khoaHocBanCu,
                $oldDb
            );
            $this->normalizeBaoCaoIForMaKhDich($maKhoaHocNguon, $maKhoaHocDich, $oldDb);
            $this->alignKhoaHocWithBaoCaoI($maKhoaHocDich, $oldDb);
            $this->refreshTongSoHocVienOnKhoa($maKhoaHocDich, $oldDb);
            $oldDb->commit();
        } catch (\Throwable $e) {
            $oldDb->rollBack();

            throw $e;
        }

        return [
            'inserted' => 0,
            'updated' => 0,
            'failed' => 0,
            'total' => 0,
            'skipped' => 0,
            'bao_cao_i_inserted' => $baoCaoIInserted,
            'bao_cao_i_updated' => $baoCaoIUpdated,
            'khoa_hoc_synced' => $khoaHocSynced,
            'inserted_ma_dks' => [],
        ];
    }

    /**
     * PM tool: NgayKG/NgayBG trên KhoaHoc khớp BaoCaoI — áp từ BaoCaoI đích sau đồng bộ.
     */
    private function alignKhoaHocWithBaoCaoI(string $maKhoaHoc, \Illuminate\Database\Connection $oldDb): void
    {
        if (! Schema::connection('sqlsrv_moto_old')->hasTable('KhoaHoc')
            || ! Schema::connection('sqlsrv_moto_old')->hasTable('BaoCaoI')) {
            return;
        }

        $baoCao = $oldDb->table('BaoCaoI')->where('MaKH', $maKhoaHoc)->first();
        if ($baoCao === null) {
            return;
        }

        $payload = [];
        foreach (['NgayKG', 'NgayBG'] as $col) {
            if (isset($baoCao->{$col}) && $baoCao->{$col} !== null && $baoCao->{$col} !== '') {
                $payload[$col] = $baoCao->{$col};
            }
        }

        if ($payload === []) {
            return;
        }

        $oldDb->table('KhoaHoc')->where('MaKH', $maKhoaHoc)->update($payload);
    }

    /**
     * Cập nhật KhoaHoc đích từ khóa nguồn (bản mới): giữ MaKH, MaCSDT, MaSoGTVT của khóa đích.
     */
    private function syncKhoaHocFromSource(
        string $maKhoaHocNguon,
        string $maKhoaHocDich,
        \Illuminate\Database\Connection $oldDb
    ): bool {
        if (! Schema::connection('sqlsrv_moto')->hasTable('KhoaHoc')
            || ! Schema::connection('sqlsrv_moto_old')->hasTable('KhoaHoc')) {
            return false;
        }

        $cols = CopyBanCuColumnLists::forTable('KhoaHoc');
        if ($cols === []) {
            return false;
        }

        $source = KhoaHoc::query()->where('MaKH', $maKhoaHocNguon)->first();
        if ($source === null) {
            return false;
        }

        $payload = $this->onlyColumns($source->getAttributes(), $cols);
        if ($payload === []) {
            return false;
        }

        $baoCaoNguon = DB::connection('sqlsrv_moto')
            ->table('BaoCaoI')
            ->where('MaKH', $maKhoaHocNguon)
            ->first();
        if ($baoCaoNguon !== null) {
            foreach (['NgayKG', 'NgayBG'] as $col) {
                if (isset($baoCaoNguon->{$col}) && $baoCaoNguon->{$col} !== null && $baoCaoNguon->{$col} !== '') {
                    $payload[$col] = $baoCaoNguon->{$col};
                }
            }
        }

        $payload = $this->clipPayloadToOldColumnLengths('KhoaHoc', $payload);

        return $oldDb->table('KhoaHoc')->where('MaKH', $maKhoaHocDich)->update($payload) > 0;
    }

    private function refreshTongSoHocVienOnKhoa(string $maKhoaHoc, \Illuminate\Database\Connection $oldDb): void
    {
        if (! Schema::connection('sqlsrv_moto_old')->hasTable('KhoaHoc')) {
            return;
        }

        $count = (int) $oldDb->table('NguoiLX_HoSo')->where('MaKhoaHoc', $maKhoaHoc)->count();
        $oldDb->table('KhoaHoc')->where('MaKH', $maKhoaHoc)->update(['TongSoHV' => $count]);
    }

    /**
     * BaoCaoI: MaKH/MaCSDT khóa đích; INSERT hoặc UPDATE bản ghi đã gắn MaKH đích.
     *
     * @return array{0: int, 1: int} inserted, updated
     */
    private function syncBaoCaoIFromCourse(
        string $maKhoaHocNguon,
        string $maKhoaHocDich,
        object $khoaHocBanCu,
        \Illuminate\Database\Connection $oldDb
    ): array {
        if (! Schema::connection('sqlsrv_moto')->hasTable('BaoCaoI')
            || ! Schema::connection('sqlsrv_moto_old')->hasTable('BaoCaoI')) {
            return [0, 0];
        }

        $cols = CopyBanCuColumnLists::forTable('BaoCaoI');
        if ($cols === []) {
            return [0, 0];
        }

        $maCsdt = trim((string) ($khoaHocBanCu->MaCSDT ?? ''));
        $rows = DB::connection('sqlsrv_moto')
            ->table('BaoCaoI')
            ->where('MaKH', $maKhoaHocNguon)
            ->get();

        $inserted = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $maBci = (string) ($row->MaBCI ?? '');
            if ($maBci === '') {
                continue;
            }

            $payload = $this->onlyColumns((array) $row, $cols);
            unset($payload['MaBCI']);
            $payload['MaKH'] = $maKhoaHocDich;
            if ($maCsdt !== '') {
                $payload['MaCSDT'] = $maCsdt;
            }

            $payload = $this->clipPayloadToOldColumnLengths('BaoCaoI', $payload);

            $existingOnDest = $oldDb->table('BaoCaoI')->where('MaKH', $maKhoaHocDich)->first();

            if ($existingOnDest !== null) {
                $oldDb->table('BaoCaoI')->where('MaKH', $maKhoaHocDich)->update($payload);
                $updated++;

                continue;
            }

            if ($oldDb->table('BaoCaoI')->where('MaBCI', $maBci)->exists()) {
                continue;
            }

            $payload['MaBCI'] = $maBci;
            $oldDb->table('BaoCaoI')->insert($payload);
            $inserted++;
        }

        return [$inserted, $updated];
    }

    /**
     * Chuẩn khóa đích như khóa PM tạo tay (vd. 1012): MaBCI = DKKH_{MaKH đích}, SoBaoCao đổi prefix,
     * BaoCaoI.TrangThai = 1. Không đổi TT_XuLy HV (giữ 09/12 như tool copy 44016).
     */
    private function normalizeBaoCaoIForMaKhDich(
        string $maKhoaHocNguon,
        string $maKhoaHocDich,
        \Illuminate\Database\Connection $oldDb
    ): bool {
        if (! Schema::connection('sqlsrv_moto_old')->hasTable('BaoCaoI')) {
            return false;
        }

        $maKhoaHocDich = trim($maKhoaHocDich);
        $maKhoaHocNguon = trim($maKhoaHocNguon);
        if ($maKhoaHocDich === '') {
            return false;
        }

        $row = $oldDb->table('BaoCaoI')->where('MaKH', $maKhoaHocDich)->first();
        if ($row === null) {
            return false;
        }

        $dkkhDest = 'DKKH_'.$maKhoaHocDich;
        $payload = ['TrangThai' => 1];

        $maBci = (string) ($row->MaBCI ?? '');
        if ($maBci !== $dkkhDest) {
            $takeover = str_starts_with(strtoupper($maBci), 'DKKH_')
                || ($maKhoaHocNguon !== '' && str_contains($maBci, $maKhoaHocNguon));

            if ($takeover) {
                $conflict = $oldDb->table('BaoCaoI')
                    ->where('MaBCI', $dkkhDest)
                    ->where('MaKH', '<>', $maKhoaHocDich)
                    ->exists();

                if (! $conflict) {
                    $payload['MaBCI'] = $dkkhDest;
                }
            }
        }

        $soBaoCao = (string) ($row->SoBaoCao ?? '');
        $prefixNguon = MaDkBanCu::prefixFromMaKhoaHoc($maKhoaHocNguon);
        $prefixDich = MaDkBanCu::prefixFromMaKhoaHoc($maKhoaHocDich);
        if ($soBaoCao !== '' && $prefixNguon !== '' && $prefixDich !== '' && $prefixNguon !== $prefixDich) {
            if (str_starts_with($soBaoCao, $prefixNguon)) {
                $payload['SoBaoCao'] = $prefixDich.substr($soBaoCao, strlen($prefixNguon));
            }
        } elseif ($soBaoCao !== '' && $maKhoaHocNguon !== '' && str_contains($soBaoCao, $maKhoaHocNguon)) {
            $payload['SoBaoCao'] = str_replace($maKhoaHocNguon, $maKhoaHocDich, $soBaoCao);
        }

        $payload = $this->clipPayloadToOldColumnLengths('BaoCaoI', $payload);

        return $oldDb->table('BaoCaoI')->where('MaKH', $maKhoaHocDich)->update($payload) > 0;
    }

    private function countBaoCaoIOnSource(string $maKhoaHoc): int
    {
        if (! Schema::connection('sqlsrv_moto')->hasTable('BaoCaoI')) {
            return 0;
        }

        return (int) DB::connection('sqlsrv_moto')
            ->table('BaoCaoI')
            ->where('MaKH', $maKhoaHoc)
            ->count();
    }

    private function countBaoCaoIOnTarget(string $maKhoaHoc): int
    {
        if (! Schema::connection('sqlsrv_moto_old')->hasTable('BaoCaoI')) {
            return 0;
        }

        return (int) DB::connection('sqlsrv_moto_old')
            ->table('BaoCaoI')
            ->where('MaKH', $maKhoaHoc)
            ->count();
    }

    /**
     * Giống COPY_DATA_GPLX: NOCHECK/CHECK trong lúc ghi (tránh vướng FK tạm thời).
     */
    private function setTableConstraintChecks(bool $check): void
    {
        $state = $check ? 'CHECK' : 'NOCHECK';
        $conn = DB::connection('sqlsrv_moto_old');

        foreach (['NguoiLX', 'NguoiLX_HoSo'] as $table) {
            $conn->statement("ALTER TABLE [{$table}] {$state} CONSTRAINT ALL");
        }
    }

    /**
     * Cột chuỗi trên DB cũ ngắn hơn bản mới → cắt bớt để tránh "String or binary data would be truncated"
     * (khuyến nghị vẫn chạy PUBLISH_DATA_GPLX/sql/01-alter-ban-cu-column-widths-moto.sql trên đích).
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function clipPayloadToOldColumnLengths(string $table, array $payload): array
    {
        $limits = $this->oldStringColumnCharLimits($table);

        foreach ($payload as $col => $val) {
            if ($val === null || ! is_string($val) || ! isset($limits[$col])) {
                continue;
            }

            $max = $limits[$col];
            if ($max > 0 && mb_strlen($val) > $max) {
                $payload[$col] = mb_substr($val, 0, $max);
            }
        }

        return $payload;
    }

    /**
     * @return array<string, int>
     */
    private function oldStringColumnCharLimits(string $table): array
    {
        static $cache = [];

        if (! array_key_exists($table, $cache)) {
            $rows = DB::connection('sqlsrv_moto_old')->select(
                'SELECT c.name, ty.name AS type_name, c.max_length
                FROM sys.columns c
                INNER JOIN sys.tables t ON c.object_id = t.object_id
                INNER JOIN sys.types ty ON c.user_type_id = ty.user_type_id
                WHERE t.name = ? AND ty.name IN (\'nvarchar\', \'varchar\', \'nchar\', \'char\')',
                [$table]
            );

            $limits = [];
            foreach ($rows as $row) {
                $maxLength = (int) $row->max_length;
                if ($maxLength < 0) {
                    continue;
                }

                $isWide = in_array((string) $row->type_name, ['nvarchar', 'nchar'], true);
                $limits[(string) $row->name] = $isWide ? (int) ($maxLength / 2) : $maxLength;
            }

            $cache[$table] = $limits;
        }

        return $cache[$table];
    }
}
