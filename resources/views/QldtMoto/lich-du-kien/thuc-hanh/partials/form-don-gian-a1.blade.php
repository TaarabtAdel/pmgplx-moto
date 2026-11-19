@php
    $ch = $cauHinh ?? \App\Support\QldtMoto\CheDoThucHanhMoto::cauHinhMacDinh();
    $xeDaChon = (array) ($ch['xe'] ?? []);
    $phanBo = \App\Support\QldtMoto\CheDoThucHanhMoto::phanBoNgayThucHanh(
        $ch['tong_so_hv'],
        $ch['so_hv_ngay_day_du'],
        $ch['so_gio_ngay_day_du']
    );
    $ngayCuoi = $phanBo !== [] ? end($phanBo) : null;
@endphp

<div class="alert alert-info py-2 small" id="huong-dan-th-don-gian">
    Mẫu <em>5. LỊCH HỌC THỰC HÀNH</em>: mỗi HV <strong>{{ \App\Support\QldtMoto\CheDoThucHanhMoto::GIO_MOI_HV }} giờ</strong>.
    Ngày đủ <strong>{{ $ch['so_hv_ngay_day_du'] }} HV</strong> × <strong>{{ rtrim(rtrim(number_format($ch['so_gio_ngay_day_du'], 1, '.', ''), '0'), '.') }} giờ</strong>
    → <strong>{{ $ch['so_ngay'] }} ngày</strong> liên tiếp.
    @if ($ngayCuoi && count($phanBo) > 1 && $ngayCuoi['so_hv'] < $ch['so_hv_ngay_day_du'])
        Ngày cuối: <strong>{{ $ngayCuoi['so_hv'] }} HV</strong>, <strong>{{ rtrim(rtrim(number_format($ngayCuoi['so_gio'], 1, '.', ''), '0'), '.') }} giờ</strong>.
    @endif
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="th_ten_gv">Giáo viên hướng dẫn <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-sm" id="th_ten_gv" name="cau_hinh[ten_gv]" maxlength="200" required
               list="ds-gv-th-goi-y" autocomplete="off"
               value="{{ old('cau_hinh.ten_gv', $ch['ten_gv'] ?? '') }}"
               placeholder="vd MAI THỊ LỆ HUYỀN">
        <datalist id="ds-gv-th-goi-y">
            @foreach ($gvGoiY ?? [] as $ten)
                <option value="{{ $ten }}"></option>
            @endforeach
        </datalist>
    </div>
    <div class="form-group col-md-3">
        <label for="th_noi_dung">Nội dung</label>
        <input type="text" class="form-control form-control-sm" id="th_noi_dung" name="cau_hinh[noi_dung]" maxlength="255"
               value="{{ old('cau_hinh.noi_dung', $ch['noi_dung'] ?? '') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="th_dia_diem">Địa điểm luyện tập</label>
        <input type="text" class="form-control form-control-sm" id="th_dia_diem" name="cau_hinh[dia_diem]" maxlength="255"
               value="{{ old('cau_hinh.dia_diem', $ch['dia_diem'] ?? '') }}">
    </div>
    <div class="form-group col-md-2">
        <label for="th_tong_so_hv">Tổng số HV</label>
        <input type="number" class="form-control form-control-sm" id="th_tong_so_hv" name="cau_hinh[tong_so_hv]" min="1" max="9999"
               value="{{ old('cau_hinh.tong_so_hv', $ch['tong_so_hv'] ?? 236) }}">
    </div>
</div>
<div class="form-row align-items-end">
    <div class="form-group col-md-2">
        <label for="th_so_hv_ngay">HV / ngày đủ</label>
        <input type="number" class="form-control form-control-sm" id="th_so_hv_ngay" name="cau_hinh[so_hv_ngay_day_du]" min="1" max="999"
               value="{{ old('cau_hinh.so_hv_ngay_day_du', $ch['so_hv_ngay_day_du'] ?? 40) }}">
    </div>
    <div class="form-group col-md-2">
        <label for="th_so_gio">Giờ / ngày đủ</label>
        <input type="number" class="form-control form-control-sm" id="th_so_gio" name="cau_hinh[so_gio_ngay_day_du]" min="0.5" max="24" step="0.5"
               value="{{ old('cau_hinh.so_gio_ngay_day_du', $ch['so_gio_ngay_day_du'] ?? 10) }}">
    </div>
    <div class="form-group col-md-2">
        <label class="d-block">Số ngày học</label>
        <p class="form-control form-control-sm bg-light mb-0" id="th_so_ngay_tinh" aria-readonly="true">{{ $ch['so_ngay'] ?? count($phanBo) }}</p>
    </div>
    <div class="form-group col-md-3">
        <label for="th_ngay_ky">Ngày ký (footer file)</label>
        <input type="date" class="form-control form-control-sm" id="th_ngay_ky" name="cau_hinh[ngay_ky]"
               value="{{ old('cau_hinh.ngay_ky', $ch['ngay_ky'] ?? '') }}">
    </div>
    <div class="form-group col-md-5">
        <label class="d-block">Xe tập lái (cố định — mẫu A1-K67)</label>
        <p class="form-control form-control-sm bg-light mb-0 small" style="height: auto; min-height: 2.25rem;">
            {{ \App\Support\QldtMoto\CheDoThucHanhMoto::chuoiXeTap($xeDaChon) }}
        </p>
        @foreach (\App\Support\QldtMoto\CheDoThucHanhMoto::xeTapMacDinh() as $bienSo)
            <input type="hidden" name="cau_hinh[xe][]" value="{{ $bienSo }}">
        @endforeach
    </div>
</div>

@push('scripts')
<script>
(function () {
    function tinhSoNgay() {
        var tong = parseInt(document.getElementById('th_tong_so_hv').value, 10) || 0;
        var hvNgay = parseInt(document.getElementById('th_so_hv_ngay').value, 10) || 1;
        if (tong < 1) {
            document.getElementById('th_so_ngay_tinh').textContent = '0';
            return;
        }
        var du = tong % hvNgay;
        var so = Math.floor(tong / hvNgay) + (du > 0 ? 1 : 0);
        document.getElementById('th_so_ngay_tinh').textContent = String(Math.max(1, so));
    }
    ['th_tong_so_hv', 'th_so_hv_ngay'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', tinhSoNgay);
        }
    });
})();
</script>
@endpush