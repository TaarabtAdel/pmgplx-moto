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
            @if (! empty($item['mo_ta']))
                <div class="alert alert-info py-2">
                    <strong>Ghi chú:</strong> {{ $item['mo_ta'] }}
                </div>
            @endif
            @if (($slug ?? '') === '1-danh-sach-hoc-vien-lai-xe' || ($slug ?? '') === '1-xuat-danh-sach-hoc-vien-pmgplx')
                <p class="mb-3">
                    Xuất danh sách học viên từ <strong>PMGPLX-MOTO</strong>: vào danh mục học viên, lọc theo khóa, chọn cột rồi <strong>Xuất Excel</strong>.
                </p>
                <a href="{{ route('pmgplxmoto.dm.hoc-vien.index') }}" class="btn btn-sm btn-success">Mở danh mục học viên — xuất Excel</a>
                <a href="{{ route('qldtmoto.danh-sach-hoc-vien-lai-xe.index') }}" class="btn btn-sm btn-primary ml-1">Màn xuất DS BC1 (Word/Excel)</a>
            @elseif (($slug ?? '') === '1-nhap-ho-so-hoc-vien-pmgplx')
                <p class="mb-3">Nhập danh sách học viên đăng ký thi từ file Excel lên PMGPLX-MOTO.</p>
                <a href="{{ route('pmgplxmoto.dm.hoc-vien.nhap-file.create') }}" class="btn btn-sm btn-navy">Mở nhập học viên từ file</a>
            @elseif (($slug ?? '') === '1-tao-khoa-hoc-pmgplxmoto')
                <p class="mb-3">Tạo và quản lý khoá học trên PMGPLX-MOTO.</p>
                <a href="{{ route('pmgplxmoto.dm.khoa-hoc.index') }}" class="btn btn-sm btn-navy">Mở quản lý khoá học</a>
            @elseif (($slug ?? '') === '1-kiem-tra-hoc-vien-anh-the')
                <p class="mb-3">Rà soát học viên và ảnh thẻ trên PMGPLX-MOTO (dò thông tin CCCD, ảnh JP2).</p>
                <a href="{{ route('pmgplxmoto.dm.hoc-vien.index') }}" class="btn btn-sm btn-primary">Danh mục học viên</a>
            @elseif (($slug ?? '') === '2-cap-nhat-ket-qua-dao-tao' || ($slug ?? '') === '2-ket-xuat-ket-qua-dao-tao' || ($slug ?? '') === '2-xac-nhan-hoan-thanh-khoa-hoc')
                <p class="mb-3">Cập nhật / đồng bộ kết quả đào tạo trên PMGPLX-MOTO.</p>
                <a href="{{ route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.form') }}" class="btn btn-sm btn-navy">Cập nhật kết quả đào tạo (bản cũ)</a>
                <a href="{{ route('pmgplxmoto.dm.hoc-vien.ket-qua-khoa-hoc.form') }}" class="btn btn-sm btn-outline-primary ml-1">Kết quả theo khoá</a>
            @elseif (($slug ?? '') === '1-danh-sach-hoc-vien-tap-trung-lop-ly-thuyet' || ($slug ?? '') === '1-danh-sach-hoc-vien-tap-trung-lop-thuc-hanh')
                <p class="mb-3">Danh sách HV đủ thời gian LT/TH: lọc theo kết quả đào tạo trên PMGPLX-MOTO và xuất Excel.</p>
                <a href="{{ route('pmgplxmoto.dm.hoc-vien.index') }}" class="btn btn-sm btn-success">Danh mục học viên</a>
                <a href="{{ route('pmgplxmoto.dm.hoc-vien.ket-qua-khoa-hoc.form') }}" class="btn btn-sm btn-outline-primary ml-1">Kết quả theo khoá</a>
            @elseif (($slug ?? '') === '3-dua-vao-phan-mem-thi-lai' || ($slug ?? '') === '3-cap-nhat-ds-thi-lai-pm')
                <p class="mb-3">Danh sách thi lại nhập trên PMGPLX-MOTO (bản cũ nếu cần so sánh).</p>
                <a href="{{ route('pmgplxmotoold.dm.hoc-vien.index') }}" class="btn btn-sm btn-success">Học viên PMGPLX bản cũ</a>
            @elseif (($slug ?? '') === '4-lap-luu-ho-so-quan-ly-dao-tao')
                <p class="mb-3">Checklist lập lưu theo mindnote — các mục tương ứng trong menu QLĐT XE MÁY.</p>
                <a href="{{ route('qldtmoto.index') }}" class="btn btn-sm btn-outline-secondary">Xem tổng quan các bước</a>
            @else
                @php $mauA1 = \App\Support\QldtMoto\MauKhoaMoto::choTrang($slug ?? ''); @endphp
                @if ($mauA1 !== null && ($mauA1['mau'] !== [] || $mauA1['ghi_chu'] !== ''))
                    <p class="small text-muted mb-2">
                        <strong>Mẫu khoá A1-K67:</strong>
                        {{ \App\Support\QldtMoto\MauKhoaMoto::nhanTrangThai($mauA1['trang_thai']) }}
                    </p>
                    @if ($mauA1['mau'] !== [])
                        <ul class="small mb-3">
                            @foreach ($mauA1['mau'] as $tenFile)
                                <li><code>{{ $tenFile }}</code></li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($mauA1['ghi_chu'] !== '')
                        <p class="small mb-3">{{ $mauA1['ghi_chu'] }}</p>
                    @endif
                @endif
                <p class="text-muted mb-0">Mục quy trình nội bộ hoặc chưa có màn xuất file riêng — xem mục con trong menu có biểu tượng xuất Word/Excel.</p>
            @endif
        </div>
    </div>
@endsection