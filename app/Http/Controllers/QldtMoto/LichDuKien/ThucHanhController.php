<?php

namespace App\Http\Controllers\QldtMoto\LichDuKien;

use App\Http\Controllers\Controller;
use App\Models\QldtMoto\BaiDay;
use App\Models\QldtMoto\LichDuKienThucHanh;
use App\Models\QldtMoto\LichDuKienThucHanhCapGv;
use App\Models\QldtMoto\LichDuKienThucHanhNgayNghi;
use App\Models\PMGPLXMOTO\GiaoVien;
use App\Models\PMGPLXMOTO\XeTap;
use App\Support\QldtMoto\CheDoThucHanhMoto;
use App\Support\QldtMoto\LichDuKienThucHanhA1ExcelExporter;
use App\Support\QldtMoto\LichDuKienThucHanhBangLich;
use App\Support\QldtMoto\LichDuKienThucHanhPmgplxExcelExporter;
use App\Support\QldtMoto\SinhLichDuKienThucHanh;
use App\Support\QldtMoto\SinhLichDuKienThucHanhA1;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** QLĐT mô tô → Lịch dự kiến thực hành hạng A. */
class ThucHanhController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'tu_khoa' => trim((string) $request->query('tu_khoa', '')),
            'hang' => (string) $request->query('hang', ''),
        ];

        $items = LichDuKienThucHanh::query()
            ->with('nguoiTao:Id,Ten')
            ->with('ngayNghis:Id,LichDuKienId,Ngay')
            ->withCount('capGvs')
            ->when($filters['tu_khoa'] !== '', fn ($q) => $q->where('TenKhoa', 'like', '%'.$filters['tu_khoa'].'%'))
            ->when(isset(BaiDay::HANG[$filters['hang']]), fn ($q) => $q->where('Hang', $filters['hang']))
            ->orderByDesc('NgayBatDauThucHanh')
            ->orderByDesc('Id')
            ->paginate(20)
            ->withQueryString();

        return view('QldtMoto.lich-du-kien.thuc-hanh.index', [
            'items' => $items,
            'filters' => $filters,
            'hangOptions' => BaiDay::HANG,
        ]);
    }

    public function create(): View
    {
        return $this->form(new LichDuKienThucHanh(['Hang' => 'A1']));
    }

    public function store(Request $request): RedirectResponse
    {
        $lich = new LichDuKienThucHanh(['NguoiTaoId' => auth()->id(), 'NgayTao' => Carbon::now()]);
        $this->luu($request, $lich);

        return $this->sauKhiLuu($request, $lich, 'Đã tạo lịch dự kiến "'.$lich->TenKhoa.'".');
    }

    public function edit(LichDuKienThucHanh $lich): View
    {
        return $this->form($lich);
    }

    public function update(Request $request, LichDuKienThucHanh $lich): RedirectResponse
    {
        $this->luu($request, $lich);

        return $this->sauKhiLuu($request, $lich, 'Đã lưu lịch dự kiến "'.$lich->TenKhoa.'".');
    }

    public function destroy(LichDuKienThucHanh $lich): RedirectResponse
    {
        $ten = $lich->TenKhoa;
        $lich->delete();

        return redirect()
            ->route('qldtmoto.lich-du-kien.thuc-hanh.index')
            ->with('success', 'Đã xoá lịch dự kiến "'.$ten.'".');
    }

    public function exportExcel(LichDuKienThucHanh $lich): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        $bangLich = LichDuKienThucHanhBangLich::build($lich);
        if ($bangLich === null) {
            return redirect()
                ->route('qldtmoto.lich-du-kien.thuc-hanh.edit', $lich)
                ->with('error', 'Chưa có lịch đã sinh — bấm Sinh lịch trước khi xuất Excel.')
                ->withFragment('lich-da-sinh');
        }

        try {
            if (CheDoThucHanhMoto::isDonGian($lich->Hang)) {
                return app(LichDuKienThucHanhA1ExcelExporter::class)->download($lich);
            }

            return LichDuKienThucHanhPmgplxExcelExporter::download($lich, $bangLich);
        } catch (\Throwable $e) {
            return redirect()
                ->route('qldtmoto.lich-du-kien.thuc-hanh.edit', $lich)
                ->with('error', 'Không xuất được Excel: '.$e->getMessage())
                ->withFragment('lich-da-sinh');
        }
    }

    /** Nút "Sinh lịch" gửi kèm hanh_dong=sinh_lich: lưu form rồi sinh lại toàn bộ buổi. */
    private function sauKhiLuu(Request $request, LichDuKienThucHanh $lich, string $thongBao): RedirectResponse
    {
        $redirect = redirect()->route('qldtmoto.lich-du-kien.thuc-hanh.edit', $lich);
        if ($request->input('hanh_dong') !== 'sinh_lich') {
            return $redirect->with('success', $thongBao);
        }

        $lich = $lich->fresh();
        $kq = CheDoThucHanhMoto::isDonGian($lich->Hang)
            ? app(SinhLichDuKienThucHanhA1::class)->sinh($lich)
            : app(SinhLichDuKienThucHanh::class)->sinh($lich);

        $thongBao = 'Đã sinh lịch: '.$kq['so_buoi'].' buổi'
            .($kq['ngay_ket_thuc'] ? ', kết thúc '.Carbon::parse($kq['ngay_ket_thuc'])->format('d/m/Y') : '').'.';

        if (! CheDoThucHanhMoto::isDonGian($lich->Hang)) {
            $thongBao .= collect(['cabin' => 'Cabin', 'tu_dong' => 'Tự động'])
                ->filter(fn (string $nhan, string $khoi): bool => ! empty($kq['khoi'][$khoi]))
                ->map(fn (string $nhan, string $khoi): string => ' '.$nhan.': '.collect($kq['khoi'][$khoi])
                    ->map(fn (array $ngay, int $cap): string => 'Cặp '.$cap.' '.Carbon::parse($ngay[0])->format('d/m')
                        .($ngay[1] !== $ngay[0] ? '–'.Carbon::parse($ngay[1])->format('d/m') : ''))
                    ->implode(', ').'.')
                ->implode('');
        }

        return $redirect
            ->with('success', $thongBao)
            ->with('canh_bao_sinh_lich', $kq['canh_bao'])
            ->withFragment('lich-da-sinh');
    }

    private function form(LichDuKienThucHanh $lich): View
    {
        $cauHinh = CheDoThucHanhMoto::normalizeCauHinh(
            old('cau_hinh') ?? ($lich->CauHinhJson ? json_decode((string) $lich->CauHinhJson, true) : null)
        );

        $maXeDangChon = array_filter($cauHinh['xe']);
        $xeOptions = self::xeOptions($maXeDangChon);
        $gvGoiY = GiaoVien::query()
            ->where('TrangThai', 1)
            ->orderBy('TenGV')
            ->orderBy('HoTenDem')
            ->get(['HoTenDem', 'TenGV'])
            ->map(fn (GiaoVien $g): string => $g->HoTen)
            ->all();

        return view('QldtMoto.lich-du-kien.thuc-hanh.form', [
            'lich' => $lich,
            'hangOptions' => BaiDay::HANG,
            'cauHinh' => $cauHinh,
            'xeOptions' => $xeOptions,
            'gvGoiY' => $gvGoiY,
            'bangLich' => $lich->exists ? LichDuKienThucHanhBangLich::build($lich) : null,
        ]);
    }

    private function luu(Request $request, LichDuKienThucHanh $lich): void
    {
        $ngayNghi = array_values(array_filter(
            (array) $request->input('ngay_nghi', []),
            static fn ($r): bool => is_array($r) && trim((string) ($r['ngay'] ?? '')) !== ''
        ));
        $capGv = array_values(array_filter(
            (array) $request->input('cap_gv', []),
            static fn ($r): bool => is_array($r) && trim(implode('', array_map('strval', $r))) !== ''
        ));
        $request->merge(['ngay_nghi' => $ngayNghi, 'cap_gv' => $capGv]);

        $hang = (string) $request->input('hang');
        $donGian = CheDoThucHanhMoto::isDonGian($hang);
        $rules = [
            'ten_khoa' => ['required', 'string', 'max:255'],
            'hang' => ['required', Rule::in(array_keys(BaiDay::HANG))],
            'ngay_bat_dau' => ['required', 'date_format:Y-m-d'],
            'ngay_nghi' => ['array'],
            'ngay_nghi.*.ngay' => ['required', 'date_format:Y-m-d', 'distinct'],
            'ngay_nghi.*.ghi_chu' => ['nullable', 'string', 'max:255'],
        ];

        if ($donGian) {
            $rules += [
                'cau_hinh.ten_gv' => ['required', 'string', 'max:200'],
                'cau_hinh.noi_dung' => ['nullable', 'string', 'max:255'],
                'cau_hinh.dia_diem' => ['nullable', 'string', 'max:255'],
                'cau_hinh.tong_so_hv' => ['required', 'integer', 'min:1', 'max:9999'],
                'cau_hinh.so_hv_ngay_day_du' => ['nullable', 'integer', 'min:1', 'max:999'],
                'cau_hinh.so_gio_ngay_day_du' => ['nullable', 'numeric', 'min:0.5', 'max:24'],
                'cau_hinh.ngay_ky' => ['nullable', 'date_format:Y-m-d'],
                'cau_hinh.xe' => ['nullable', 'array'],
                'cau_hinh.xe.*' => ['string', 'max:20'],
            ];
        } else {
            $rules += [
                'xe_tu_dong' => ['nullable', 'string', 'max:20', Rule::exists('sqlsrv_moto.XeTap', 'BienSoXe')],
                'cabin_ngay' => ['nullable', 'date_format:Y-m-d'],
                'cap_gv' => ['array'],
                'cap_gv.*.gv_sang' => ['nullable', 'string', 'max:20', Rule::exists('sqlsrv_moto.GiaoVien', 'MaGV')],
                'cap_gv.*.gv_chieu' => ['nullable', 'string', 'max:20', Rule::exists('sqlsrv_moto.GiaoVien', 'MaGV')],
                'cap_gv.*.xe' => ['nullable', 'string', 'max:20', Rule::exists('sqlsrv_moto.XeTap', 'BienSoXe')],
            ];
        }

        $validator = validator($request->all(), $rules, [
            'ten_khoa.required' => 'Nhập Tên khoá.',
            'hang.required' => 'Chọn Hạng.',
            'hang.in' => 'Hạng không hợp lệ.',
            'ngay_bat_dau.required' => 'Nhập Ngày bắt đầu thực hành.',
            'ngay_bat_dau.date_format' => 'Ngày bắt đầu thực hành không hợp lệ.',
            'cabin_ngay.date_format' => 'Ngày cabin của cặp trước không hợp lệ.',
            'ngay_nghi.*.ngay.date_format' => 'Ngày nghỉ dòng :position không hợp lệ.',
            'ngay_nghi.*.ngay.distinct' => 'Ngày nghỉ dòng :position bị trùng.',
            'ngay_nghi.*.ghi_chu.max' => 'Ghi chú ngày nghỉ dòng :position tối đa 255 ký tự.',
            'xe_tu_dong.exists' => 'Xe tự động không có trong danh mục xe.',
            'cap_gv.*.gv_sang.exists' => 'Cặp :position: giáo viên sáng không có trong danh mục.',
            'cap_gv.*.gv_chieu.exists' => 'Cặp :position: giáo viên chiều không có trong danh mục.',
            'cap_gv.*.xe.exists' => 'Cặp :position: xe không có trong danh mục.',
            'cau_hinh.ten_gv.required' => 'Nhập giáo viên hướng dẫn.',
            'cau_hinh.tong_so_hv.required' => 'Nhập tổng số học viên.',
        ]);
        if (! $donGian) {
            $validator->after(function ($v) use ($capGv): void {
                $gvDaDung = [];
                $xeDaDung = [];
                foreach ($capGv as $i => $c) {
                    $sang = trim((string) ($c['gv_sang'] ?? ''));
                    $chieu = trim((string) ($c['gv_chieu'] ?? ''));
                    $xe = trim((string) ($c['xe'] ?? ''));
                    $cap = 'Cặp '.($i + 1);
                    if ($sang === '' && $chieu === '') {
                        $v->errors()->add("cap_gv.$i", $cap.': chọn ít nhất một giáo viên.');
                    }
                    foreach (array_filter([$sang, $chieu]) as $ma) {
                        $key = mb_strtoupper($ma);
                        if (isset($gvDaDung[$key])) {
                            $v->errors()->add("cap_gv.$i", $cap.': giáo viên '.$ma.' đã có ở '.$gvDaDung[$key].'.');
                        }
                        $gvDaDung[$key] = $cap;
                    }
                    if ($xe !== '') {
                        $key = mb_strtoupper($xe);
                        if (isset($xeDaDung[$key])) {
                            $v->errors()->add("cap_gv.$i", $cap.': xe '.$xe.' đã dùng ở '.$xeDaDung[$key].'.');
                        }
                        $xeDaDung[$key] = $cap;
                    }
                }
            });
        }
        $validator->validate();

        $cauHinhJson = null;
        if ($donGian) {
            $cauHinh = CheDoThucHanhMoto::normalizeCauHinh((array) $request->input('cau_hinh', []));
            $cauHinhJson = json_encode($cauHinh, JSON_UNESCAPED_UNICODE);
            $xeDau = $cauHinh['xe'][0] ?? '';
            $capGv = [[
                'gv_sang' => '',
                'gv_chieu' => '',
                'xe' => $xeDau,
            ]];
        }

        $now = Carbon::now();
        DB::connection('sqlsrv_manhlinh_moto')->transaction(function () use ($request, $lich, $ngayNghi, $capGv, $now, $donGian, $cauHinhJson): void {
            $lich->fill([
                'TenKhoa' => trim((string) $request->input('ten_khoa')),
                'Hang' => (string) $request->input('hang'),
                'NgayBatDauThucHanh' => $request->input('ngay_bat_dau'),
                'XeTuDong' => $donGian ? null : ($request->input('xe_tu_dong') ?: null),
                'CabinCapTruocNgay' => $donGian ? null : ($request->input('cabin_ngay') ?: null),
                'CauHinhJson' => $cauHinhJson,
                'NgayCapNhat' => $now,
            ])->save();

            LichDuKienThucHanhNgayNghi::query()->where('LichDuKienId', $lich->Id)->delete();
            $rows = collect($ngayNghi)
                ->sortBy('ngay')
                ->map(fn (array $r): array => [
                    'LichDuKienId' => $lich->Id,
                    'Ngay' => $r['ngay'],
                    'GhiChu' => trim((string) ($r['ghi_chu'] ?? '')) ?: null,
                ])
                ->values()
                ->all();
            foreach (array_chunk($rows, 200) as $chunk) {
                LichDuKienThucHanhNgayNghi::query()->insert($chunk);
            }

            LichDuKienThucHanhCapGv::query()->where('LichDuKienId', $lich->Id)->delete();
            $don = static fn ($v): ?string => trim((string) $v) ?: null;
            foreach ($capGv as $i => $c) {
                LichDuKienThucHanhCapGv::query()->create([
                    'LichDuKienId' => $lich->Id,
                    'ThuTu' => $i + 1,
                    'MaGvSang' => $don($c['gv_sang'] ?? null),
                    'MaGvChieu' => $don($c['gv_chieu'] ?? null),
                    'BienSoXe' => $don($c['xe'] ?? null),
                ]);
            }
        });
    }

    /**
     * Xe đang hoạt động (+ xe đã chọn dù đã ngừng) — sắp theo hạng rồi biển số.
     *
     * @param  list<string>  $dangChon
     * @return list<array{bien_so: string, nhan: string, hang: string}>
     */
    private static function xeOptions(array $dangChon): array
    {
        return XeTap::query()
            ->where(fn ($q) => $q->where('TrangThai', 1)->orWhereIn('BienSoXe', $dangChon ?: ['']))
            ->orderBy('HangGPLXXe')
            ->orderBy('BienSoXe')
            ->get(['BienSoXe', 'NhanHieu', 'HangGPLXXe', 'TrangThai'])
            ->map(fn (XeTap $x): array => [
                'bien_so' => trim((string) $x->BienSoXe),
                'nhan' => trim($x->BienSoXe.' — '.trim((string) $x->NhanHieu).($x->TrangThai ? '' : ' (ngừng hoạt động)')),
                'hang' => trim((string) $x->HangGPLXXe),
            ])
            ->all();
    }

    /**
     * Giáo viên đang hoạt động (+ GV đã chọn). 'xe' = xe đã gán cho GV (ô Ghi chú bên danh mục GV) nếu là biển số hợp lệ.
     *
     * @param  list<string>  $dangChon
     * @param  list<string>  $bienSoHopLe
     * @return list<array{ma: string, nhan: string, xe: string}>
     */
    private static function giaoVienOptions(array $dangChon, array $bienSoHopLe): array
    {
        $hopLe = array_flip($bienSoHopLe);

        return GiaoVien::query()
            ->where(fn ($q) => $q->where('TrangThai', 1)->orWhereIn('MaGV', $dangChon ?: ['']))
            ->orderBy('TenGV')
            ->orderBy('HoTenDem')
            ->get(['MaGV', 'HoTenDem', 'TenGV', 'GhiChu', 'TrangThai'])
            ->map(function (GiaoVien $g) use ($hopLe): array {
                $xe = trim((string) $g->GhiChu);

                return [
                    'ma' => trim((string) $g->MaGV),
                    'nhan' => $g->HoTen.' ('.trim((string) $g->MaGV).')'.($g->TrangThai ? '' : ' — ngừng'),
                    'xe' => isset($hopLe[$xe]) ? $xe : '',
                ];
            })
            ->all();
    }
}
