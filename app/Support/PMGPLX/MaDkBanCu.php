<?php

namespace App\Support\PMGPLX;

class MaDkBanCu
{
    /**
     * Tiền tố mã ĐK (5 ký tự đầu mã khóa học).
     */
    public static function prefixFromMaKhoaHoc(string $maKhoaHoc): string
    {
        return substr(trim($maKhoaHoc), 0, 5);
    }

    /**
     * Lấy mã ĐK bản cũ từ mã ĐK phần mềm mới — chỉ thay tiền tố mã khóa (vd. 44007 → 45003),
     * giữ nguyên phần sau dấu - đầu (vd. 44007-20260831-000002 → 45003-20260831-000002).
     */
    public static function mapFromSource(string $sourceMaDk, string $maKhoaHocDich, ?string $maKhoaHocNguon = null): string
    {
        $newPrefix = self::prefixFromMaKhoaHoc($maKhoaHocDich);
        $oldPrefix = ($maKhoaHocNguon !== null && $maKhoaHocNguon !== '')
            ? self::prefixFromMaKhoaHoc($maKhoaHocNguon)
            : substr(trim($sourceMaDk), 0, 5);

        return self::mapWithPrefix((string) $sourceMaDk, $oldPrefix, $newPrefix);
    }

    /**
     * Giống COPY_DATA_GPLX: NewPrefix + SUBSTRING(MaDK, LEN(OldPrefix) + 1, …).
     */
    public static function mapWithPrefix(string $sourceMaDk, string $oldPrefix, string $newPrefix): string
    {
        $sourceMaDk = trim($sourceMaDk);
        $oldPrefix = trim($oldPrefix);
        $newPrefix = trim($newPrefix);

        if ($sourceMaDk === '' || $newPrefix === '') {
            return '';
        }

        if ($oldPrefix !== '' && str_starts_with($sourceMaDk, $oldPrefix)) {
            return $newPrefix.substr($sourceMaDk, strlen($oldPrefix));
        }

        $dashPos = strpos($sourceMaDk, '-');

        return $dashPos === false
            ? $newPrefix
            : $newPrefix.substr($sourceMaDk, $dashPos);
    }

    /** Lọc CopyNguoiLX: MaDK LIKE OldPrefix + '-%'. */
    public static function maDkMatchesCopyFilter(string $maDk, string $oldPrefix): bool
    {
        $oldPrefix = trim($oldPrefix);
        $maDk = trim($maDk);

        if ($oldPrefix === '' || $maDk === '') {
            return false;
        }

        if (! str_starts_with($maDk, $oldPrefix)) {
            return false;
        }

        return strlen($maDk) > strlen($oldPrefix) && $maDk[strlen($oldPrefix)] === '-';
    }
}
