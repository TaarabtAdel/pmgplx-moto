<?php

namespace App\Support\DaoTao;

use Illuminate\Support\Collection;

/**
 * PMGPLX-MOTO: chưa nối DAT — trả thống kê rỗng để xem danh sách lịch xe vẫn mở được.
 */
class DatPhienLichXeThongKe
{
    /**
     * @param  Collection<int, object>  $lichRows
     * @return array<int, array{so_phien: int, tong_gio: float}>
     */
    public static function forScheduleRows(Collection $lichRows): array
    {
        $stats = [];
        foreach ($lichRows as $row) {
            $maLich = (int) ($row->MaLichSD ?? 0);
            if ($maLich <= 0) {
                continue;
            }
            $stats[$maLich] = ['so_phien' => 0, 'tong_gio' => 0.0];
        }

        return $stats;
    }
}