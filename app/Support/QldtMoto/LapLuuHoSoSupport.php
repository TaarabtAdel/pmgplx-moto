<?php

namespace App\Support\QldtMoto;

use App\Models\PMGPLXMOTO\KhoaHoc;
use App\Support\Docx\DocxTextReplacer;
use Carbon\Carbon;

/** Lập / lưu hồ sơ (mẫu B1, B2, B6, B10) — nguồn `resources/templates/moto/luu-ho-so/*.goc.docx`. */
final class LapLuuHoSoSupport
{
    public const LOAI_QUAN_LY = 'ql';

    public const LOAI_DAO_TAO = 'dt';

    public const MAU_QD_LAP = 'qd_lap';

    public const MAU_QD_LUU = 'qd_luu';

    public const TEMPLATE_QL = 'templates/moto/luu-ho-so/ho-so-quan-ly-dao-tao.goc.docx';

    public const TEMPLATE_DT = 'templates/moto/luu-ho-so/ho-so-dao-tao.goc.docx';

    /** @return list<string> */
    public static function slugs(): array
    {
        $out = [];
        foreach ([self::LOAI_QUAN_LY, self::LOAI_DAO_TAO] as $loai) {
            $prefix = $loai === self::LOAI_QUAN_LY ? '4-ql' : '4-dt';
            $out[] = $prefix.'-quyet-dinh-lap-ho-so';
            $out[] = $prefix.'-quyet-dinh-luu-ho-so';
        }

        return $out;
    }

    public static function loaiTuSlug(string $slug): string
    {
        return str_starts_with($slug, '4-ql-') ? self::LOAI_QUAN_LY : self::LOAI_DAO_TAO;
    }

    public static function mauTuSlug(string $slug): string
    {
        return str_contains($slug, 'quyet-dinh-luu-ho-so')
            ? self::MAU_QD_LUU
            : self::MAU_QD_LAP;
    }

    public static function nhanLoai(string $loai): string
    {
        return $loai === self::LOAI_QUAN_LY ? 'Hồ sơ quản lý đào tạo' : 'Hồ sơ đào tạo';
    }

    public static function tienToSoHoSo(string $loai): string
    {
        return $loai === self::LOAI_QUAN_LY ? 'QL' : 'ĐT';
    }

    public static function templatePath(string $loai): string
    {
        $rel = $loai === self::LOAI_QUAN_LY ? self::TEMPLATE_QL : self::TEMPLATE_DT;
        $path = resource_path($rel);
        if (! is_file($path)) {
            throw new \RuntimeException('Chưa có mẫu: '.$rel);
        }

        return $path;
    }

    /**
     * @return array{
     *   so_qd_lap: string,
     *   so_qd_luu: string,
     *   ngay_quyet_dinh: string,
     *   ma_kh: string,
     *   ten_khoa: string,
     *   hang_label: string,
     *   khoa_so: string,
     *   so_ho_so: string,
     *   so_luu_tru: string,
     *   giao_vien: string,
     *   chuc_vu: string,
     *   phong_to: string,
     *   ngay_lap: string,
     *   ngay_ket_thuc: string,
     *   ngay_nop_luu: string,
     *   tinh_trang_ho_so: string,
     *   tong_so: string,
     *   thoi_han_bao_quan: string,
     *   lanh_dao: string,
     * }
     */
    public static function formTuKhoa(?KhoaHoc $khoa, string $loai): array
    {
        $now = Carbon::now()->format('Y-m-d');
        $defaults = [
            'so_qd_lap' => $loai === self::LOAI_QUAN_LY ? '306' : '308',
            'so_qd_luu' => $loai === self::LOAI_QUAN_LY ? '307' : '309',
            'ngay_quyet_dinh' => $now,
            'ma_kh' => '',
            'ten_khoa' => 'A1-K68',
            'hang_label' => 'A1',
            'khoa_so' => '68',
            'so_ho_so' => '',
            'so_luu_tru' => '',
            'giao_vien' => 'Mai Thị Lệ Huyền',
            'chuc_vu' => $loai === self::LOAI_DAO_TAO ? 'Giáo viên' : 'Giáo viên',
            'phong_to' => $loai === self::LOAI_DAO_TAO ? 'Tổ lý thuyết' : 'Phòng đào tạo',
            'ngay_lap' => $now,
            'ngay_ket_thuc' => $now,
            'ngay_nop_luu' => $now,
            'tinh_trang_ho_so' => $loai === self::LOAI_QUAN_LY ? 'Đầy đủ' : 'Tốt',
            'tong_so' => $loai === self::LOAI_QUAN_LY ? '71' : '05 cuốn',
            'thoi_han_bao_quan' => $loai === self::LOAI_QUAN_LY
                ? 'Không thời hạn đối với tài liệu thuộc stt 07; 05 năm đối với tài liệu thuộc stt 01 và 08; 03 năm đối với các tài liệu còn lại.'
                : 'Lưu trữ: 03 năm',
            'lanh_dao' => 'Trương Hoàng Linh',
        ];

        if (! $khoa instanceof KhoaHoc) {
            return $defaults;
        }

        $tu = BaoCaoDangKyKhoaHoc::tuKhoaHoc($khoa);
        $hang = trim((string) ($khoa->HangDT ?? $tu['hang_dt'] ?? 'A1'));
        $so = BaoCaoDangKyKhoaHoc::soKhoaTuTen($khoa->TenKH ?? '') ?? $tu['khoa_so'] ?? '68';
        $tenKhoa = trim($hang.'-K'.$so);
        $nam2 = substr((string) Carbon::now()->year, -2);
        $soHs = self::tienToSoHoSo($loai).$nam2.$hang.'K'.$so;
        $soHsSpaced = self::formatSoHoSoCoKhoang($loai, $nam2, $hang, $so);

        return array_merge($defaults, [
            'ma_kh' => (string) $khoa->MaKH,
            'ten_khoa' => $tenKhoa,
            'hang_label' => $hang,
            'khoa_so' => (string) $so,
            'so_ho_so' => $soHsSpaced,
            'so_luu_tru' => 'NL-'.$soHs,
        ]);
    }

    public static function formatSoHoSoCoKhoang(string $loai, string $nam2, string $hang, string $so): string
    {
        if ($loai === self::LOAI_QUAN_LY) {
            return 'QL'.$nam2.' '.$hang.'K '.$so;
        }

        return 'ĐT'.$nam2.' '.$hang.' K '.$so;
    }

    /**
     * @param  array<string, string>  $data
     */
    public static function generate(string $loai, string $mau, array $data, string $outputPath): string
    {
        $src = self::templatePath($loai);
        @mkdir(dirname($outputPath), 0755, true);
        if (! copy($src, $outputPath)) {
            throw new \RuntimeException('Không copy được mẫu Word.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($outputPath) !== true) {
            throw new \RuntimeException('Không mở được file docx.');
        }
        $xml = (string) $zip->getFromName('word/document.xml');
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = true;
        $dom->loadXML($xml);
        $xpath = DocxTextReplacer::xpath($dom);
        $body = $xpath->query('//w:body')->item(0);
        if (! $body instanceof \DOMNode) {
            throw new \RuntimeException('document.xml không hợp lệ.');
        }

        $ngay = Carbon::parse($data['ngay_quyet_dinh']);
        $ngayDong = 'Quảng Trị, ngày '.BaoCaoDangKyKhoaHoc::dongNgayThangNam($ngay);
        $ngaySlashes = BaoCaoDangKyKhoaHoc::dinhDangNgay($ngay);

        $khoaChu = 'khóa '.$data['ten_khoa'];
        $khoaChu2 = 'Khóa '.$data['ten_khoa'];
        $khoaSpace = str_replace('-', ' ', $data['ten_khoa']);

        $replacements = [
            '306 /QĐ-TTML' => trim($data['so_qd_lap']).QuyetDinhDieuDongXeSupport::HAU_TO_SO_QD,
            '307 /QĐ-TTML' => trim($data['so_qd_luu']).QuyetDinhDieuDongXeSupport::HAU_TO_SO_QD,
            '308 /QĐ-TTML' => trim($data['so_qd_lap']).QuyetDinhDieuDongXeSupport::HAU_TO_SO_QD,
            '309 /QĐ-TTML' => trim($data['so_qd_luu']).QuyetDinhDieuDongXeSupport::HAU_TO_SO_QD,
            'Quảng Trị, ngày 22 tháng 09 năm 20 26' => $ngayDong,
            'Quảng Trị, ngày 22 tháng 0 9 năm 20 26' => $ngayDong,
            'Mai Thị Lệ Huyền' => $data['giao_vien'],
            'Trương Hoàng Linh' => $data['lanh_dao'],
            'Phòng đào tạo' => $data['phong_to'],
            'Tổ lý thuyết' => $data['phong_to'],
            'Giáo viên' => $data['chuc_vu'],
            'Giáo viên .' => $data['chuc_vu'].' .',
            'khóa A1- K 68' => $khoaChu,
            'khóa A1 -K 68' => $khoaChu,
            'Khóa A1 -K 68' => $khoaChu2,
            'Khóa A1 -K68' => $khoaChu2,
            'A1- K 68' => $data['ten_khoa'],
            'A1-K 68' => str_replace(' ', '', $data['ten_khoa']),
            'A1 -K68' => $data['ten_khoa'],
            'A1- K67' => $data['ten_khoa'],
            'A1-K67' => str_replace(' ', '', $data['ten_khoa']),
            'QL26 A1K 68' => $data['so_ho_so'],
            'QL2 6 A1K 67' => $data['so_ho_so'],
            'QL26 A1K67' => preg_replace('/\s+/', '', $data['so_ho_so']) ?? $data['so_ho_so'],
            'ĐT26 A1 K 68' => $data['so_ho_so'],
            'ĐT2 6 A1 K 68' => $data['so_ho_so'],
            'NL - QL26 A1K 67' => $data['so_luu_tru'],
            'NL - QL26 A1K 6 7' => $data['so_luu_tru'],
            'NL - ĐT2 6 A1 K 68' => $data['so_luu_tru'],
            'NL - ĐT 2 6 A1 K 68' => $data['so_luu_tru'],
            '12/08 /2026' => BaoCaoDangKyKhoaHoc::dinhDangNgay(Carbon::parse($data['ngay_lap'])),
            '22/09 /2026' => BaoCaoDangKyKhoaHoc::dinhDangNgay(Carbon::parse($data['ngay_nop_luu'])),
            'Tình trạng hồ sơ: Đầy đủ' => 'Tình trạng hồ sơ: '.$data['tinh_trang_ho_so'],
            'Tình trạng hồ sơ: Tốt' => 'Tình trạng hồ sơ: '.$data['tinh_trang_ho_so'],
            'Tổng số tờ: 71' => 'Tổng số tờ: '.$data['tong_so'],
            'Tổng số: 05 cuốn' => 'Tổng số: '.$data['tong_so'],
        ];

        foreach ($replacements as $from => $to) {
            DocxTextReplacer::replace($xpath, $body, $from, $to);
        }

        $zip->addFromString('word/document.xml', $dom->saveXML());
        $zip->close();

        return $outputPath;
    }

    public static function tenFileTai(string $loai, string $mau, string $tenKhoa): string
    {
        $safe = preg_replace('/[^\p{L}\p{N}\-_]+/u', '_', $tenKhoa) ?: 'khoa';
        $suffix = $mau === self::MAU_QD_LUU ? 'qd-luu-ho-so' : 'qd-lap-ho-so';
        $prefix = $loai === self::LOAI_QUAN_LY ? 'QL' : 'DT';

        return $prefix.'-'.$safe.'-'.$suffix.'.docx';
    }
}