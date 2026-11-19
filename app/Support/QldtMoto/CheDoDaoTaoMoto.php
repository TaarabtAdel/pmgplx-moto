<?php

namespace App\Support\QldtMoto;

/** GPLX mô tô — chỉ hạng A (DB PM: thường ghi `A1m`). */
final class CheDoDaoTaoMoto
{
    public const HANG_A1 = 'A1';

    public const HANG_A2 = 'A2';

    /** @var list<string> */
    public const HANG_HOP_LE = [self::HANG_A1, self::HANG_A2];

    /** @return array<string, string> */
    public static function nhanHang(): array
    {
        return [
            self::HANG_A1 => 'A1 (mô tô)',
            self::HANG_A2 => 'A2',
        ];
    }

    public static function isHangMoto(?string $hang): bool
    {
        return in_array(self::chuanHoaHang($hang), self::HANG_HOP_LE, true);
    }

    public static function chuanHoaHang(?string $hang): string
    {
        $h = strtoupper(trim((string) $hang));
        if ($h === '' || str_starts_with($h, 'A1')) {
            return self::HANG_A1;
        }
        if (str_starts_with($h, 'A2')) {
            return self::HANG_A2;
        }

        return self::HANG_A1;
    }

    /** Số khóa từ tên PM (vd `K26A1K67` → `67`). */
    public static function soKhoaTuTen(?string $tenKh): string
    {
        $ten = trim((string) $tenKh);
        if (preg_match('/A1K(\d+)/i', $ten, $m)) {
            return $m[1];
        }
        if (preg_match('/K(\d+)$/i', $ten, $m)) {
            return $m[1];
        }

        return '';
    }
}