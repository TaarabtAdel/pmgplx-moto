<?php

namespace App\Support\QldtMoto;

/** Tra cứu mẫu A1-K67 ↔ slug trang QLĐT XE MÁY. */
final class MauKhoaMoto
{
    /** @return array{mau: list<string>, ghi_chu: string, trang_thai: string}|null */
    public static function choTrang(string $slug): ?array
    {
        $row = config("qldt_moto_mau_a1_k67.{$slug}");
        if (! is_array($row)) {
            return null;
        }

        return [
            'mau' => array_values($row['mau'] ?? []),
            'ghi_chu' => (string) ($row['ghi_chu'] ?? ''),
            'trang_thai' => (string) ($row['trang_thai'] ?? 'chua'),
        ];
    }

    public static function nhanTrangThai(string $trangThai): string
    {
        return match ($trangThai) {
            'pmgplx' => 'Đã có trên PMGPLX-MOTO',
            'port_oto' => 'Port từ QLĐT Ô TÔ (mẫu A1 cần chỉnh)',
            'thu_cong' => 'Thủ công / ngoài PM',
            default => 'Chưa có màn xuất',
        };
    }
}