@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', $item['title'])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white border py-2 small mb-3">
            <li class="breadcrumb-item"><a href="{{ route('qldtmoto.index') }}">QLĐT MÔ TÔ</a></li>
            @foreach ($duongDan as $buoc)
                <li class="breadcrumb-item">{{ $buoc }}</li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
        </ol>
    </nav>

    <div class="card card-panel">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <span class="mr-auto">{{ $item['title'] }}</span>
            <span class="badge badge-secondary">Chỉ hướng dẫn — thông báo trên Zalo</span>
        </div>
        <div class="card-body">
            <div class="alert alert-warning border mb-4">
                <strong>Mục này không có chức năng trên QLĐT / PMGPLX.</strong>
                Việc thông báo thực hiện <strong>thủ công trên nhóm Zalo</strong> học viên của khoá.
            </div>

            <div class="card border-success mb-4">
                <div class="card-body py-3">
                    <h6 class="text-success mb-2">Nội dung cần ghi rõ</h6>
                    <p class="mb-0">
                        <strong>Sau khi có lịch thi chính thức</strong> (từ phòng CSGT / công an),
                        <strong>thông báo cho học viên</strong> trên <strong>nhóm Zalo lớp</strong>
                        (ngày, giờ, địa điểm, hồ sơ mang theo theo quy định).
                    </p>
                </div>
            </div>

            <h6 class="text-navy">Phân biệt với lịch dự kiến</h6>
            <p class="small text-muted mb-3">
                Bước <a href="{{ route('qldtmoto.thong-bao-lich-thi-du-kien.index') }}">Thông báo lịch thi dự kiến</a>
                là xin <em>lịch dự kiến</em> với CSGT. Mục này chỉ thực hiện khi đã có <strong>lịch thi chính thức</strong> —
                thông tin có hiệu lực để HV đi thi.
            </p>

            <h6 class="text-navy">Gợi ý việc làm</h6>
            <ol class="small text-muted mb-3">
                <li>Nhận lịch thi chính thức từ CSGT; đối chiếu danh sách HV đủ điều kiện trên PMGPLX-MOTO.</li>
                <li>Soạn tin / file tóm tắt lịch thi (theo mẫu nội bộ, không sai lệch so với lịch CSGT).</li>
                <li>Đăng lên <strong>nhóm Zalo học viên</strong> khoá (đã tạo ở bước <a href="{{ route('qldtmoto.tao-nhom-zalo-hoc-vien.index') }}">Tạo nhóm Zalo</a>).</li>
                <li>Nhắc HV xác nhận đã đọc; lưu ảnh chụp / bản ghi thông báo vào hồ sơ khoá nếu cần.</li>
            </ol>

            <p class="small text-muted mb-0">
                Mục <strong>{{ $item['title'] }}</strong> chỉ mô tả: có lịch chính thức → thông báo HV qua Zalo.
            </p>
        </div>
    </div>
@endsection