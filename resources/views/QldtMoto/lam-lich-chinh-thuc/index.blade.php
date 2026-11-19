@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', $item['title'])

@section('content')
    @include('QldtMoto.lam-lich-chinh-thuc.partials.breadcrumb')

    <div class="card card-panel">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <span class="mr-auto">{{ $item['title'] }}</span>
            <span class="badge badge-secondary">Chỉ hướng dẫn — không thao tác trên phần mềm</span>
        </div>
        <div class="card-body">
            <div class="alert alert-warning border mb-4">
                <strong>Mục menu này không làm chức năng gì trên QLĐT / PMGPLX.</strong>
                Chỉ ghi rõ quy trình thủ công khi «chốt» lịch chính thức cho khoá mô tô A1-K67.
            </div>

            <h6 class="text-navy">1. «Lịch chính thức» là gì (mô tô A1)?</h6>
            <p class="small text-muted">
                Sau khi đã có lịch từ bước <strong>lịch dự kiến</strong>, «lịch chính thức» là <strong>file Excel in / ký / lưu hồ sơ</strong>
                (mẫu <em>4. LỊCH HỌC LT A1K67</em> và <em>5. LỊCH HỌC THỰC HÀNH</em>), không phải một bản sao nhập ngược vào PMGPLX.
            </p>
            <ul class="small mb-4">
                <li>
                    <strong>Lịch lý thuyết:</strong>
                    <a href="{{ route('qldtmoto.lich-du-kien.ly-thuyet.index') }}">Làm lịch dự kiến → Lý thuyết</a>
                    → sinh / xuất Excel → in.
                </li>
                <li>
                    <strong>Lịch thực hành:</strong>
                    <a href="{{ route('qldtmoto.lich-du-kien.thuc-hanh.index') }}">Làm lịch dự kiến → Thực hành</a>
                    → sinh lịch → xuất Excel mẫu A1-K67 → in.
                </li>
                <li>
                    <strong class="text-danger">Không</strong> dùng chức năng nhập lịch từ file trên PMGPLX
                    (<code>KhoaHoc_GiaoVien</code>, <code>KhoaHoc_XeTap</code>) cho khoá mô tô A1 theo bộ A1-K67.
                </li>
            </ul>

            <h6 class="text-navy">2. Thông tin bắt buộc ghi trên hồ sơ: khai giảng — bế giảng</h6>
            <p class="small text-muted mb-2">
                Với từng khoá, lấy hai ngày sau trên <strong>danh mục khoá PMGPLX-MOTO</strong> (bảng <code>KhoaHoc</code>), rồi
                <strong>ghi rõ</strong> (tay hoặc trên biểu mẫu in) — <strong>không</strong> «nhập lịch» qua phần mềm:
            </p>

            <div class="row text-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="card border-primary h-100">
                        <div class="card-body py-3">
                            <div class="small text-muted">Ngày khai giảng</div>
                            <div class="h4 text-primary mb-1">Cột <code>NgayKG</code></div>
                            <p class="small text-muted mb-0">Trên danh mục khoá PMGPLX-MOTO, đúng mã / tên khoá (vd. K26A1K67).</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-success h-100">
                        <div class="card-body py-3">
                            <div class="small text-muted">Ngày bế giảng</div>
                            <div class="h4 text-success mb-1">Cột <code>NgayBG</code></div>
                            <p class="small text-muted mb-0">Cùng bản ghi khoá — dùng khi in báo cáo, quyết định, lịch kèm tên khoá.</p>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="text-navy">3. Việc cần làm khi «làm lịch chính thức»</h6>
            <ol class="small text-muted">
                <li>Mở PMGPLX-MOTO → danh mục khoá → xác định <strong>NgayKG</strong>, <strong>NgayBG</strong>, tên khoá, tổng HV.</li>
                <li>Xuất lại (hoặc dùng file đã xuất) lịch LT + TH từ menu <strong>Lịch dự kiến</strong>.</li>
                <li>Trên file in / hồ sơ: ghi đầy đủ <strong>tên khoá</strong>, <strong>ngày khai giảng</strong>, <strong>ngày bế giảng</strong> cho đúng khoá đó.</li>
                <li>Lưu bản cứng / PDF; <strong>không</strong> import lịch buổi học vào PM cho mục đích «chính thức hoá» A1-K67.</li>
            </ol>

            <hr class="my-4">

            <p class="small mb-0 text-muted">
                Liên quan: mẫu in lấy từ menu
                <a href="{{ route('qldtmoto.lich-du-kien.ly-thuyet.index') }}">Lịch dự kiến lý thuyết</a>,
                <a href="{{ route('qldtmoto.lich-du-kien.thuc-hanh.index') }}">Lịch dự kiến thực hành</a>.
                Menu <strong>{{ $item['title'] }}</strong> chỉ giữ để nhắc quy trình trên.
            </p>
        </div>
    </div>
@endsection