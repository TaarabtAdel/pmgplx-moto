@php
    $ch = $cauHinh;
@endphp

<div class="form-row mb-3">
    <div class="form-group col-md-2">
        <label for="lt_lop">Lớp <span class="text-danger">*</span></label>
        <select class="form-control form-control-sm" id="lt_lop" name="cau_hinh[lop]" required>
            @foreach ($lopOptions as $ma => $lop)
                <option value="{{ $ma }}" @selected($ch['lop'] === $ma)>{{ $lop['nhan'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-2">
        <label for="lt_so_hv">Số HV</label>
        <input type="number" class="form-control form-control-sm" id="lt_so_hv" name="cau_hinh[so_hoc_vien]" min="1" max="999"
               value="{{ old('cau_hinh.so_hoc_vien', $ch['so_hoc_vien']) }}">
    </div>
    <div class="form-group col-md-2">
        <label for="lt_ngay_hoc">Ngày học LT <span class="text-danger">*</span></label>
        <input type="date" class="form-control form-control-sm" id="lt_ngay_hoc" name="cau_hinh[ngay_hoc]" required
               value="{{ old('cau_hinh.ngay_hoc', $ch['ngay_hoc'] ?? '') }}">
    </div>
    <div class="form-group col-md-2">
        <label for="lt_ngay_ky">Ngày ký (footer)</label>
        <input type="date" class="form-control form-control-sm" id="lt_ngay_ky" name="cau_hinh[ngay_ky]"
               value="{{ old('cau_hinh.ngay_ky', $ch['ngay_ky'] ?? '') }}">
    </div>
</div>

<h6 class="small font-weight-bold mb-2">Xem trước tiêu đề file</h6>
<p class="small text-center border rounded py-2 mb-3 bg-light">{{ $previewTieuDe ?: '…' }}</p>

<div class="table-responsive mb-3">
    <table class="table table-sm table-bordered mb-0" style="font-size: 0.8125rem;">
        <thead class="thead-light">
            <tr class="text-center">
                <th>Môn học</th>
                <th style="width: 5rem">Tổng giờ</th>
                <th style="width: 5rem">Số giờ</th>
                <th>Địa điểm</th>
                <th style="min-width: 10rem">Giáo viên</th>
                <th style="width: 8rem">Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ch['buoi'] as $i => $buoi)
                <tr>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="cau_hinh[buoi][{{ $i }}][mon]" maxlength="255" required
                               value="{{ old('cau_hinh.buoi.'.$i.'.mon', $buoi['mon']) }}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="cau_hinh[buoi][{{ $i }}][tong_gio]" step="0.5" min="0.5" max="24" required
                               value="{{ old('cau_hinh.buoi.'.$i.'.tong_gio', $buoi['tong_gio']) }}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="cau_hinh[buoi][{{ $i }}][so_gio]" step="0.5" min="0.5" max="24" required
                               value="{{ old('cau_hinh.buoi.'.$i.'.so_gio', $buoi['so_gio']) }}">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="cau_hinh[buoi][{{ $i }}][dia_diem]" maxlength="255" required
                               value="{{ old('cau_hinh.buoi.'.$i.'.dia_diem', $buoi['dia_diem']) }}">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="cau_hinh[buoi][{{ $i }}][gv]" maxlength="200" list="ds-gv-lt"
                               value="{{ old('cau_hinh.buoi.'.$i.'.gv', $buoi['gv']) }}" placeholder="Tên GV">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="cau_hinh[buoi][{{ $i }}][ghi_chu]" maxlength="255"
                               value="{{ old('cau_hinh.buoi.'.$i.'.ghi_chu', $buoi['ghi_chu']) }}">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<datalist id="ds-gv-lt">
    @foreach ($gvOptions as $gv)
        <option value="{{ $gv['ten'] }}">{{ $gv['ma'] }}</option>
    @endforeach
</datalist>

<p class="small text-muted mb-0">
    Mỗi lớp xuất một file (lớp 01, 02, 03). Đổi <strong>Lớp</strong> để điền phòng học mặc định theo mẫu A1-K67.
</p>

@push('scripts')
<script>
(function () {
    var presets = @json($lopOptions);
    var sel = document.getElementById('lt_lop');
    if (!sel) return;
    sel.addEventListener('change', function () {
        var p = presets[sel.value];
        if (!p) return;
        var diems = document.querySelectorAll('[name="cau_hinh[buoi][0][dia_diem]"], [name="cau_hinh[buoi][1][dia_diem]"]');
        if (diems[0]) diems[0].value = p.phong_lt;
        if (diems[1]) diems[1].value = p.phong_kt;
        var gc = document.querySelector('[name="cau_hinh[buoi][1][ghi_chu]"]');
        if (gc) gc.value = p.ghi_chu_kt || '';
    });
})();
</script>
@endpush