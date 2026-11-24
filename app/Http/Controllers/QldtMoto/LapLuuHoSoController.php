<?php

namespace App\Http\Controllers\QldtMoto;

use App\Http\Controllers\Controller;
use App\Models\PMGPLXMOTO\KhoaHoc;
use App\Support\MenuQldt;
use App\Support\QldtMoto\LapLuuHoSoSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LapLuuHoSoController extends Controller
{
    public function index(Request $request): View
    {
        $slug = self::slugTuRequest($request);
        $trang = MenuQldt::timTrang($slug);
        abort_if($trang === null, 404);

        $loai = LapLuuHoSoSupport::loaiTuSlug($slug);
        $mau = LapLuuHoSoSupport::mauTuSlug($slug);

        $maKh = trim((string) $request->query('ma_kh', ''));
        $khoa = $maKh !== '' ? KhoaHoc::query()->find($maKh) : null;

        $khoaOptions = KhoaHoc::query()
            ->select('MaKH', 'TenKH', 'HangDT', 'NgayKG')
            ->orderByDesc('NgayKG')
            ->orderByDesc('MaKH')
            ->limit(500)
            ->get();

        return view('QldtMoto.lap-luu-ho-so', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
            'slug' => $slug,
            'loai' => $loai,
            'mau' => $mau,
            'khoaOptions' => $khoaOptions,
            'form' => LapLuuHoSoSupport::formTuKhoa($khoa, $loai),
        ]);
    }

    public function export(Request $request): BinaryFileResponse|RedirectResponse
    {
        $slug = self::slugTuRequest($request);
        $loai = LapLuuHoSoSupport::loaiTuSlug($slug);
        $mau = LapLuuHoSoSupport::mauTuSlug($slug);

        $validated = $request->validate([
            'so_qd_lap' => ['required', 'string', 'max:20'],
            'so_qd_luu' => ['required', 'string', 'max:20'],
            'ngay_quyet_dinh' => ['required', 'date'],
            'ma_kh' => ['nullable', 'string', 'max:50'],
            'ten_khoa' => ['required', 'string', 'max:40'],
            'giao_vien' => ['required', 'string', 'max:120'],
            'chuc_vu' => ['required', 'string', 'max:80'],
            'phong_to' => ['required', 'string', 'max:80'],
            'lanh_dao' => ['required', 'string', 'max:120'],
            'so_ho_so' => ['required', 'string', 'max:40'],
            'so_luu_tru' => ['required', 'string', 'max:50'],
            'ngay_lap' => ['required', 'date'],
            'ngay_ket_thuc' => ['required', 'date'],
            'ngay_nop_luu' => ['required', 'date'],
            'tinh_trang_ho_so' => ['required', 'string', 'max:200'],
            'tong_so' => ['required', 'string', 'max:40'],
            'thoi_han_bao_quan' => ['nullable', 'string', 'max:2000'],
        ]);

        $out = sys_get_temp_dir().'/'.uniqid('lap-luu-', true).'.docx';

        try {
            LapLuuHoSoSupport::generate($loai, $mau, array_map('strval', $validated), $out);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $tenFile = LapLuuHoSoSupport::tenFileTai($loai, $mau, $validated['ten_khoa']);

        return response()->download($out, $tenFile)->deleteFileAfterSend(true);
    }

    public function khoaJson(string $maKh): \Illuminate\Http\JsonResponse
    {
        $slug = self::slugTuRequest(request());
        $loai = LapLuuHoSoSupport::loaiTuSlug($slug);
        $khoa = KhoaHoc::query()->find($maKh);
        if (! $khoa) {
            return response()->json(['message' => 'Không tìm thấy khoá.'], 404);
        }

        return response()->json(LapLuuHoSoSupport::formTuKhoa($khoa, $loai));
    }

    private static function slugTuRequest(Request $request): string
    {
        $route = $request->route();
        if ($route !== null && isset($route->defaults['menu_trang'])) {
            return (string) $route->defaults['menu_trang'];
        }

        return (string) $request->route('menu_trang', '');
    }
}