<?php

namespace App\Http\Controllers\QldtMoto;

use App\Http\Controllers\Controller;
use App\Models\PMGPLXMOTO\GiaoVien;
use App\Models\PMGPLXMOTO\KhoaHoc;
use App\Support\MenuQldt;
use App\Support\QldtMoto\CheDoDaoTaoMoto;
use App\Support\QldtMoto\LichHocLyThuyetThucHanhA1;
use App\Support\QldtMoto\LichHocLyThuyetThucHanhHangA1Exporter;
use App\Support\QldtMoto\LichHocLyThuyetThucHanhLichNgay;
use App\Support\QldtMoto\PhanCongGvXeTapLaiFormSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/** Lịch dự kiến lý thuyết hạng A — mẫu A1-K67. */
class LichHocLyThuyetThucHanhController extends Controller
{
    private const SLUG = '1-lich-du-kien-ly-thuyet';

    public function index(Request $request): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        $maKh = trim((string) $request->query('ma_kh', ''));
        $hang = '';
        $khoa = $maKh !== '' ? KhoaHoc::query()->find($maKh) : null;
        $cauHinh = LichHocLyThuyetThucHanhA1::cauHinhMacDinh(null);
        $tenKhoaDoc = '';
        $previewTieuDe = '';

        if ($khoa instanceof KhoaHoc) {
            $hang = LichHocLyThuyetThucHanhLichNgay::hangGoiYTuKhoa($khoa)[0]
                ?? CheDoDaoTaoMoto::chuanHoaHang($khoa->HangGPLX);
            $tenKhoaDoc = PhanCongGvXeTapLaiFormSupport::tenKhoaDaoTaoTuPm($khoa);
            $raw = old('cau_hinh');
            if ($raw === null && $request->query('lop') !== null) {
                $raw = ['lop' => $request->query('lop')];
            }
            $cauHinh = LichHocLyThuyetThucHanhA1::normalizeCauHinh($raw, $khoa);
            $previewTieuDe = LichHocLyThuyetThucHanhA1::tieuDe($khoa, $cauHinh['lop'], $cauHinh['so_hoc_vien']);
        }

        $khoaOptions = KhoaHoc::query()
            ->select('MaKH', 'TenKH', 'NgayKG')
            ->orderByDesc('NgayKG')
            ->orderByDesc('MaKH')
            ->limit(500)
            ->get();

        $gvOptions = GiaoVien::query()
            ->where('TrangThai', 1)
            ->orderBy('TenGV')
            ->orderBy('HoTenDem')
            ->get(['MaGV', 'HoTenDem', 'TenGV'])
            ->map(fn (GiaoVien $g): array => [
                'ma' => trim((string) $g->MaGV),
                'ten' => $g->HoTen,
            ])
            ->all();

        return view('QldtMoto.lich-hoc-ly-thuyet-thuc-hanh.form', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
            'khoaOptions' => $khoaOptions,
            'maKh' => $maKh,
            'hang' => $hang,
            'khoa' => $khoa,
            'tenKhoaDoc' => $tenKhoaDoc,
            'cauHinh' => $cauHinh,
            'previewTieuDe' => $previewTieuDe,
            'nhanHang' => LichHocLyThuyetThucHanhLichNgay::nhanHang(),
            'lopOptions' => LichHocLyThuyetThucHanhA1::LOP,
            'gvOptions' => $gvOptions,
            'linkDanhMucKhoaHoc' => $maKh !== ''
                ? route('pmgplxmoto.dm.khoa-hoc.index', ['tu_khoa' => $maKh])
                : route('pmgplxmoto.dm.khoa-hoc.index'),
        ]);
    }

    public function export(Request $request): BinaryFileResponse|RedirectResponse
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        $validated = $request->validate([
            'ma_kh' => ['required', 'string'],
            'cau_hinh.lop' => ['required', 'string', 'in:01,02,03'],
            'cau_hinh.so_hoc_vien' => ['required', 'integer', 'min:1', 'max:999'],
            'cau_hinh.ngay_hoc' => ['required', 'date_format:Y-m-d'],
            'cau_hinh.ngay_ky' => ['nullable', 'date_format:Y-m-d'],
            'cau_hinh.buoi' => ['array', 'size:2'],
            'cau_hinh.buoi.*.mon' => ['required', 'string', 'max:255'],
            'cau_hinh.buoi.*.tong_gio' => ['required', 'numeric', 'min:0.5', 'max:24'],
            'cau_hinh.buoi.*.so_gio' => ['required', 'numeric', 'min:0.5', 'max:24'],
            'cau_hinh.buoi.*.dia_diem' => ['required', 'string', 'max:255'],
            'cau_hinh.buoi.*.gv' => ['nullable', 'string', 'max:200'],
            'cau_hinh.buoi.*.ghi_chu' => ['nullable', 'string', 'max:255'],
        ], [
            'ma_kh.required' => 'Chọn khoá.',
            'cau_hinh.ngay_hoc.required' => 'Chọn ngày học lý thuyết.',
        ]);

        $maKh = trim($validated['ma_kh']);
        $khoa = KhoaHoc::query()->find($maKh);
        if (! $khoa) {
            return back()->with('error', 'Không tìm thấy khoá.');
        }

        $hang = LichHocLyThuyetThucHanhLichNgay::hangGoiYTuKhoa($khoa)[0]
            ?? CheDoDaoTaoMoto::chuanHoaHang($khoa->HangGPLX);
        if (! CheDoDaoTaoMoto::isHangMoto($hang)) {
            return back()->with('error', 'Khoá không phải hạng A (mô tô).');
        }

        $cauHinh = LichHocLyThuyetThucHanhA1::normalizeCauHinh($validated['cau_hinh'], $khoa);

        try {
            $slug = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $maKh) ?: 'khoa';

            return app(LichHocLyThuyetThucHanhHangA1Exporter::class)->download($khoa, $cauHinh, 'lich-lt-'.$slug);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Không xuất được Excel: '.$e->getMessage());
        }
    }
}