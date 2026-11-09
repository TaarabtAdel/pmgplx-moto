<?php

namespace App\Support\HeThong;

use Illuminate\Support\Str;

/**
 * Cây chức năng để phân quyền: Tab → Menu 1 → Menu 2 (tối đa 2 cấp dưới tab).
 * Dùng chung cho ma trận phân quyền, menu chính (layout) và middleware chặn route.
 *
 * Mỗi nút: khoa (vd. «pmgplx/lich/lich-xe»), title, route (đường dẫn của mục), active (mẫu tên route thuộc mục,
 * kiểu routeIs), divider (vạch ngăn phía trên), children. Quyền gán trên nút lá (Menu 1 không có con, hoặc Menu 2).
 * Tab QLĐT lấy từ config/menu_qldt.php; nhánh sâu hơn Menu 2 gộp vào Menu 2 (tích Menu 2 = cả nhánh).
 */
final class CayChucNang
{
    /** @var list<array<string, mixed>>|null */
    private static ?array $cay = null;

    /** @var array<string, array<string, mixed>>|null khoá lá => nút */
    private static ?array $la = null;

    /** @return list<array<string, mixed>> */
    public static function cay(): array
    {
        if (self::$cay !== null) {
            return self::$cay;
        }

        $cay = [];
        foreach (self::tinh() as $tab) {
            $cay[] = self::chuanHoa($tab, '');
        }

        return self::$cay = $cay;
    }

    /**
     * Các nút lá (đơn vị gán quyền), kèm đường dẫn tên Tab / Menu 1 / Menu 2.
     *
     * @return array<string, array{khoa: string, title: string, active: list<string>, duong_dan: list<string>}>
     */
    public static function laNut(): array
    {
        if (self::$la !== null) {
            return self::$la;
        }

        $out = [];
        $walk = function (array $nodes, array $duongDan) use (&$walk, &$out): void {
            foreach ($nodes as $n) {
                $dd = [...$duongDan, $n['title']];
                if ($n['children'] !== []) {
                    $walk($n['children'], $dd);
                } elseif (count($dd) > 1) {
                    $out[$n['khoa']] = ['khoa' => $n['khoa'], 'title' => $n['title'], 'active' => $n['active'], 'duong_dan' => $dd];
                }
            }
        };
        $walk(self::cay(), []);

        return self::$la = $out;
    }

    /** @return list<string> */
    public static function tatCaKhoaLa(): array
    {
        return array_keys(self::laNut());
    }

    /**
     * Mọi mẫu route của nút và các nút con (để tô sáng menu đang mở).
     *
     * @param  array<string, mixed>  $node
     * @return list<string>
     */
    public static function mauRoute(array $node): array
    {
        $out = $node['active'];
        foreach ($node['children'] as $c) {
            $out = [...$out, ...self::mauRoute($c)];
        }

        return array_values(array_unique($out));
    }

    public static function slug(string $title): string
    {
        return Str::slug($title) ?: substr(md5($title), 0, 8);
    }

    /** @param  array<string, mixed>  $node */
    private static function chuanHoa(array $node, string $khoaCha): array
    {
        $khoa = ($khoaCha === '' ? '' : $khoaCha.'/').$node['khoa'];
        $children = array_map(fn (array $c) => self::chuanHoa($c, $khoa), $node['children'] ?? []);

        return [
            'khoa' => $khoa,
            'title' => $node['title'],
            'route' => $node['route'] ?? null,
            'active' => $node['active'] ?? [],
            'divider' => (bool) ($node['divider'] ?? false),
            'children' => $children,
        ];
    }

    /** Khoá phân quyền của một tab trong config/menu_qldt.php (vd. «QLĐT Ô TÔ» → «qldt-o-to»). */
    public static function khoaTabQldt(array $tab): string
    {
        $slug = self::slug($tab['title']);

        return str_starts_with($slug, 'qldt-') ? $slug : 'qldt-'.$slug;
    }

    /** @param  array<string, mixed>  $tab  một tab trong config/menu_qldt.php */
    private static function tabQldt(array $tab): array
    {
        $khoaTab = self::khoaTabQldt($tab);
        $menu1 = [];
        foreach ($tab['children'] ?? [] as $m1) {
            $khoa1 = $khoaTab.'/'.self::slug($m1['title']);
            $menu2 = [];
            foreach ($m1['children'] ?? [] as $m2) {
                $menu2[] = [
                    'khoa' => $khoa1.'/'.self::slug($m2['title']),
                    'title' => $m2['title'],
                    'route' => null,
                    'active' => self::mauRouteQldt($m2),
                    'divider' => false,
                    'children' => [],
                ];
            }
            $menu1[] = [
                'khoa' => $khoa1,
                'title' => $m1['title'],
                'route' => null,
                'active' => $menu2 === [] ? self::mauRouteQldt($m1) : [],
                'divider' => false,
                'children' => $menu2,
            ];
        }

        return ['khoa' => $khoaTab, 'title' => $tab['title'], 'route' => null, 'active' => [], 'divider' => false, 'children' => $menu1];
    }

    /**
     * Mẫu tên route của một mục QLĐT và mọi mục con: «active» nếu có; route «x.index» → «x.*»;
     * mục chỉ có «trang» → route trang trắng qldtoto.{slug}.
     *
     * @param  array<string, mixed>  $item
     * @return list<string>
     */
    private static function mauRouteQldt(array $item): array
    {
        $out = [];
        if (! empty($item['children'])) {
            foreach ($item['children'] as $c) {
                $out = [...$out, ...self::mauRouteQldt($c)];
            }
        } elseif (! empty($item['active'])) {
            $out[] = (string) $item['active'];
        } elseif (! empty($item['route'])) {
            $r = (string) $item['route'];
            $out[] = str_ends_with($r, '.index') ? substr($r, 0, -6).'.*' : $r;
        } elseif (! empty($item['trang'])) {
            $out[] = MenuQldt::ROUTE_PREFIX.MenuQldt::tenRouteTrang((string) $item['trang']);
        }

        return array_values(array_unique($out));
    }

    /** Menu PMGPLX-MOTO (bản mới + học viên bản cũ). */
    private static function tinh(): array
    {
        return [
            [
                'khoa' => 'pmgplxmoto', 'title' => 'PMGPLX-MOTO', 'children' => [
                    ['khoa' => 'giao-vien', 'title' => 'Quản lý giáo viên', 'route' => 'pmgplxmoto.dm.giao-vien.index', 'active' => ['pmgplxmoto.dm.giao-vien.*']],
                    ['khoa' => 'hoc-vien', 'title' => 'Quản lý học viên', 'route' => 'pmgplxmoto.dm.hoc-vien.index', 'active' => ['pmgplxmoto.dm.hoc-vien.*']],
                    ['khoa' => 'hoc-vien-ban-cu', 'title' => 'Học viên (bản cũ)', 'route' => 'pmgplxmotoold.dm.hoc-vien.index', 'active' => ['pmgplxmotoold.*']],
                    ['khoa' => 'xe', 'title' => 'Quản lý xe', 'route' => 'pmgplxmoto.dm.xe.index', 'active' => ['pmgplxmoto.dm.xe.*']],
                    ['khoa' => 'khoa-hoc', 'title' => 'Quản lý khoá học', 'route' => 'pmgplxmoto.dm.khoa-hoc.index', 'active' => ['pmgplxmoto.dm.khoa-hoc.*']],
                ],
            ],
        ];
    }
}
