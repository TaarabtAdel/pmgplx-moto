@php
    $cardTitle = $cardTitle ?? 'Lịch dự kiến';
    $exportUrl = $exportUrl ?? ($lich->exists && $lich->getTable() === 'QldtMotoLichDuKienThucHanh'
        ? route('qldtmoto.lich-du-kien.thuc-hanh.export-excel', $lich)
        : null);
    $donGian = \App\Support\QldtMoto\CheDoThucHanhMoto::isDonGian($lich->Hang);
    $cauHinhBang = \App\Support\QldtMoto\CheDoThucHanhMoto::normalizeCauHinh(
        $lich->CauHinhJson ? json_decode((string) $lich->CauHinhJson, true) : null
    );
    $chuoiXe = \App\Support\QldtMoto\CheDoThucHanhMoto::chuoiXeTap($cauHinhBang['xe']);
    $nhanCa = \App\Models\QldtMoto\BaiDay::CA;
    $mauTheoTuKhoa = ['cabin' => 'o-cabin', 'ban đêm' => 'o-ban-dem', 'cao tốc' => 'o-cao-toc', 'có tải' => 'o-co-tai'];
@endphp
<style>
    #lich-da-sinh .bang-lich { font-size: .82rem; }
    #lich-da-sinh .bang-lich th, #lich-da-sinh .bang-lich td { vertical-align: top; }
    #lich-da-sinh .khung-cuon { overflow-x: auto; }
    #lich-da-sinh .bang-lich { width: auto; min-width: 100%; border-collapse: separate; border-spacing: 0; border-width: 1px 0 0 1px; }
    #lich-da-sinh .bang-lich th,
    #lich-da-sinh .bang-lich td { border-width: 0 1px 1px 0; }
    @if (! $donGian)
    #lich-da-sinh .bang-lich .chu-nghi { position: sticky; left: 9.75rem; display: inline-block; }
    #lich-da-sinh .bang-lich .col-ngay,
    #lich-da-sinh .bang-lich .col-thu { position: sticky; z-index: 2; background: #fff; }
    #lich-da-sinh .bang-lich .col-ngay { left: 0; white-space: nowrap; width: 6.5rem; min-width: 6.5rem; max-width: 6.5rem; }
    #lich-da-sinh .bang-lich .col-thu { left: 6.5rem; width: 2.75rem; min-width: 2.75rem; max-width: 2.75rem; text-align: center; box-shadow: inset -2px 0 0 #adb5bd; }
    #lich-da-sinh .bang-lich thead .col-ngay,
    #lich-da-sinh .bang-lich thead .col-thu { background: #e9ecef; z-index: 3; }
    @endif
    #lich-da-sinh .bang-lich .thu-cn { color: #dc3545; font-weight: 600; }
    #lich-da-sinh .bang-lich .o-buoi { min-width: 12rem; }
    #lich-da-sinh .bang-lich .o-buoi.ca-chieu { background: #fbfbf3; }
    #lich-da-sinh .bang-lich .so-bai { font-weight: 600; color: #1f3a60; margin-right: .2rem; }
    #lich-da-sinh .bang-lich .gio { color: #6c757d; font-size: .75rem; }
    #lich-da-sinh .bang-lich .o-buoi.o-cabin { background: #e3f2fd; }
    #lich-da-sinh .bang-lich .o-buoi.o-cao-toc { background: #ffe566; }
    #lich-da-sinh .bang-lich .o-buoi.o-ban-dem { background: #c77dff; }
    #lich-da-sinh .bang-lich .o-buoi.o-co-tai { background: #fde4e4; }
    #lich-da-sinh .bang-lich .o-buoi.o-tu-dong { background: #c4c9cf; }
    #lich-da-sinh .bang-lich tr.ngay-nghi td { background: #eef0f3; color: #6c757d; }
</style>

<div class="card card-panel" id="lich-da-sinh">
    <div class="card-header d-flex flex-wrap align-items-center gap-2">
        <span class="mr-auto">{{ $cardTitle }}</span>
        @if ($bangLich)
            <span class="small text-muted">
                @if ($lich->NgaySinhLich)
                    Sinh lúc {{ $lich->NgaySinhLich->format('d/m/Y H:i') }} ·
                @endif
                {{ $bangLich['so_buoi'] }} buổi · {{ count($bangLich['dong']) }} ngày
            </span>
            @if ($exportUrl)
                <a href="{{ $exportUrl }}" class="btn btn-sm btn-outline-success">
                    Xuất Excel{{ $donGian ? ' (mẫu A1-K67)' : ' (mẫu nhập lịch)' }}
                </a>
            @endif
        @endif
    </div>
    <div class="card-body">
        @foreach ((array) session('canh_bao_sinh_lich', []) as $canhBao)
            <div class="alert alert-warning py-2 mb-2">{{ $canhBao }}</div>
        @endforeach

        @if (! $bangLich)
            <p class="text-muted mb-0">
                @if ($donGian)
                    Chưa sinh lịch. Bấm <strong>Sinh lịch</strong> — {{ $cauHinhBang['tong_so_hv'] }} HV, {{ $cauHinhBang['so_ngay'] }} ngày (ngày đủ {{ $cauHinhBang['so_hv_ngay_day_du'] }} HV / {{ rtrim(rtrim(number_format($cauHinhBang['so_gio_ngay_day_du'], 1, '.', ''), '0'), '.') }} giờ)
                    — mẫu <em>5. LỊCH HỌC THỰC HÀNH</em>.
                @else
                    Chưa sinh lịch. Bấm <strong>Sinh lịch</strong> để điền lần lượt các bài của
                    Hạng {{ $hangOptions[$lich->Hang] ?? $lich->Hang }} (Sáng cho giáo viên sáng, Chiều cho giáo viên chiều)
                    vào các ngày từ Ngày bắt đầu thực hành, bỏ qua ngày nghỉ.
                @endif
            </p>
        @elseif ($donGian)
            <p class="small text-muted mb-2">
                Tổng {{ $cauHinhBang['tong_so_hv'] }} HV (mỗi HV {{ \App\Support\QldtMoto\CheDoThucHanhMoto::GIO_MOI_HV }} giờ).
                Sửa cấu hình rồi bấm <strong>Sinh lịch</strong> lại.
            </p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0 bang-lich">
                    <thead class="thead-light">
                        <tr>
                            <th>Thứ</th>
                            <th>Ngày</th>
                            <th class="text-right">Giờ</th>
                            <th>Nội dung</th>
                            <th>Địa điểm</th>
                            <th class="text-right">HV ngày</th>
                            <th>Xe tập</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bangLich['dong'] as $d)
                            @if ($d['nghi'])
                                <tr class="ngay-nghi">
                                    <td class="{{ $d['thu'] === 'CN' ? 'thu-cn' : '' }}">{{ $d['thu'] }}</td>
                                    <td colspan="6">Nghỉ{{ $d['ly_do_nghi'] !== '' ? ' — '.$d['ly_do_nghi'] : '' }}</td>
                                </tr>
                            @else
                                @php
                                    $b = $d['o']['1|sang'] ?? reset($d['o']);
                                    $noiDung = $b?->NoiDung ?: $cauHinhBang['noi_dung'];
                                    $soGio = $b?->SoGio ?? $cauHinhBang['so_gio_ngay_day_du'];
                                    $soHvNgay = $b?->SoHocVien ?? $cauHinhBang['so_hv_ngay_day_du'];
                                @endphp
                                <tr>
                                    <td class="{{ $d['thu'] === 'CN' ? 'thu-cn' : '' }}">{{ $d['thu'] }}</td>
                                    <td>{{ $d['ngay'] }}</td>
                                    <td class="text-right">{{ rtrim(rtrim(number_format((float) $soGio, 1, '.', ''), '0'), '.') }}</td>
                                    <td>{{ $noiDung }}</td>
                                    <td>{{ $cauHinhBang['dia_diem'] }}</td>
                                    <td class="text-right">{{ $soHvNgay }}</td>
                                    <td class="small">{{ $chuoiXe }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="small text-muted mb-2">
                Lịch chụp lại bài dạy lúc sinh. Sửa thông tin, ngày nghỉ, cặp giáo viên hoặc cấu hình bài dạy thì bấm <strong>Sinh lịch</strong> lại.
                Ô xanh nhạt là Cabin: cặp đầu sau "Cabin của cặp trước" ít nhất 1 ngày, mỗi cặp sau cách cặp trước 1 ngày. Màu: tím = Ban đêm, đỏ nhạt = Có tải, vàng = Cao tốc, xám đậm = Tự động.
            </p>
            <div class="khung-cuon">
                <table class="table table-sm table-bordered mb-0 bang-lich">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" class="col-ngay">Ngày</th>
                            <th rowspan="2" class="col-thu">Thứ</th>
                            @foreach (collect($bangLich['cot'])->groupBy('cap') as $cap => $cots)
                                <th colspan="{{ count($cots) }}" class="text-center">
                                    Cặp {{ $cap }}@if ($cots->first()['xe']) <span class="font-weight-normal text-muted">· xe {{ $cots->first()['xe'] }}</span>@endif
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($bangLich['cot'] as $c)
                                <th class="o-buoi {{ $c['ca'] === 'chieu' ? 'ca-chieu' : '' }}">
                                    {{ $nhanCa[$c['ca']] }}:
                                    <span class="font-weight-normal">{{ $c['ten_gv'] ?: '—' }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bangLich['dong'] as $d)
                            <tr class="{{ $d['nghi'] ? 'ngay-nghi' : '' }}">
                                <td class="col-ngay">{{ $d['ngay'] }}</td>
                                <td class="col-thu {{ $d['thu'] === 'CN' ? 'thu-cn' : '' }}">{{ $d['thu'] }}</td>
                                @if ($d['nghi'])
                                    <td colspan="{{ count($bangLich['cot']) }}"><span class="chu-nghi">Nghỉ{{ $d['ly_do_nghi'] !== '' ? ' — '.$d['ly_do_nghi'] : '' }}</span></td>
                                @else
                                    @foreach ($bangLich['cot'] as $c)
                                        @php
                                            $b = $d['o'][$c['key']] ?? null;
                                            $tenBai = $b ? mb_strtolower($b->NoiDung) : '';
                                            $mauO = str_starts_with($tenBai, 'tự động')
                                                ? 'o-tu-dong'
                                                : (collect($mauTheoTuKhoa)->first(fn ($cls, $tu) => $tenBai !== '' && str_contains($tenBai, $tu)) ?? '');
                                        @endphp
                                        <td class="o-buoi {{ $c['ca'] === 'chieu' ? 'ca-chieu' : '' }} {{ $mauO }}">
                                            @if ($b)
                                                <span class="so-bai" title="Bài thứ {{ $b->NgaySo }}">{{ $b->NgaySo }}.</span>{{ $b->NoiDung }}
                                                @if ($b->GioBatDau || $b->GioKetThuc || $b->SoGio)
                                                    <div class="gio">
                                                        {{ $b->GioBatDau ?: '—' }} → {{ $b->GioKetThuc ?: '—' }}@if ($b->SoGio) · {{ rtrim(rtrim(number_format($b->SoGio, 2, '.', ''), '0'), '.') }}h @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>