@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', $item['title'])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white border py-2 small mb-3">
            <li class="breadcrumb-item"><a href="{{ route('qldtmoto.index') }}">QLĐT XE MÁY</a></li>
            @foreach (array_slice($duongDan, 1) as $buoc)
                <li class="breadcrumb-item">{{ $buoc }}</li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
        </ol>
    </nav>

    <div class="card card-panel">
        <div class="card-header">{{ $item['title'] }}</div>
        <div class="card-body">
            <div class="alert alert-info small py-2">
                <strong>{{ \App\Support\QldtMoto\LapLuuHoSoSupport::nhanLoai($loai) }}</strong> —
                @if ($loai === 'ql')
                    một file mẫu <code>ho-so-quan-ly-dao-tao.goc.docx</code>
                @else
                    một file mẫu <code>ho-so-dao-tao.goc.docx</code>
                @endif
                (QĐ lập, thống kê B2, QĐ lưu, B6, bìa B10).
                Menu <strong>{{ $mau === 'qd_luu' ? 'lưu hồ sơ' : 'lập hồ sơ' }}</strong> chỉ phân theo giai đoạn công việc;
                mỗi lần xuất vẫn là <strong>cùng một bộ Word</strong>, điền đủ thông tin hai giai đoạn trên form.
            </div>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @php
                $routePrefix = 'qldtmoto.'.\App\Support\MenuQldt::tenRouteTrang($slug);
            @endphp

            <form method="POST" action="{{ route($routePrefix.'.export') }}" id="form-lap-luu-ho-so">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ma_kh">Khoá (PMGPLX-MOTO)</label>
                        <select class="form-control form-control-sm" id="ma_kh" name="ma_kh">
                            <option value="">— Không chọn —</option>
                            @foreach ($khoaOptions as $kh)
                                <option value="{{ $kh->MaKH }}" @selected(old('ma_kh', $form['ma_kh']) === $kh->MaKH)>
                                    {{ $kh->TenKH ?: $kh->MaKH }} ({{ $kh->MaKH }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ten_khoa">Tên khoá (trên hồ sơ)</label>
                        <input type="text" class="form-control form-control-sm" id="ten_khoa" name="ten_khoa"
                               value="{{ old('ten_khoa', $form['ten_khoa']) }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ngay_quyet_dinh">Ngày quyết định</label>
                        <input type="date" class="form-control form-control-sm" id="ngay_quyet_dinh" name="ngay_quyet_dinh"
                               value="{{ old('ngay_quyet_dinh', $form['ngay_quyet_dinh']) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="so_qd_lap">Số QĐ lập hồ sơ</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="so_qd_lap" name="so_qd_lap"
                                   value="{{ old('so_qd_lap', $form['so_qd_lap']) }}" required>
                            <div class="input-group-append"><span class="input-group-text">/QĐ-TTML</span></div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="so_qd_luu">Số QĐ lưu hồ sơ</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="so_qd_luu" name="so_qd_luu"
                                   value="{{ old('so_qd_luu', $form['so_qd_luu']) }}" required>
                            <div class="input-group-append"><span class="input-group-text">/QĐ-TTML</span></div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="so_ho_so">Số hồ sơ (đăng ký)</label>
                        <input type="text" class="form-control form-control-sm" id="so_ho_so" name="so_ho_so"
                               value="{{ old('so_ho_so', $form['so_ho_so']) }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="so_luu_tru">Số lưu trữ</label>
                        <input type="text" class="form-control form-control-sm" id="so_luu_tru" name="so_luu_tru"
                               value="{{ old('so_luu_tru', $form['so_luu_tru']) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="giao_vien">Giáo viên / NV được giao</label>
                        <input type="text" class="form-control form-control-sm" id="giao_vien" name="giao_vien"
                               value="{{ old('giao_vien', $form['giao_vien']) }}" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="chuc_vu">Chức vụ</label>
                        <input type="text" class="form-control form-control-sm" id="chuc_vu" name="chuc_vu"
                               value="{{ old('chuc_vu', $form['chuc_vu']) }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="phong_to">Phòng / Tổ</label>
                        <input type="text" class="form-control form-control-sm" id="phong_to" name="phong_to"
                               value="{{ old('phong_to', $form['phong_to']) }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="lanh_dao">Lãnh đạo ký QĐ</label>
                        <input type="text" class="form-control form-control-sm" id="lanh_dao" name="lanh_dao"
                               value="{{ old('lanh_dao', $form['lanh_dao']) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="ngay_lap">Ngày lập</label>
                        <input type="date" class="form-control form-control-sm" id="ngay_lap" name="ngay_lap"
                               value="{{ old('ngay_lap', $form['ngay_lap']) }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ngay_ket_thuc">Ngày kết thúc</label>
                        <input type="date" class="form-control form-control-sm" id="ngay_ket_thuc" name="ngay_ket_thuc"
                               value="{{ old('ngay_ket_thuc', $form['ngay_ket_thuc']) }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ngay_nop_luu">Ngày nộp lưu</label>
                        <input type="date" class="form-control form-control-sm" id="ngay_nop_luu" name="ngay_nop_luu"
                               value="{{ old('ngay_nop_luu', $form['ngay_nop_luu']) }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tong_so">Tổng số tờ / cuốn</label>
                        <input type="text" class="form-control form-control-sm" id="tong_so" name="tong_so"
                               value="{{ old('tong_so', $form['tong_so']) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tinh_trang_ho_so">Tình trạng hồ sơ</label>
                    <input type="text" class="form-control form-control-sm" id="tinh_trang_ho_so" name="tinh_trang_ho_so"
                           value="{{ old('tinh_trang_ho_so', $form['tinh_trang_ho_so']) }}" required>
                </div>

                <div class="form-group">
                    <label for="thoi_han_bao_quan">Thời hạn bảo quản (mục B6)</label>
                    <textarea class="form-control form-control-sm" id="thoi_han_bao_quan" name="thoi_han_bao_quan" rows="3">{{ old('thoi_han_bao_quan', $form['thoi_han_bao_quan']) }}</textarea>
                </div>

                <button type="submit" class="btn btn-navy">Xuất Word</button>
            </form>
        </div>
    </div>

@push('scripts')
<script>
(function () {
    var $maKh = $('#ma_kh');
    if (!$maKh.length) return;
    $maKh.on('change', function () {
        var ma = $(this).val();
        if (!ma) return;
        var url = @json(route($routePrefix.'.khoa', ['maKh' => '__MA__'])).replace('__MA__', encodeURIComponent(ma));
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (d) {
                if (!d) return;
                Object.keys(d).forEach(function (k) {
                    var el = document.getElementById(k);
                    if (el && d[k] != null) el.value = d[k];
                });
            });
    });
})();
</script>
@endpush
@endsection