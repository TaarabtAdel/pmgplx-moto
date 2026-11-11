<?php

namespace App\Http\Controllers\PMGPLXMOTO;

use App\Http\Controllers\Controller;
use App\Models\PMGPLXMOTO\KhoaHoc as KhoaHocMoi;
use App\Services\PMGPLXMOTO\KetQuaDaoTaoBanCuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KetQuaDaoTaoBanCuController extends Controller
{
    public function capNhatForm(Request $request, KetQuaDaoTaoBanCuService $service): View
    {
        $maKhoaHoc = trim((string) $request->input('ma_khoa_hoc', ''));
        $maKhNguon = trim((string) $request->input('ma_kh_nguon', ''));

        $data = $maKhoaHoc !== '' ? $service->studentsForUpdate($maKhoaHoc) : ['rows' => [], 'meta' => ['ma_khoa_hoc' => '', 'ten_khoa_hoc' => null]];

        $khoaMoi = KhoaHocMoi::query()
            ->orderBy('TenKH')
            ->get(['MaKH', 'TenKH']);

        return view('PMGPLXMOTO.danh-muc.cap-nhat-ket-qua-dao-tao-ban-cu', [
            'rows' => $data['rows'],
            'meta' => $data['meta'],
            'maKhoaHoc' => $maKhoaHoc,
            'maKhNguon' => $maKhNguon,
            'khoaMoi' => $khoaMoi,
        ]);
    }

    public function capNhatStore(Request $request, KetQuaDaoTaoBanCuService $service): RedirectResponse
    {
        $validated = $request->validate([
            'ma_khoa_hoc' => ['required', 'string'],
            'ma_dk' => ['nullable', 'array'],
            'ma_dk.*' => ['string'],
            'tg_bat_dau' => ['nullable', 'date'],
            'tg_ket_thuc' => ['nullable', 'date'],
            'ket_luan' => ['nullable', 'in:0,1'],
        ], [
            'ma_khoa_hoc.required' => 'Nhập mã khóa học trên bản cũ.',
        ]);

        $maDks = $validated['ma_dk'] ?? [];
        $ketLuan = array_key_exists('ket_luan', $validated) && $validated['ket_luan'] !== null
            ? (int) $validated['ket_luan']
            : null;

        try {
            $result = $service->updateSelected(
                (string) $validated['ma_khoa_hoc'],
                is_array($maDks) ? $maDks : [],
                $validated['tg_bat_dau'] ?? null,
                $validated['tg_ket_thuc'] ?? null,
                $ketLuan
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $msg = 'Đã cập nhật '.$result['updated'].' học viên trên bản cũ.';
        if ($result['skipped'] > 0) {
            $msg .= ' Bỏ qua '.$result['skipped'].' mã (không khớp khóa học).';
        }

        return redirect()
            ->route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.form', [
                'ma_khoa_hoc' => $validated['ma_khoa_hoc'],
                'ma_kh_nguon' => $request->input('ma_kh_nguon'),
            ])
            ->with('success', $msg);
    }

    public function copyFromNew(Request $request, KetQuaDaoTaoBanCuService $service): RedirectResponse
    {
        $validated = $request->validate([
            'ma_khoa_hoc' => ['required', 'string'],
            'ma_kh_nguon' => ['required', 'string'],
        ], [
            'ma_kh_nguon.required' => 'Chọn khóa nguồn (bản mới) để lấy KQĐT.',
        ]);

        try {
            $result = $service->copyKetQuaFromNewCourse(
                (string) $validated['ma_kh_nguon'],
                (string) $validated['ma_khoa_hoc'],
                null
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.form', [
                'ma_khoa_hoc' => $validated['ma_khoa_hoc'],
                'ma_kh_nguon' => $validated['ma_kh_nguon'],
            ])
            ->with('success', 'Đã copy KQĐT từ bản mới: cập nhật '.$result['updated'].'/'.$result['total'].' học viên.');
    }

    public function danhSachForm(Request $request, KetQuaDaoTaoBanCuService $service): View
    {
        $maKhoaHoc = trim((string) $request->input('ma_khoa_hoc', ''));
        $ketQua = trim((string) $request->input('ket_qua', ''));

        $data = $maKhoaHoc !== ''
            ? $service->listByCourse($maKhoaHoc, $ketQua !== '' ? $ketQua : null)
            : ['rows' => [], 'meta' => ['ma_khoa_hoc' => '', 'ten_khoa_hoc' => null, 'tong' => 0, 'dat' => 0, 'khong_dat' => 0]];

        return view('PMGPLXMOTO.danh-muc.ket-qua-khoa-hoc-ban-cu', [
            'rows' => $data['rows'],
            'meta' => $data['meta'],
            'maKhoaHoc' => $maKhoaHoc,
            'ketQua' => $ketQua,
        ]);
    }
}
