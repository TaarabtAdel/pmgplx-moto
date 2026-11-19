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
            <span class="badge badge-secondary">Chỉ hướng dẫn — không chức năng trên phần mềm</span>
        </div>
        <div class="card-body">
            <div class="alert alert-warning border mb-4">
                <strong>Mục này không có chức năng trên QLĐT / PMGPLX.</strong>
                Việc cung cấp tài liệu thực hiện <strong>thủ công</strong> (in, file, Zalo nhóm lớp, v.v.).
            </div>

            <div class="card border-primary mb-4">
                <div class="card-body py-3">
                    <h6 class="text-primary mb-2">Nội dung cần ghi rõ</h6>
                    <p class="mb-2">
                        <strong>Cung cấp cho học viên:</strong>
                    </p>
                    <ul class="mb-2">
                        <li><strong>Tài liệu học lý thuyết</strong></li>
                        <li><strong>Tài liệu học thực hành</strong></li>
                    </ul>
                    <p class="mb-0">
                        <strong>Nguồn tài liệu:</strong> tài liệu từ <strong>cô Huyền</strong> (bộ LT + TH do cô Huyền cung cấp / phụ trách).
                    </p>
                </div>
            </div>

            <h6 class="text-navy">Gợi ý việc làm</h6>
            <ul class="small text-muted mb-3">
                <li>Nhận file / bản in tài liệu LT, TH từ <strong>cô Huyền</strong> theo từng khoá / hạng (vd. A1-K67).</li>
                <li>Phát cho HV khi nhập học hoặc qua <strong>nhóm Zalo lớp</strong> (file PDF, link, ảnh — tùy quy ước trung tâm).</li>
                <li>Lưu một bản trong hồ sơ khoá: đã cung cấp tài liệu LT + TH, ngày phát, số lượng HV nhận.</li>
            </ul>

            <p class="small text-muted mb-0">
                Liên quan: menu <em>Tạo nhóm Zalo học viên</em> (kênh gửi tài liệu).
                Mục <strong>{{ $item['title'] }}</strong> chỉ mô tả nội dung cung cấp tài liệu như trên.
            </p>
        </div>
    </div>
@endsection