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
            <span class="badge badge-secondary">Chỉ hướng dẫn — thao tác trên app Zalo</span>
        </div>
        <div class="card-body">
            <div class="alert alert-warning border mb-4">
                <strong>Mục này không có chức năng trên QLĐT / PMGPLX.</strong>
                Việc tạo nhóm và mời học viên thực hiện <strong>thủ công trên Zalo</strong> (điện thoại hoặc Zalo PC).
            </div>

            <h6 class="text-navy">Mục đích</h6>
            <p class="small text-muted">
                Với từng khoá / lớp (vd. A1-K67), <strong>tạo nhóm Zalo</strong> làm kênh liên lạc lớp
                và <strong>mời từng học viên</strong> của khoá đó vào nhóm để thông báo lịch học, lịch thi, tài liệu.
            </p>

            <h6 class="text-navy mt-4">1. Chuẩn bị danh sách học viên</h6>
            <ul class="small text-muted mb-3">
                <li>Lấy danh sách HV đúng khoá trên <strong>PMGPLX-MOTO</strong> (họ tên, số điện thoại Zalo).</li>
                <li>
                    Có thể xuất / xem tại
                    <a href="{{ route('pmgplxmoto.dm.hoc-vien.index') }}">Danh mục học viên</a>
                    hoặc file danh sách đăng ký khoá.
                </li>
                <li>Chuẩn bị số Zalo của <strong>GVCN</strong>, giáo viên LT/TH cần tham gia nhóm (nếu có quy định nội bộ).</li>
            </ul>

            <h6 class="text-navy">2. Tạo nhóm Zalo</h6>
            <ol class="small text-muted mb-3">
                <li>Mở <strong>Zalo</strong> → <strong>Tạo nhóm</strong> (hoặc Nhóm chat → Tạo nhóm mới).</li>
                <li>
                    Đặt tên nhóm thống nhất với tên khoá, ví dụ:
                    <code>HV A1-K67 K26 …</code> hoặc theo quy ước của trung tâm.
                </li>
                <li>Chọn ảnh đại diện / mô tả ngắn (tùy chọn): ghi hạng GPLX, ngày khai giảng nếu cần.</li>
                <li>Thêm trước 1–2 quản trị viên (GVCN, nhân viên phụ trách) để quản lý nhóm.</li>
            </ol>

            <h6 class="text-navy">3. Mời học viên vào lớp (vào nhóm)</h6>
            <ol class="small text-muted mb-3">
                <li>Trong nhóm: <strong>Mời thành viên</strong> → chọn từ danh bạ hoặc tìm theo <strong>số điện thoại</strong> từng HV.</li>
                <li>Mời đủ HV của khoá; với HV chưa có Zalo, nhắc đăng ký / gửi link mời khi có số.</li>
                <li>Sau khi vào nhóm: nhắc HV đặt tên hiển thị đúng <strong>họ tên</strong> (trùng hồ sơ đào tạo).</li>
                <li>Gửi tin chào + nội quy nhóm (không spam, thông báo lịch chính thức từ trung tâm, v.v.).</li>
            </ol>

            <h6 class="text-navy">4. Ghi nhận nội bộ</h6>
            <p class="small text-muted mb-0">
                Lưu tên nhóm, link/ID nhóm (nếu dùng) và ngày tạo vào sổ theo dõi khoá.
                Các bước <strong>thông báo lịch thi</strong>, <strong>gửi tài liệu</strong> thực hiện trên cùng nhóm Zalo
                — xem các mục kế tiếp trong menu <em>Kênh liên lạc và tài liệu học tập</em>.
            </p>
        </div>
    </div>
@endsection