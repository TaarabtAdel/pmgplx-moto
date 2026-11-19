<?php

namespace App\Support\PMGPLX;

use App\Models\PMGPLXMOTO\NguoiLX;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Lọc danh sách học viên PMGPLX-MOTO. */
final class DanhSachHocVienFilter
{
    public const CONNECTION = 'sqlsrv_moto';

    /**
     * @param  list<\Illuminate\Contracts\Database\Query\Expression|string>|null  $select
     */
    public static function query(Request $request, ?array $select = null): Builder
    {
        $query = NguoiLX::query()->from('NguoiLX as n');
        $query->leftJoin('NguoiLX_HoSo as h', 'h.MaDK', '=', 'n.MaDK');

        if ($select !== null) {
            $query->select($select);
        } else {
            $query->select(self::defaultSelect());
        }

        self::apply($query, $request);

        return $query;
    }

    /** @return list<string> */
    public static function defaultSelect(): array
    {
        return [
            'n.MaDK',
            'n.HoDemNLX',
            'n.TenNLX',
            'n.HoVaTen',
            'n.NgaySinh',
            'n.GioiTinh',
            'n.SoCMT',
            'n.NgayCapCMT',
            'n.NoiTT',
            'n.NoiTT_MaDVHC',
            'n.NoiCT',
            'n.NoiCT_MaDVHC',
            'n.TrangThai',
            'h.SoHoSo',
            'h.MaKhoaHoc',
            'h.HangGPLX',
            'h.HangDaoTao',
            'h.SoGPLXDaCo',
            'h.NgayCapGPLXDaCo',
            'h.DonViCapGPLXDaCo',
        ];
    }

    public static function apply(Builder $query, Request $request): void
    {
        if ($keyword = trim((string) $request->input('tu_khoa'))) {
            $like = '%'.$keyword.'%';
            $query->where(function ($q) use ($like) {
                foreach (['n.MaDK', 'n.HoVaTen', 'n.HoDemNLX', 'n.TenNLX', 'n.SoCMT', 'h.SoHoSo'] as $cot) {
                    $q->orWhereRaw($cot.' COLLATE Latin1_General_CI_AI LIKE ?', [$like]);
                }
            });
        }

        if ($maKh = trim((string) $request->input('ma_kh'))) {
            $query->where('h.MaKhoaHoc', $maKh);
        }

        if ($hang = trim((string) $request->input('hang_gplx'))) {
            $query->where('h.HangGPLX', 'like', '%'.$hang.'%');
        }

        if ($request->filled('trang_thai') && $request->input('trang_thai') !== '') {
            $query->where('n.TrangThai', (string) (int) $request->input('trang_thai'));
        }
    }

    /**
     * @return array{0: list<string>, 1: list<\Illuminate\Contracts\Database\Query\Expression|string>}
     */
    public static function exportColumnSelects(?string $connection = null): array
    {
        $connection ??= self::CONNECTION;

        $colsN = Schema::connection($connection)->getColumnListing('NguoiLX');
        $colsH = Schema::connection($connection)->getColumnListing('NguoiLX_HoSo');

        $columns = [];
        $selects = [];
        foreach ($colsN as $col) {
            $alias = 'NguoiLX.'.$col;
            $columns[] = $alias;
            $selects[] = DB::raw('n.['.$col.'] as ['.$alias.']');
        }
        foreach ($colsH as $col) {
            $alias = 'NguoiLX_HoSo.'.$col;
            $columns[] = $alias;
            $selects[] = DB::raw('h.['.$col.'] as ['.$alias.']');
        }

        return [$columns, $selects];
    }
}