<?php

namespace App\Support\QldtMoto;

/** Lịch TH xe máy: hạng A dùng bảng ngày đơn giản (mẫu A1-K67). */
final class CheDoThucHanhMoto
{
    /** Danh sách xe tập trên mẫu A1-K67 «5. LỊCH HỌC THỰC HÀNH». */
    public const XE_TAP_MAC_DINH_A1 = [
        '74C1-473.48',
        '74H4-6991',
        '74C1-459.76',
        '74H4-1880',
        '74C1-529.33',
        '74B1-172.18',
        '74C1-403.14',
        '74C1-503.96',
        '74AC-037.04',
        '74K1-018.30',
        '74AA-706.34',
        '74C1-326.64',
    ];

    public const GIO_MOI_HV = 4.0;

    /** @var list<string> */
    public const HANG_DON_GIAN = CheDoDaoTaoMoto::HANG_HOP_LE;

    public static function isDonGian(?string $hang): bool
    {
        return CheDoDaoTaoMoto::isHangMoto($hang);
    }

    /**
     * @return array{
     *     ten_gv: string,
     *     ma_gv: string,
     *     noi_dung: string,
     *     dia_diem: string,
     *     tong_so_hv: int,
     *     so_hv_ngay_day_du: int,
     *     so_gio_ngay_day_du: float,
     *     so_ngay: int,
     *     xe: list<string>,
     *     ngay_ky: ?string
     * }
     */
    public static function cauHinhMacDinh(): array
    {
        $base = [
            'ten_gv' => '',
            'ma_gv' => '',
            'noi_dung' => 'Thực hành lái xe',
            'dia_diem' => 'Sân sát hạch',
            'tong_so_hv' => 236,
            'so_hv_ngay_day_du' => 40,
            'so_gio_ngay_day_du' => 10.0,
            'xe' => self::XE_TAP_MAC_DINH_A1,
            'ngay_ky' => null,
        ];
        $base['so_ngay'] = count(self::phanBoNgayThucHanh(
            $base['tong_so_hv'],
            $base['so_hv_ngay_day_du'],
            $base['so_gio_ngay_day_du']
        ));

        return $base;
    }

    /** @return list<string> */
    public static function xeTapMacDinh(): array
    {
        return self::XE_TAP_MAC_DINH_A1;
    }

    /**
     * Chia tổng HV theo ngày: các ngày đủ + ngày cuối (HV và giờ giảm theo tỉ lệ).
     *
     * @return list<array{so_hv: int, so_gio: float}>
     */
    public static function phanBoNgayThucHanh(int $tongSoHv, int $hvNgayDayDu = 40, float $gioNgayDayDu = 10.0): array
    {
        $tongSoHv = max(1, $tongSoHv);
        $hvNgayDayDu = max(1, $hvNgayDayDu);
        $gioNgayDayDu = max(0.5, $gioNgayDayDu);

        $soNgayDayDu = intdiv($tongSoHv, $hvNgayDayDu);
        $du = $tongSoHv % $hvNgayDayDu;
        $soNgay = max(1, $soNgayDayDu + ($du > 0 ? 1 : 0));

        $dong = [];
        for ($i = 0; $i < $soNgay; $i++) {
            $laNgayCuoiLe = $du > 0 && $i === $soNgay - 1;
            $hv = $laNgayCuoiLe ? $du : $hvNgayDayDu;
            $gio = ($laNgayCuoiLe && $hv < $hvNgayDayDu)
                ? self::gioNgayLe($hv, $hvNgayDayDu, $gioNgayDayDu)
                : $gioNgayDayDu;
            $dong[] = ['so_hv' => $hv, 'so_gio' => $gio];
        }

        return $dong;
    }

    public static function gioNgayLe(int $hvNgay, int $hvNgayDayDu, float $gioNgayDayDu): float
    {
        return round($hvNgay / $hvNgayDayDu * $gioNgayDayDu, 1);
    }

    /**
     * @param  array<string, mixed>|null  $raw
     * @return array{
     *     ten_gv: string,
     *     ma_gv: string,
     *     noi_dung: string,
     *     dia_diem: string,
     *     tong_so_hv: int,
     *     so_hv_ngay_day_du: int,
     *     so_gio_ngay_day_du: float,
     *     so_ngay: int,
     *     xe: list<string>,
     *     ngay_ky: ?string
     * }
     */
    public static function normalizeCauHinh(?array $raw): array
    {
        $defaults = self::cauHinhMacDinh();
        $raw = is_array($raw) ? $raw : [];

        $ngayKy = trim((string) ($raw['ngay_ky'] ?? ''));
        if ($ngayKy !== '' && ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngayKy)) {
            $ngayKy = '';
        }

        $tenGv = trim((string) ($raw['ten_gv'] ?? ''));
        $maGv = trim((string) ($raw['ma_gv'] ?? ''));
        if ($tenGv === '' && $maGv !== '') {
            $tenGv = $maGv;
        }

        $tongSoHv = max(1, (int) ($raw['tong_so_hv'] ?? $raw['so_hoc_vien'] ?? $defaults['tong_so_hv']));
        $soHvNgay = max(1, (int) ($raw['so_hv_ngay_day_du'] ?? $defaults['so_hv_ngay_day_du']));
        $soGioNgay = max(0.5, (float) ($raw['so_gio_ngay_day_du'] ?? $raw['so_gio_ngay'] ?? $defaults['so_gio_ngay_day_du']));

        return [
            'ten_gv' => $tenGv,
            'ma_gv' => $maGv,
            'noi_dung' => trim((string) ($raw['noi_dung'] ?? $defaults['noi_dung'])) ?: $defaults['noi_dung'],
            'dia_diem' => trim((string) ($raw['dia_diem'] ?? $defaults['dia_diem'])) ?: $defaults['dia_diem'],
            'tong_so_hv' => $tongSoHv,
            'so_hv_ngay_day_du' => $soHvNgay,
            'so_gio_ngay_day_du' => $soGioNgay,
            'so_ngay' => count(self::phanBoNgayThucHanh($tongSoHv, $soHvNgay, $soGioNgay)),
            'xe' => self::XE_TAP_MAC_DINH_A1,
            'ngay_ky' => $ngayKy !== '' ? $ngayKy : null,
        ];
    }

    public static function chuoiXeTap(array $xe): string
    {
        $xe = array_values(array_filter(array_map('strval', $xe)));
        if ($xe === []) {
            return '';
        }

        return count($xe).' xe: '.implode(', ', $xe).'.';
    }
}