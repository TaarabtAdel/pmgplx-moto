@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', $item['title'])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white border py-2 small mb-3">
            <li class="breadcrumb-item"><a href="{{ route('qldtmoto.index') }}">QLĐT MÔ TÔ</a></li>
            @foreach (array_slice($duongDan, 1) as $buoc)
                <li class="breadcrumb-item">{{ $buoc }}</li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
        </ol>
    </nav>

    <div class="card card-panel">
        <div class="card-header">{{ $item['title'] }}</div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <p class="text-muted small">
                Lịch <strong>lý thuyết hạng A</strong> theo mẫu <em>4. LICH HỌC LT A1K67</em> (một file / lớp).
                <strong>Ngày học</strong> mặc định <strong>Ngày KG</strong> trên
                <a href="{{ $linkDanhMucKhoaHoc }}" target="_blank">danh mục khoá học</a>
                (<code>{{ config('database.connections.sqlsrv_moto.database') }}</code> — hiện chỉ có hạng A, vd. <code>A1m</code>).
            </p>

            <form method="GET" action="{{ route('qldtmoto.lich-du-kien.ly-thuyet.index') }}" class="form-row align-items-end mb-3">
                <div class="form-group col-md-5 mb-0">
                    <label for="ma_kh">Khoá (PMGPLX)</label>
                    <select class="form-control form-control-sm sel-khoa-pmgplx" id="ma_kh" name="ma_kh" data-placeholder="— Chọn khoá —">
                        <option value=""></option>
                        @foreach ($khoaOptions as $kh)
                            <option value="{{ $kh->MaKH }}" @selected($maKh === $kh->MaKH)>
                                {{ $kh->TenKH }} ({{ $kh->MaKH }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($maKh !== '' && $hang !== '')
                    <div class="form-group col-md-3 mb-0">
                        <label class="d-block">Hạng đào tạo</label>
                        <p class="form-control-plaintext small py-1 mb-0 font-weight-bold">
                            {{ $nhanHang[$hang] ?? $hang }}
                        </p>
                    </div>
                @endif
                <div class="form-group col-md-2 mb-0">
                    <button type="submit" class="btn btn-sm btn-primary btn-block">Chọn</button>
                </div>
            </form>

            @if ($maKh === '')
                <p class="text-muted mb-0">Chọn khoá để điền lịch và xuất Excel.</p>
            @else
                <form method="POST" action="{{ route('qldtmoto.lich-du-kien.ly-thuyet.export') }}">
                    @csrf
                    <input type="hidden" name="ma_kh" value="{{ $maKh }}">

                    @include('QldtMoto.lich-hoc-ly-thuyet-thuc-hanh.partials.form-a1', [
                        'cauHinh' => $cauHinh,
                        'lopOptions' => $lopOptions,
                        'gvOptions' => $gvOptions,
                        'previewTieuDe' => $previewTieuDe,
                        'hang' => $hang,
                        'nhanHang' => $nhanHang,
                    ])

                    <button type="submit" class="btn btn-success">
                        ⬇ Xuất Excel lịch LT (lớp {{ $cauHinh['lop'] }})
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('#ma_kh').select2({
        theme: 'bootstrap4',
        placeholder: 'Tìm khoá học...',
        allowClear: true,
        width: '100%'
    });
</script>
@endpush