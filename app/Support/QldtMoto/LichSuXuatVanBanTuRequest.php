<?php

namespace App\Support\QldtMoto;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Http\Request;

/**
 * Trích số văn bản và ngày từ request (QLĐT XE MÁY).
 *
 * @return array{so_cong_van: ?string, ngay_van_ban: ?Carbon, ma_kh: ?string}
 */
final class LichSuXuatVanBanTuRequest
{
    public static function trichXuat(Request $request, string $tenRoute): array
    {
        $data = array_merge(
            $request->query(),
            $request->except(['_token', '_method']),
        );

        $ngay = self::ngayTuMang($data);
        $so = self::soTuMang($data, $ngay, $tenRoute);
        $maKh = self::chuoiTuMang($data, ['ma_kh', 'ma_khoa_hoc', 'MaKh']);

        return [
            'so_cong_van' => $so !== '' ? mb_substr($so, 0, 80) : null,
            'ngay_van_ban' => $ngay,
            'ma_kh' => $maKh !== null ? mb_substr($maKh, 0, 50) : null,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function soTuMang(array $data, ?Carbon $ngay, string $tenRoute): string
    {
        if ($tb = trim((string) ($data['so_tb'] ?? ''))) {
            if ($tenRoute === 'qldtmoto.thong-bao-tap-trung-thuc-hanh.export') {
                return $tb.ThongBaoTapTrungThucHanhDocx::HAU_TO_SO_TB;
            }

            return $tb.ThongBaoKiemTraKetThucMonDocx::HAU_TO_SO;
        }
        foreach (['so_cong_van', 'so_bc', 'so_qd', 'so_quyet_dinh'] as $key) {
            $v = trim((string) ($data[$key] ?? ''));
            if ($v === '') {
                continue;
            }
            if ($key === 'so_qd' || $key === 'so_quyet_dinh') {
                return str_contains($v, '/') ? $v : $v.SoTayQuyetDinhThanhLap::HAU_TO_SO_QD;
            }

            return self::dinhdangTtml($v, $ngay);
        }

        return '';
    }

    private static function dinhdangTtml(string $so, ?Carbon $ngay): string
    {
        if (str_contains($so, '/')) {
            return $so;
        }
        $nam = $ngay?->year ?? (int) date('Y');

        return $so.'/TTML-'.$nam;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function ngayTuMang(array $data): ?Carbon
    {
        foreach (['ngay_bao_cao', 'ngay_quyet_dinh', 'ngay_lap', 'ngay_thong_bao'] as $key) {
            $v = trim((string) ($data[$key] ?? ''));
            if ($v === '') {
                continue;
            }
            try {
                return Carbon::parse($v)->startOfDay();
            } catch (InvalidFormatException) {
                continue;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $keys
     */
    private static function chuoiTuMang(array $data, array $keys): ?string
    {
        foreach ($keys as $key) {
            $v = trim((string) ($data[$key] ?? ''));
            if ($v !== '') {
                return $v;
            }
        }

        return null;
    }
}