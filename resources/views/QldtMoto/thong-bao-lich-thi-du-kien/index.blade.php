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
            <span class="badge badge-secondary">Chỉ hướng dẫn — thủ tục với CSGT</span>
        </div>
        <div class="card-body">
            <div class="alert alert-warning border mb-4">
                <strong>Mục này không có chức năng trên QLĐT / PMGPLX.</strong>
                Đây là bước <strong>hành chính nội bộ + làm việc với phòng CSGT</strong>, không nhập dữ liệu trên phần mềm.
            </div>

            <div class="card border-primary mb-4">
                <div class="card-body py-3">
                    <h6 class="text-primary mb-2">Nội dung cần ghi rõ</h6>
                    <p class="mb-0">
                        <strong>Căn cứ vào tình hình nhu cầu học viên tại thời điểm đó</strong>, trung tâm
                        <strong>xin phòng CSGT</strong> cấp <strong>lịch thi dự kiến</strong> (ngày / đợt thi dự kiến phù hợp số lượng và tiến độ HV).
                    </p>
                </div>
            </div>

            <h6 class="text-navy">Ý nghĩa bước này</h6>
            <ul class="small text-muted mb-3">
                <li>Lịch thi <strong>dự kiến</strong> chưa phải lịch thi <strong>chính thức</strong> — dùng để chủ động kế hoạch đào tạo và thông báo sơ bộ cho HV (nhóm Zalo, lớp).</li>
                <li>Số lượng / thời điểm xin phụ thuộc <strong>nhu cầu HV tại thời điểm làm hồ sơ</strong>: HV đủ điều kiện thi, số lượng đủ một đợt, khung thời gian CSGT còn slot.</li>
                <li>Kết quả là phòng CSGT đồng ý <em>đợt / ngày thi dự kiến</em> (hoặc hướng dẫn bổ sung) — lưu vào hồ sơ khoá theo quy định nội bộ.</li>
            </ul>

            <h6 class="text-navy">Gợi ý việc làm (thủ công)</h6>
            <ol class="small text-muted mb-3">
                <li>Rà soát PMGPLX-MOTO: HV khoá đang học / sắp hoàn thành, ước lượng số HV đăng ký thi trong kỳ.</li>
                <li>Soạn hồ sơ / công văn xin lịch theo mẫu phòng CSGT và quy định hiện hành.</li>
                <li>Trình ký, nộp phòng CSGT; nhận phản hồi lịch <strong>dự kiến</strong>.</li>
                <li>Thông báo sơ bộ cho HV (Zalo lớp, bảng tin) — chi tiết chính thức khi có lịch từ CSGT ở bước sau trong menu.</li>
            </ol>

            <p class="small text-muted mb-0">
                Liên quan: menu <em>Kênh liên lạc và tài liệu học tập</em> (nhóm Zalo, thông báo lịch thi chính thức khi CSGT có lịch chính thức).
                Mục <strong>{{ $item['title'] }}</strong> chỉ mô tả mục đích xin lịch dự kiến như trên.
            </p>
        </div>
    </div>
@endsection