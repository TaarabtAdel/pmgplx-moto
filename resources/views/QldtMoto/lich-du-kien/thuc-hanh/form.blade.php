@extends('PMGPLXMOTO.layouts.quan-ly')

@php
    $dangSua = $lich->exists;
    $tieuDe = $dangSua ? 'Lịch dự kiến: '.$lich->TenKhoa : 'Tạo lịch dự kiến thực hành';
@endphp

@section('title', $tieuDe)

@section('content')
    @include('QldtMoto.lich-du-kien.thuc-hanh.partials.breadcrumb', ['trangCon' => $dangSua ? $lich->TenKhoa : 'Tạo mới'])

    <form method="POST" id="form-lich-du-kien" autocomplete="off"
          action="{{ $dangSua ? route('qldtmoto.lich-du-kien.thuc-hanh.update', $lich) : route('qldtmoto.lich-du-kien.thuc-hanh.store') }}">
        @csrf
        @if ($dangSua)
            @method('PUT')
        @endif

        <div class="card card-panel">
            <div class="card-header">{{ $tieuDe }}</div>
            <div class="card-body">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="ten_khoa">Tên khoá <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="ten_khoa" name="ten_khoa" maxlength="255" required
                               value="{{ old('ten_khoa', $lich->TenKhoa) }}" placeholder="vd K26A1K67">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="hang">Hạng <span class="text-danger">*</span></label>
                        <select class="form-control form-control-sm" id="hang" name="hang" required>
                            @foreach ($hangOptions as $ma => $nhan)
                                <option value="{{ $ma }}" @selected(old('hang', $lich->Hang ?: 'A1') === $ma)>{{ $nhan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="ngay_bat_dau">Ngày bắt đầu thực hành <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="ngay_bat_dau" name="ngay_bat_dau" required
                               value="{{ old('ngay_bat_dau', $lich->NgayBatDauThucHanh?->format('Y-m-d')) }}">
                    </div>
                </div>

                @include('QldtMoto.lich-du-kien.thuc-hanh.partials.form-don-gian-a1')

                <p class="small text-muted mb-3">
                    <strong>Sinh lịch</strong>: chia <em>tổng HV</em> theo ngày đủ (vd 40 HV × 10 giờ); ngày cuối còn lại (vd 36 HV × 9 giờ). Mỗi HV 4 giờ thực hành.
                </p>

                <div class="d-flex align-items-center">
                    <a href="{{ route('qldtmoto.lich-du-kien.thuc-hanh.index') }}" class="btn btn-sm btn-outline-secondary mr-auto">← Danh sách</a>
                    @if ($dangSua)
                        <button type="button" class="btn btn-sm btn-outline-danger mr-2" id="btn-xoa-lich">Xoá lịch</button>
                    @endif
                    <button type="submit" class="btn btn-success mr-2" name="hanh_dong" value="sinh_lich"
                            title="Lưu rồi sinh các ngày học liên tiếp theo số ngày đã chọn">Sinh lịch</button>
                    <button type="submit" class="btn btn-navy">{{ $dangSua ? 'Lưu' : 'Tạo lịch dự kiến' }}</button>
                </div>
            </div>
        </div>
    </form>

    @if ($dangSua)
        <form method="POST" action="{{ route('qldtmoto.lich-du-kien.thuc-hanh.destroy', $lich) }}" id="form-xoa-lich" class="d-none">
            @csrf
            @method('DELETE')
        </form>

        @include('QldtMoto.lich-du-kien.thuc-hanh.partials.bang-lich')
    @endif
@endsection

@push('scripts')
<script>
    (function () {
        var btnXoa = document.getElementById('btn-xoa-lich');
        if (btnXoa) {
            btnXoa.addEventListener('click', function () {
                if (confirm('Xoá lịch dự kiến này?')) {
                    document.getElementById('form-xoa-lich').submit();
                }
            });
        }
    })();
</script>
@endpush