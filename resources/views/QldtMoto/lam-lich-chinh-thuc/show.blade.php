@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', 'Lịch chính thức: '.$lich->TenKhoa)

@section('content')
    @include('QldtMoto.lam-lich-chinh-thuc.partials.breadcrumb', ['trangCon' => $lich->TenKhoa])

    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif
    @foreach ($errors->all() as $err)
        <div class="alert alert-danger py-2" style="white-space: pre-line;">{{ $err }}</div>
    @endforeach

    <div class="card card-panel mb-3">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <span class="mr-auto">{{ $lich->TenKhoa }} · {{ $hangOptions[$lich->Hang] ?? $lich->Hang }}</span>
            <a href="{{ route('qldtmoto.lam-lich-chinh-thuc.index') }}" class="btn btn-sm btn-outline-secondary">← Danh sách</a>
            @if ($lich->LichDuKienId)
                <a href="{{ route('qldtmoto.lich-du-kien.thuc-hanh.edit', $lich->LichDuKienId) }}" class="btn btn-sm btn-outline-secondary">Nguồn dự kiến</a>
            @endif
        </div>
        <div class="card-body">
            <div class="form-row small text-muted mb-3">
                <div class="col-md-4">BĐ thực hành: <strong class="text-dark">{{ $lich->NgayBatDauThucHanh?->format('d/m/Y') }}</strong></div>
            </div>

            @include('QldtMoto.lam-lich-chinh-thuc.partials.thong-tin-khoa', ['khoa' => $khoa])

            <form method="POST" action="{{ route('qldtmoto.lam-lich-chinh-thuc.update-ma-kh', $lich) }}" class="form-row align-items-end mb-0">
                @csrf
                @method('PUT')
                <div class="form-group col-md-5 mb-0">
                    <label for="ma_kh">Liên kết khoá PMGPLX-MOTO (chỉ đọc KG / BG)</label>
                    <select class="form-control form-control-sm sel-khoa-pmgplx" id="ma_kh" name="ma_kh" data-placeholder="— Chọn khoá —">
                        <option value=""></option>
                        @foreach ($khoaOptions as $kh)
                            <option value="{{ $kh->MaKH }}" @selected(old('ma_kh', $lich->MaKH) === $kh->MaKH)
                                    data-kg="{{ $kh->NgayKG?->format('d/m/Y') }}"
                                    data-bg="{{ $kh->NgayBG?->format('d/m/Y') }}">
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
                <div class="form-group col-md-2 mb-0">
                    <button type="submit" class="btn btn-sm btn-outline-primary btn-block">Lưu liên kết</button>
                </div>
            </form>
        </div>
    </div>

    @if ($donGian)
        @include('QldtMoto.lich-du-kien.thuc-hanh.partials.bang-lich', [
            'lich' => $lich,
            'bangLich' => $bangLich,
            'hangOptions' => $hangOptions,
            'cardTitle' => 'Lịch chính thức — in / Excel',
            'exportUrl' => route('qldtmoto.lam-lich-chinh-thuc.export-excel', $lich),
        ])
    @else
        @include('QldtMoto.lam-lich-chinh-thuc.partials.bang-lich', [
            'lich' => $lich,
            'bangLich' => $bangLich,
            'coKeoThuTu' => true,
        ])
        <div class="card card-panel mt-3">
            <div class="card-header">Xuất Excel</div>
            <div class="card-body">
                <a href="{{ route('qldtmoto.lam-lich-chinh-thuc.export-excel', $lich) }}" class="btn btn-sm btn-outline-success">
                    Xuất Excel (mẫu nhập lịch)
                </a>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
@if (! $donGian)
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
(function ($) {
    var $kh = $('#ma_kh');
    if ($kh.length) {
        $kh.select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true,
            placeholder: $kh.data('placeholder') || '—'
        });
    }

    var holder = document.getElementById('thu-tu-bai-an');
    if (!holder || !window.Sortable) return;

    function capNhatInputAn() {
        holder.innerHTML = '';
        var theoCot = {};
        document.querySelectorAll('#lich-chinh-thuc tbody tr').forEach(function (tr) {
            if (tr.querySelector('.chu-nghi')) return;
            tr.querySelectorAll('.o-buoi-slot').forEach(function (ul) {
                var cot = ul.getAttribute('data-cot');
                var li = ul.querySelector('.o-buoi-keo');
                if (!cot || !li) return;
                if (!theoCot[cot]) theoCot[cot] = [];
                var id = li.getAttribute('data-id');
                if (id) theoCot[cot].push(id);
            });
        });
        Object.keys(theoCot).forEach(function (cot) {
            theoCot[cot].forEach(function (id) {
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'thu_tu[' + cot + '][]';
                inp.value = id;
                holder.appendChild(inp);
            });
        });
    }

    document.querySelectorAll('#lich-chinh-thuc .o-buoi-slot').forEach(function (ul) {
        var cot = ul.getAttribute('data-cot');
        if (!cot) return;
        Sortable.create(ul, {
            group: { name: 'cot-' + cot, pull: true, put: true },
            draggable: '.o-buoi-keo',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'dang-keo',
            onEnd: function (evt) {
                if (evt.to !== evt.from && evt.to.children.length > 1) {
                    Array.from(evt.to.children).forEach(function (el) {
                        if (el !== evt.item) {
                            evt.from.appendChild(el);
                        }
                    });
                }
                capNhatInputAn();
            }
        });
    });

    capNhatInputAn();
})(jQuery);
</script>
@else
<script>
(function ($) {
    var $kh = $('#ma_kh');
    if ($kh.length) {
        $kh.select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true,
            placeholder: $kh.data('placeholder') || '—'
        });
    }
})(jQuery);
</script>
@endif
@endpush