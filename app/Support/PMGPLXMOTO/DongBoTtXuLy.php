<?php

namespace App\Support\PMGPLXMOTO;

/**
 * TT_XuLy: cột trên NguoiLX_HoSo; mã tham chiếu DM_TrangThai.MaTT.
 */
final class DongBoTtXuLy
{
    public const CHOICE_KEEP = 'keep';

    public const CHOICE_03 = '03';

    public const CHOICE_04 = '04';

    /** @var list<string> */
    public const CHOICES = [self::CHOICE_KEEP, self::CHOICE_03, self::CHOICE_04];

    /**
     * PM cũ thường chỉ cần giai đoạn đầu (03, 04); 05+ là trạng thái nâng cao / tốt nghiệp trên PM mới.
     */
    public static function isGreaterThan04(mixed $ttXuLy): bool
    {
        $raw = trim((string) $ttXuLy);
        if ($raw === '' || ! ctype_digit($raw)) {
            return false;
        }

        return (int) $raw > 4;
    }

    public static function sanitizeChoice(?string $choice): string
    {
        $choice = trim((string) $choice);

        return in_array($choice, self::CHOICES, true) ? $choice : self::CHOICE_KEEP;
    }

    /**
     * @param  array<string, mixed>  $perStudent  keyed by MaDK nguồn
     */
    public static function choiceForSourceMaDk(string $sourceMaDk, string $defaultChoice, array $perStudent): string
    {
        if (array_key_exists($sourceMaDk, $perStudent)) {
            return self::sanitizeChoice((string) $perStudent[$sourceMaDk]);
        }

        return self::sanitizeChoice($defaultChoice);
    }

    /**
     * Áp dụng khi ghi NguoiLX_HoSo.TT_XuLy sang bản cũ — chỉ đổi nếu nguồn &gt; 04 và người dùng chọn 03/04.
     */
    public static function valueForOldDatabase(mixed $sourceTtXuLy, string $choice): mixed
    {
        if (! self::isGreaterThan04($sourceTtXuLy)) {
            return $sourceTtXuLy;
        }

        return match (self::sanitizeChoice($choice)) {
            self::CHOICE_03, self::CHOICE_04 => self::sanitizeChoice($choice),
            default => $sourceTtXuLy,
        };
    }

    public static function displayCode(mixed $ttXuLy): string
    {
        $raw = trim((string) $ttXuLy);

        return $raw === '' ? '—' : str_pad($raw, 2, '0', STR_PAD_LEFT);
    }
}