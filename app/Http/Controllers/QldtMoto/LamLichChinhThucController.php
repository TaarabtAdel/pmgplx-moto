<?php

namespace App\Http\Controllers\QldtMoto;

use App\Http\Controllers\Controller;
use App\Models\PMGPLXMOTO\KhoaHoc;
use App\Models\QldtMoto\BaiDay;
use App\Models\QldtMoto\LichChinhThuc;
use App\Models\QldtMoto\LichDuKienThucHanh;
use App\Support\MenuQldt;
use App\Support\QldtMoto\LichChinhThucLuuPmgplx;
use App\Support\QldtMoto\LichChinhThucSapXepBai;
use App\Support\QldtMoto\LichChinhThucTuDuKien;
use App\Support\QldtMoto\LichDuKienThucHanhBangLich;
use App\Support\QldtMoto\LichDuKienThucHanhPmgplxExcelExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** QLĐT Ô TÔ → Làm lịch chính thức: chọn từ dự kiến → xem / kéo thứ tự bài → xuất Excel → lưu PMGPLX. */
class LamLichChinhThucController extends Controller
{
    private const SLUG = '1-lam-lich-chinh-thuc';

    public function index(Request $request): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        $filters = [
            'tu_khoa' => trim((string) $request->query('tu_khoa', '')),
            'hang' => (string) $request->query('hang', ''),
        ];

        $items = LichChinhThuc::query()
            ->with('nguoiTao:Id,Ten')
            ->withCount('buois')
            ->when($filters['tu_khoa'] !== '', fn ($q) => $q->where('TenKhoa', 'like', '%'.$filters['tu_khoa'].'%'))
            ->when(isset(BaiDay::HANG[$filters['hang']]), fn ($q) => $q->where('Hang', $filters['hang']))
            ->orderByDesc('NgayCapNhat')
            ->orderByDesc('Id')
            ->paginate(20)
            ->withQueryString();

        return view('QldtMoto.lam-lich-chinh-thuc.index', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
            'items' => $items,
            'filters' => $filters,
            'hangOptions' => BaiDay::HANG,
        ]);
    }

    public function create(): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        $duKienOptions = LichDuKienThucHanh::query()
            ->whereHas('buois')
            ->withCount('buois')
            ->orderByDesc('NgayCapNhat')
            ->orderByDesc('Id')
            ->limit(300)
            ->get(['Id', 'TenKhoa', 'Hang', 'NgayBatDauThucHanh', 'NgaySinhLich']);

        $khoaOptions = KhoaHoc::query()
            ->select('MaKH', 'TenKH', 'NgayKG')
            ->orderByDesc('NgayKG')
            ->orderByDesc('MaKH')
            ->limit(500)
            ->get();

        return view('QldtMoto.lam-lich-chinh-thuc.create', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
            'duKienOptions' => $duKienOptions,
            'khoaOptions' => $khoaOptions,
            'hangOptions' => BaiDay::HANG,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lich_du_kien_id' => ['required', 'integer'],
            'ma_kh' => ['nullable', 'string', 'max:50'],
        ], [
            'lich_du_kien_id.required' => 'Chọn lịch dự kiến thực hành.',
        ]);

        $duKien = LichDuKienThucHanh::query()->find((int) $validated['lich_du_kien_id']);
        if ($duKien === null) {
            return back()->withInput()->with('error', 'Không tìm thấy lịch dự kiến.');
        }

        $lich = LichChinhThucTuDuKien::tao(
            $duKien,
            $validated['ma_kh'] ?? null,
            auth()->id()
        );

        return redirect()
            ->route('qldtmoto.lam-lich-chinh-thuc.show', $lich)
            ->with('success', 'Đã tạo lịch chính thức từ dự kiến «'.$duKien->TenKhoa.'». Kéo lại thứ tự bài nếu cần, rồi lưu / xuất / lưu vào phần mềm.');
    }

    public function show(LichChinhThuc $lich): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        $lich->load(['ngayNghis', 'capGvs', 'lichDuKien:Id,TenKhoa']);
        $bangLich = LichDuKienThucHanhBangLich::build($lich);

        $khoaOptions = KhoaHoc::query()
            ->select('MaKH', 'TenKH', 'NgayKG')
            ->orderByDesc('NgayKG')
            ->orderByDesc('MaKH')
            ->limit(500)
            ->get();

        return view('QldtMoto.lam-lich-chinh-thuc.show', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
            'lich' => $lich,
            'bangLich' => $bangLich,
            'hangOptions' => BaiDay::HANG,
            'khoaOptions' => $khoaOptions,
        ]);
    }

    public function updateThuTu(Request $request, LichChinhThuc $lich): RedirectResponse
    {
        $thuTu = (array) $request->input('thu_tu', []);
        LichChinhThucSapXepBai::apDung($lich, $thuTu);

        return redirect()
            ->route('qldtmoto.lam-lich-chinh-thuc.show', $lich)
            ->with('success', 'Đã lưu thứ tự bài.')
            ->withFragment('lich-chinh-thuc');
    }

    public function updateMaKh(Request $request, LichChinhThuc $lich): RedirectResponse
    {
        $validated = $request->validate([
            'ma_kh' => ['nullable', 'string', 'max:50'],
        ]);
        $maKh = trim((string) ($validated['ma_kh'] ?? ''));
        if ($maKh !== '' && ! KhoaHoc::query()->where('MaKH', $maKh)->exists()) {
            return back()->withInput()->with('error', 'Mã khoá PMGPLX không tồn tại.');
        }

        $lich->forceFill([
            'MaKH' => $maKh !== '' ? $maKh : null,
            'NgayCapNhat' => now(),
        ])->save();

        return redirect()
            ->route('qldtmoto.lam-lich-chinh-thuc.show', $lich)
            ->with('success', 'Đã cập nhật mã khoá PMGPLX.');
    }

    public function exportExcel(LichChinhThuc $lich): StreamedResponse|RedirectResponse
    {
        $bangLich = LichDuKienThucHanhBangLich::build($lich);
        if ($bangLich === null) {
            return redirect()
                ->route('qldtmoto.lam-lich-chinh-thuc.show', $lich)
                ->with('error', 'Lịch chưa có buổi để xuất Excel.');
        }

        try {
            return LichDuKienThucHanhPmgplxExcelExporter::download($lich, $bangLich, 'lich-chinh-thuc-th');
        } catch (\Throwable $e) {
            return redirect()
                ->route('qldtmoto.lam-lich-chinh-thuc.show', $lich)
                ->with('error', 'Không xuất được Excel: '.$e->getMessage());
        }
    }

    public function luuVaoPhanMem(Request $request, LichChinhThuc $lich): RedirectResponse
    {
        $maKh = trim((string) $request->input('ma_kh', $lich->MaKH ?? ''));
        $kq = LichChinhThucLuuPmgplx::luu($lich, $maKh !== '' ? $maKh : null);

        return redirect()
            ->route('qldtmoto.lam-lich-chinh-thuc.show', $lich)
            ->with(
                'success',
                'Đã lưu vào phần mềm (khoá '.$kq['ma_kh'].'): '
                .$kq['saved_gv'].' buổi GV, '.$kq['saved_xe'].' buổi xe'
                .($kq['wiped_gv'] || $kq['wiped_xe']
                    ? ' — đã xoá lịch TH cũ: '.$kq['wiped_gv'].' GV / '.$kq['wiped_xe'].' xe'
                    : '').'.'
            );
    }

    public function destroy(LichChinhThuc $lich): RedirectResponse
    {
        $ten = $lich->TenKhoa;
        $lich->delete();

        return redirect()
            ->route('qldtmoto.lam-lich-chinh-thuc.index')
            ->with('success', 'Đã xoá lịch chính thức «'.$ten.'».');
    }
}
