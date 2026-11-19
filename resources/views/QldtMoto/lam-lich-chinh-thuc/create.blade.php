@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', 'Chọn lịch dự kiến → chính thức')

@section('content')
    @include('QldtMoto.lam-lich-chinh-thuc.partials.breadcrumb', ['trangCon' => 'Chọn từ dự kiến'])

    <div class="card card-panel">
        <div class="card-header">Chọn lịch dự kiến thực hành</div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger py-2">{{ session('error') }}</div>
            @endif
            @foreach ($errors->all() as $err)
                <div class="alert alert-danger py-2">{{ $err }}</div>
            @endforeach

            <p class="small text-muted mb-2">
                Chỉ hiện lịch dự kiến <strong>đã sinh buổi</strong> tại
                <a href="{{ route('qldtmoto.lich-du-kien.thuc-hanh.index') }}">Lịch dự kiến → Thực hành</a>.
                Tạo bản chính thức để <strong>in / xuất Excel</strong> và <strong>ghi rõ khai giảng — bế giảng</strong> trên danh mục khoá PM
                (<strong class="text-danger">không</strong> ghi lịch vào <code>KhoaHoc_GiaoVien</code> / <code>KhoaHoc_XeTap</code>).
            </p>

            <div class="alert alert-secondary border small mb-3" id="kg-bg-goi-y" style="display: none;">
                <strong>Ngày khai giảng:</strong> <span id="kg-goi-y">—</span>
                &nbsp;·&nbsp;
                <strong>Ngày bế giảng:</strong> <span id="bg-goi-y">—</span>
            </div>

            @if ($duKienOptions->isEmpty())
                <div class="alert alert-warning">
                    Chưa có lịch dự kiến nào đã sinh buổi.
                    <a href="{{ route('qldtmoto.lich-du-kien.thuc-hanh.create') }}">Tạo lịch dự kiến</a>
                    rồi bấm <strong>Sinh lịch</strong> trước.
                </div>
            @else
                <form method="POST" action="{{ route('qldtmoto.lam-lich-chinh-thuc.store') }}">
                    @csrf
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-6">
                            <label for="lich_du_kien_id">Lịch dự kiến <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm chon-s2" id="lich_du_kien_id" name="lich_du_kien_id" required
                                    data-placeholder="— Chọn lịch dự kiến —">
                                <option value=""></option>
                                @foreach ($duKienOptions as $dk)
                                    <option value="{{ $dk->Id }}" @selected((string) old('lich_du_kien_id') === (string) $dk->Id)
                                            data-ten="{{ $dk->TenKhoa }}">
                                        {{ $dk->TenKhoa }}
                                        · {{ $hangOptions[$dk->Hang] ?? $dk->Hang }}
                                        · BĐ {{ $dk->NgayBatDauThucHanh?->format('d/m/Y') }}
                                        · {{ $dk->buois_count }} buổi
                                        @if ($dk->NgaySinhLich)
                                            · sinh {{ $dk->NgaySinhLich->format('d/m/Y H:i') }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ma_kh">Khoá PMGPLX-MOTO (hiển thị KG / BG)</label>
                            <select class="form-control form-control-sm sel-khoa-pmgplx" id="ma_kh" name="ma_kh"
                                    data-placeholder="— Gợi ý theo tên khoá hoặc chọn —">
                                <option value=""></option>
                                @foreach ($khoaOptions as $kh)
                                    <option value="{{ $kh->MaKH }}" @selected(old('ma_kh') === $kh->MaKH)
                                            data-kg="{{ $kh->NgayKG?->format('d/m/Y') ?? '' }}"
                                            data-bg="{{ $kh->NgayBG?->format('d/m/Y') ?? '' }}">
                                        {{ $kh->TenKH }} ({{ $kh->MaKH }})
                                        @if ($kh->NgayKG)
                                            · KG {{ $kh->NgayKG->format('d/m/Y') }}
                                        @endif
                                        @if ($kh->NgayBG)
                                            · BG {{ $kh->NgayBG->format('d/m/Y') }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-sm btn-navy btn-block">Tạo &amp; xem</button>
                        </div>
                    </div>
                </form>
            @endif

            <a href="{{ route('qldtmoto.lam-lich-chinh-thuc.index') }}" class="btn btn-sm btn-outline-secondary mt-2">← Danh sách</a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function ($) {
    function s2(sel) {
        var $el = $(sel);
        if (!$el.length) return;
        $el.select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true,
            placeholder: $el.data('placeholder') || '—'
        });
    }
    s2('#lich_du_kien_id');
    s2('#ma_kh');

    function hienKgBg() {
        var $opt = $('#ma_kh option:selected');
        var kg = $opt.data('kg') || '';
        var bg = $opt.data('bg') || '';
        var $box = $('#kg-bg-goi-y');
        if (! $opt.val()) {
            $box.hide();
            return;
        }
        $('#kg-goi-y').text(kg || '—');
        $('#bg-goi-y').text(bg || '—');
        $box.show();
    }

    $('#ma_kh').on('change', hienKgBg);
    hienKgBg();

    $('#lich_du_kien_id').on('change', function () {
        var opt = this.options[this.selectedIndex];
        var ten = opt ? (opt.getAttribute('data-ten') || '') : '';
        var $kh = $('#ma_kh');
        if (!ten || $kh.val()) return;
        var found = null;
        $kh.find('option').each(function () {
            var v = this.value;
            var t = (this.textContent || '').toUpperCase();
            if (v && (v === ten || t.indexOf(ten.toUpperCase()) !== -1)) {
                found = v;
                return false;
            }
        });
        if (found) $kh.val(found).trigger('change');
    });
})(jQuery);
</script>
@endpush