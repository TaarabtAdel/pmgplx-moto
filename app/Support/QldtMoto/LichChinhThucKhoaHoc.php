<?php

namespace App\Support\QldtMoto;

use App\Models\PMGPLXMOTO\KhoaHoc;
use App\Models\QldtMoto\LichChinhThuc;

/** Tra cứu khoá PMGPLX-MOTO (chỉ đọc — KG/BG, không ghi lịch vào PM). */
final class LichChinhThucKhoaHoc
{
    public static function tim(?LichChinhThuc $lich): ?KhoaHoc
    {
        if ($lich === null) {
            return null;
        }

        $maKh = trim((string) ($lich->MaKH ?? ''));
        if ($maKh !== '') {
            return KhoaHoc::query()->find($maKh);
        }

        $ten = trim((string) $lich->TenKhoa);
        if ($ten === '') {
            return null;
        }

        $exact = KhoaHoc::query()->where('TenKH', $ten)->orderByDesc('NgayKG')->first();
        if ($exact instanceof KhoaHoc) {
            return $exact;
        }

        return KhoaHoc::query()
            ->where('TenKH', 'like', '%'.$ten.'%')
            ->orderByDesc('NgayKG')
            ->first();
    }

    /**
     * @param  iterable<int, LichChinhThuc>  $lichs
     * @return array<string, KhoaHoc>
     */
    public static function mapTheoDanhSach(iterable $lichs): array
    {
        $maKhs = [];
        foreach ($lichs as $lich) {
            $ma = trim((string) ($lich->MaKH ?? ''));
            if ($ma !== '') {
                $maKhs[$ma] = true;
            }
        }

        if ($maKhs === []) {
            return [];
        }

        return KhoaHoc::query()
            ->whereIn('MaKH', array_keys($maKhs))
            ->get()
            ->keyBy('MaKH')
            ->all();
    }
}