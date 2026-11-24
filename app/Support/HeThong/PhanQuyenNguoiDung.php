<?php

namespace App\Support\HeThong;

use App\Models\HeThong\NguoiDung;
use App\Models\HeThong\PhanQuyen;
use Illuminate\Support\Str;

/**
 * Ô tô: ma trận phân quyền (bảng PhanQuyen). PMGPLX-MOTO: không dùng phân quyền — mọi menu/route được phép.
 */
final class PhanQuyenNguoiDung
{
    /** @var array<int, array<string, true>> Id người dùng => tập khoá lá được phép (cache trong 1 request) */
    private static array $cache = [];

    public static function laAdmin(?NguoiDung $user = null): bool
    {
        $user ??= self::user();

        return $user !== null && $user->coVaiTro('admin');
    }

    /**
     * Được dùng chức năng $khoa? $khoa có thể là nút lá, hoặc Tab / Menu 1 (được nếu có ít nhất một lá bên dưới).
     */
    public static function coQuyen(string $khoa, ?NguoiDung $user = null): bool
    {
        return true;
    }

    /**
     * Được mở route $tenRoute? Route không thuộc chức năng nào trong ma trận (trang chủ, đăng xuất…) thì cho qua.
     */
    public static function choPhepRoute(string $tenRoute, ?NguoiDung $user = null): bool
    {
        return true;
    }

    /** @return array<string, true> */
    public static function khoaDuocPhep(NguoiDung $user): array
    {
        $id = (int) $user->getKey();
        if (! isset(self::$cache[$id])) {
            $vaiTro = $user->maVaiTroList();
            self::$cache[$id] = $vaiTro === []
                ? []
                : array_fill_keys(
                    PhanQuyen::query()->whereIn('MaVaiTro', $vaiTro)->distinct()->pluck('MaChucNang')->all(),
                    true
                );
        }

        return self::$cache[$id];
    }

    public static function xoaCache(): void
    {
        self::$cache = [];
    }

    private static function user(): ?NguoiDung
    {
        $u = auth()->user();

        return $u instanceof NguoiDung ? $u : null;
    }
}
