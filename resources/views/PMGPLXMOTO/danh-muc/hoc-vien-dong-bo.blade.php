@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', 'Đồng bộ học viên qua bản cũ')

@push('styles')
<style>
    .hoc-vien-dong-bo-planned-scroll,
    .hoc-vien-dong-bo-source-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        max-width: 100%;
    }
  /* Bootstrap .table-responsive ép table width:100% — bảng trái bị bó cột */
    .hoc-vien-dong-bo-source-scroll.table-responsive,
    .hoc-vien-dong-bo-planned-scroll.table-responsive {
        display: block;
    }
    .hoc-vien-dong-bo-source-scroll.table-responsive > table,
    .hoc-vien-dong-bo-planned-scroll.table-responsive > table {
        width: 1500px;
        min-width: 1500px;
        max-width: none;
        table-layout: fixed;
    }
    .hoc-vien-dong-bo-preview .col-stt {
        width: 4.5rem;
    }
    .hoc-vien-dong-bo-preview colgroup col.col-ma-dk {
        width: 220px;
    }
    .hoc-vien-dong-bo-preview colgroup col.col-ho-ten {
        width: 220px;
    }
    .hoc-vien-dong-bo-preview colgroup col.col-stt {
        width: 4.5rem;
    }
    .hoc-vien-dong-bo-preview colgroup col.col-so-gplx {
        width: 11rem;
    }
    .hoc-vien-dong-bo-preview colgroup col.col-ngay-tt-gplx {
        width: 10rem;
    }
    .hoc-vien-dong-bo-preview .col-so-gplx {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .hoc-vien-dong-bo-preview .col-ngay-tt-gplx {
        white-space: nowrap;
        vertical-align: middle;
    }
    .hoc-vien-dong-bo-preview colgroup col.col-don-vi-cap,
    .hoc-vien-dong-bo-preview colgroup col.col-hang,
    .hoc-vien-dong-bo-preview colgroup col.col-dvc-hien-tai,
    .hoc-vien-dong-bo-preview colgroup col.col-dvc-ban-cu {
        width: 200px;
    }
    .hoc-vien-dong-bo-preview .col-don-vi-cap,
    .hoc-vien-dong-bo-preview .col-hang,
    .hoc-vien-dong-bo-preview .col-dvc-hien-tai,
    .hoc-vien-dong-bo-preview .col-dvc-ban-cu {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }
    .hoc-vien-dong-bo-preview .col-dvc-ban-cu:hover {
        overflow: visible;
        white-space: normal;
        word-break: break-word;
        position: relative;
        z-index: 2;
        background: #fff;
        box-shadow: 0 0 0 1px #dee2e6;
    }
    .hoc-vien-dong-bo-preview .col-ma-dk,
    .hoc-vien-dong-bo-preview .col-ma-dk-du-kien {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }
    .hoc-vien-dong-bo-preview .col-ma-dk code {
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: bottom;
    }
    .hoc-vien-dong-bo-preview .col-ho-ten {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }
    .hoc-vien-dong-bo-preview .col-ho-ten:hover {
        overflow: visible;
        white-space: normal;
        word-break: break-word;
        position: relative;
        z-index: 2;
        background: #fff;
        box-shadow: 0 0 0 1px #dee2e6;
    }
    .hoc-vien-dong-bo-row-dvc-doi {
        background-color: #e8f4fd;
    }
    .hoc-vien-dong-bo-row-dvc-doi.hoc-vien-dong-bo-row-conflict {
        background-color: #ffe8a1;
    }
    .hoc-vien-dong-bo-row-conflict {
        background-color: #fff3cd;
    }
    .hoc-vien-dong-bo-row-conflict code {
        color: #856404 !important;
    }
    .hoc-vien-dong-bo-row-tt-xu-ly {
        background-color: #ffe8cc !important;
    }
    .hoc-vien-dong-bo-preview colgroup col.col-tt-xu-ly {
        width: 10.5rem;
    }
    .hoc-vien-dong-bo-preview .col-tt-xu-ly {
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .dong-bo-tt-xu-ly-select {
        max-width: 9.5rem;
        font-size: 0.8rem;
        padding: 0.1rem 0.25rem;
        height: calc(1.5em + 0.35rem);
    }
    .hoc-vien-dong-bo-preview colgroup col.col-anh {
        width: 28px;
    }
    .hoc-vien-dong-bo-preview .col-anh {
        width: 28px;
        text-align: center;
        vertical-align: middle;
        padding: 2px !important;
    }
    .dong-bo-anh-thumb {
        width: auto;
        max-width: 36px;
        height: 18px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid #ced4da;
        cursor: pointer;
        display: block;
    }
    .dong-bo-anh-thumb-btn:focus {
        outline: 2px solid #005BAC;
        outline-offset: 1px;
    }
    #dongBoAnhLonModal .modal-body img {
        max-width: 100%;
        max-height: 75vh;
        margin: 0 auto;
        display: block;
    }
    .dong-bo-anh-planned-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }
    .dong-bo-anh-ban-cu-stack {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1px;
    }
    .dong-bo-anh-ban-cu-stack .dong-bo-anh-thumb {
        border-color: #ffc107;
    }
    .dong-bo-anh-ban-cu-label {
        font-size: 0.6rem;
        padding: 0 0.2rem;
        line-height: 1.2;
    }
</style>
@endpush

@section('content')
    @php
        $meta = $preview['meta'] ?? null;
        $sourceRows = $preview['source'] ?? [];
        $plannedRows = $preview['planned'] ?? [];
        $conflictCount = (int) ($meta['conflict_count'] ?? 0);
        $updateCount = (int) ($meta['update_count'] ?? $conflictCount);
        $syncableCount = (int) ($meta['syncable_count'] ?? count($sourceRows));
        $canSync = $maKhNguon !== '' && $maKhDich !== '' && (count($sourceRows) > 0);
        $lastBatch = $lastBatch ?? null;
        $plannedBySourceMaDk = [];
        foreach ($plannedRows as $plannedRow) {
            $src = $plannedRow['ma_dk_nguon'] ?? null;
            if ($src !== null && $src !== '') {
                $plannedBySourceMaDk[$src] = $plannedRow;
            }
        }
        $maDkNguonTest = old('ma_dk_nguon', $maDkNguonTest ?? '');
        $defaultMaDkNguon = $maDkNguonTest !== ''
            ? $maDkNguonTest
            : (($plannedRows[0]['ma_dk_nguon'] ?? null) ?: ($sourceRows[0]['ma_dk'] ?? ''));
        $canTest = $maKhDich !== '' && $maKhNguon !== '' && count($sourceRows) > 0;
        $ttXuLyHighCount = (int) ($meta['tt_xu_ly_high_count'] ?? 0);
        $ttXuLyMacDinh = old('tt_xuly_mac_dinh', \App\Support\PMGPLXMOTO\DongBoTtXuLy::CHOICE_KEEP);
        $ttXuLyPerHv = old('tt_xuly', []);
        $sourceByMaDk = collect($sourceRows)->keyBy('ma_dk');
    @endphp

    @if (! empty($lastBatch) && ! empty($lastBatch['ma_dks']))
        <div class="card card-panel mb-3 border-success">
            <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center">
                <span>{{ ! empty($lastBatch['is_test']) ? 'Test đồng bộ gần đây' : 'Lần đồng bộ gần đây' }}</span>
                <span class="small text-muted">{{ $lastBatch['synced_at'] ?? '' }}</span>
            </div>
            <div class="card-body d-flex flex-wrap align-items-center">
                <div class="mr-3 mb-2">
                    @if (! empty($lastBatch['is_test']))
                        Đã test đưa <strong>1</strong> học viên
                        @if (! empty($lastBatch['ho_ten']))
                            (<strong>{{ $lastBatch['ho_ten'] }}</strong>)
                        @endif
                        — mã ĐK <code>{{ $lastBatch['source_ma_dk'] ?? '' }}</code> → <code>{{ $lastBatch['target_ma_dk'] ?? '' }}</code>
                    @else
                        Đã đưa <strong>{{ number_format((int) ($lastBatch['count'] ?? 0)) }}</strong> học viên
                    @endif
                    từ khóa <code>{{ $lastBatch['ma_kh_nguon'] ?? '' }}</code>
                    sang khóa <code>{{ $lastBatch['ma_kh_dich'] ?? '' }}</code> trên bản cũ.
                </div>
                <a href="{{ route('pmgplxmotoold.dm.hoc-vien.index', ['ma_kh' => $lastBatch['ma_kh_dich'] ?? '', 'tu_khoa' => $lastBatch['target_ma_dk'] ?? '']) }}"
                   class="btn btn-outline-secondary btn-sm mb-2 mr-2" target="_blank">
                    Xem trên bản cũ
                </a>
                <a href="{{ route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.form', ['ma_khoa_hoc' => $lastBatch['ma_kh_dich'] ?? '', 'ma_kh_nguon' => $lastBatch['ma_kh_nguon'] ?? '']) }}"
                   class="btn btn-info btn-sm mb-2 mr-2">
                    Cập nhật KQĐT bản cũ
                </a>
                <form method="POST" action="{{ route('pmgplxmoto.dm.hoc-vien.dong-bo.khoi-phuc') }}" class="mb-2"
                      onsubmit="return confirm('Khôi phục sẽ xóa {{ number_format((int) ($lastBatch['count'] ?? 0)) }} học viên vừa đồng bộ khỏi bản cũ. Tiếp tục?');">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        Khôi phục
                    </button>
                </form>
                <span class="text-muted small ml-3 mb-2">
                    Khôi phục = xóa dữ liệu đã đưa qua phần mềm cũ ở lần đồng bộ vừa rồi.
                </span>
            </div>
        </div>
    @endif

    <div class="card card-panel mb-3">
        <div class="card-header bg-light">Hướng dẫn sử dụng</div>
        <div class="card-body pb-2">
            <ol class="mb-3 pl-3">
                <li class="mb-2">
                    Chọn <strong>Mã khóa học nguồn</strong> (phần mềm mới) — danh sách học viên bên trái sẽ hiện ngay.
                </li>
                <li class="mb-2">
                    Chọn <strong>Khóa học đích</strong> (phần mềm cũ), bấm <strong>Xem trước</strong> — bảng bên phải hiện mã ĐK dự kiến (thay tiền tố mã khóa, phần số sau gộp liền).
                </li>
                <li class="mb-2">
                    Kiểm tra dòng vàng (<strong>Đã tồn tại</strong>): những mã ĐK đã có trên bản cũ sẽ được <strong>cập nhật</strong> khi đồng bộ lại; dòng không vàng là học viên sẽ được thêm mới.
                </li>
                <li class="mb-2">
                    (Khuyến nghị) Nhập <strong>mã ĐK nguồn</strong> và bấm <strong>Test học viên</strong> — nếu không ổn, dùng <strong>Khôi phục</strong> để xóa trên bản cũ.
                </li>
                <li class="mb-2">
                    Bấm <strong>Xác nhận đồng bộ sang bản cũ</strong> để đưa toàn bộ học viên sang phần mềm cũ (thêm mới hoặc cập nhật nếu đã có).
                </li>
                <li class="mb-2">
                    Sau đồng bộ: cập nhật <strong>kết quả đào tạo</strong> trên bản cũ
                    (<code>TGBatDau</code>, <code>TGKetThuc</code>, <code>KetLuanCSDT</code>) —
                    <a href="{{ route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.form', array_filter(['ma_khoa_hoc' => $maKhDich, 'ma_kh_nguon' => $maKhNguon])) }}">Cập nhật KQĐT bản cũ</a>
                    hoặc <a href="{{ route('pmgplxmoto.dm.hoc-vien.ket-qua-khoa-hoc.form', array_filter(['ma_khoa_hoc' => $maKhDich])) }}">Xem lại KQĐT khóa</a>
                    (tương đương Publish_KQTN_A1).
                </li>
            </ol>
            <div class="alert alert-info mb-3">
                <strong>Phạm vi dữ liệu mỗi học viên</strong> (cả phần mềm mới và cũ đều có cùng các bảng sau, liên kết qua <code>MaDK</code>):
                <ul class="mb-2 mt-2 pl-3">
                    <li><strong>NguoiLX</strong> — thông tin cá nhân (họ tên, CMT, ngày sinh…)</li>
                    <li><strong>NguoiLX_HoSo</strong> — hồ sơ khóa học (số hồ sơ, mã khóa, hạng GPLX…)</li>
                    <li><strong>NguoiLX_GPLX</strong> — GPLX đã có (nếu có)</li>
                </ul>
                Copy sang bản cũ giống <code>PUBLISH_DATA_GPLX/sql/03-copy-khoa-hoc-makh-dich.sql</code>:
                <strong>BaoCaoI</strong> (nếu có) + <strong>NguoiLX</strong> + <strong>NguoiLX_HoSo</strong> — danh sách cột cố định như script 03.
                Tạo/chọn <strong>mã khóa đích</strong> trên PM cũ trước — luồng <strong>mã mới một bước</strong> (giống <code>03-copy-khoa-hoc-makh-dich.sql</code>),
                không clone <code>MaBCI</code>/<code>MaKH</code> nguồn lên đích (tránh lỗi sau <em>Đổi mã khóa</em>).
                <code>NguoiLX_HoSo.TT_XuLy</code> copy từ PM mới (mã trong <code>DM_TrangThai</code>).
                Nếu mã <strong>&gt; 04</strong> (vd. <code>09</code> tốt nghiệp), màn xem trước cảnh báo — chọn <strong>giữ nguyên</strong> hoặc ghi <code>03</code>/<code>04</code> sang bản cũ.
                <code>BaoCaoI</code> = <code>DKKH_{MaKH đích}</code>, <code>SoBaoCao</code> đổi prefix, <code>TrangThai=1</code>.
                Map: <code>MaDK</code> prefix; <code>MaKhoaHoc</code>/<code>BaoCaoI.MaKH</code> = khóa đích; <code>DuongDanAnh</code> giữ như nguồn. Cập nhật <code>KhoaHoc</code> từ nguồn (chạy lại được khi HV đã đủ).
                <strong>Map khi ghi:</strong> <code>MaDK</code> đổi prefix; <code>MaKhoaHoc</code>/<code>BaoCaoI.MaKH</code> = khóa đích;
                <code>MaCSDT</code> / <code>MaSoGTVT</code> / <code>MaDVNhanHSo</code> / <code>DonViNhanHSo</code> theo khóa đích; học viên có <code>MaDK</code> đúng prefix khóa nguồn.
                Học viên đã có <code>MaDK</code> trên bản cũ sẽ được <strong>cập nhật</strong> (gồm <code>DuongDanAnh</code> giữ như PM mới).
                @if($preview && !empty($preview['meta']['bao_cao_i_nguon']))
                    <br /><strong>Báo cái I nguồn:</strong> {{ $preview['meta']['bao_cao_i_nguon'] }} dòng
                    @if(isset($preview['meta']['bao_cao_i_dich']))
                        · đích ({{ $preview['meta']['ma_kh_dich'] ?? '' }}): {{ $preview['meta']['bao_cao_i_dich'] }} dòng
                    @endif
                @endif
                <strong>Chưa</strong> copy <code>NguoiLXHS_GiayTo</code>, <code>NguoiLX_GPLX</code>.
                Sau đồng bộ có thể dùng <strong>Cập nhật KQĐT bản cũ</strong> nếu cần.
            </div>
            <div class="alert alert-warning mb-0">
                <strong>Lưu ý:</strong> Nếu bên <strong>Khóa học đích</strong> chưa có khóa học cần đưa qua thì cần <strong>tạo khóa học trên phần mềm cũ trước</strong>, sau đó quay lại chọn khóa đích và xem trước.
                DB đích phải là <strong>cùng catalog</strong> với cấu hình <code>DB_DATABASE_2</code> / tool COPY — nếu COPY từng báo <em>truncated</em>, chạy một lần script
                <code>PUBLISH_DATA_GPLX/sql/01-alter-ban-cu-column-widths-moto.sql</code> trên DB đó (Laravel cũng tự cắt chuỗi dài khi ghi).
            </div>
        </div>
    </div>

    <div class="card card-panel mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Đồng bộ học viên qua bản cũ</span>
            <a href="{{ route('pmgplxmoto.dm.hoc-vien.index') }}" class="btn btn-sm btn-outline-secondary">← Danh sách học viên</a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('pmgplxmoto.dm.hoc-vien.dong-bo.form') }}" id="form-chon-khoa">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-5">
                        <label for="ma_kh_nguon">Mã khóa học nguồn <span class="text-muted">(phần mềm mới · hạng A)</span></label>
                        @if (config('app.debug'))
                            <div class="small text-muted mb-1">DB: <code>{{ config('database.connections.sqlsrv_moto.database') }}</code></div>
                        @endif
                        @if ($khoaHocsNguon->isEmpty())
                            <div class="alert alert-warning py-2 small mb-2">
                                Không có khóa <strong>hạng A (mô tô)</strong> trên <code>{{ config('database.connections.sqlsrv_moto.database') }}</code>.
                                Nếu chỉ thấy khóa B/C trước đây, DB bản mới có thể đang trỏ nhầm backup <strong>ô tô</strong> — kiểm tra <code>DB_DATABASE</code> / restore <code>GPLX_MOTO_BAN_MOI</code>.
                            </div>
                        @endif
                        <select name="ma_kh_nguon" id="ma_kh_nguon" class="form-control form-control-sm" required>
                            <option value=""></option>
                            @foreach ($khoaHocsNguon as $kh)
                                @php
                                    $tenKhNguon = trim((string) ($kh->TenKH ?? ''));
                                    $hang = trim((string) ($kh->HangGPLX ?? ''));
                                    $labelKhNguon = ($tenKhNguon !== '' ? $tenKhNguon : $kh->MaKH)
                                        .' ('.$kh->MaKH.')'
                                        .($hang !== '' ? ' · '.$hang : '');
                                @endphp
                                <option value="{{ $kh->MaKH }}" data-khoa-label="{{ $labelKhNguon }}" @selected($maKhNguon === $kh->MaKH)>
                                    {{ $labelKhNguon }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="ma_kh_dich">Khóa học đích <span class="text-muted">(phần mềm cũ)</span></label>
                        <select name="ma_kh_dich" id="ma_kh_dich" class="form-control form-control-sm">
                            <option value=""></option>
                            @foreach ($khoaHocsDich as $kh)
                                @php
                                    $tenKhDich = trim((string) ($kh->TenKH ?? ''));
                                    $labelKhDich = ($tenKhDich !== '' ? $tenKhDich : $kh->MaKH).' ('.$kh->MaKH.')';
                                @endphp
                                <option value="{{ $kh->MaKH }}" data-khoa-label="{{ $labelKhDich }}" @selected($maKhDich === $kh->MaKH)>
                                    {{ $labelKhDich }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary btn-block">Xem trước</button>
                    </div>
                </div>
            </form>

            @if ($meta)
                <div class="small text-muted mt-2 mb-0">
                    Khóa nguồn: <strong>{{ $meta['ten_kh_nguon'] ?? $meta['ma_kh_nguon'] }}</strong>
                    ({{ number_format((int) ($meta['count'] ?? 0)) }} học viên)
                    @if ($meta['ma_kh_dich'] ?? null)
                        — Khóa đích: <strong>{{ ($meta['ten_kh_dich'] ?? '') !== '' ? $meta['ten_kh_dich'] : $meta['ma_kh_dich'] }} ({{ $meta['ma_kh_dich'] }})</strong>
                        @if (($meta['ma_dk_prefix_nguon'] ?? null) && ($meta['ma_dk_prefix_dich'] ?? null))
                            — Mã ĐK dự kiến: thay <code>{{ $meta['ma_dk_prefix_nguon'] }}</code> → <code>{{ $meta['ma_dk_prefix_dich'] }}</code>
                        @endif
                    @else
                        — <em>Chọn khóa đích để xem mã ĐK dự kiến.</em>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if ($maKhNguon !== '')
        @if ($maKhDich !== '' && $conflictCount > 0)
            <div class="alert alert-warning mb-3">
                <strong>Lưu ý:</strong> {{ number_format($conflictCount) }} mã ĐK đã tồn tại trên bản cũ (dòng vàng bảng bên phải) — sẽ được <strong>cập nhật</strong> khi đồng bộ.
            </div>
        @endif

        @if ($maKhDich !== '' && $ttXuLyHighCount > 0)
            <div class="alert alert-warning mb-3" id="dong-bo-tt-xu-ly-canh-bao">
                <strong>TT xử lý hồ sơ (<code>NguoiLX_HoSo.TT_XuLy</code>):</strong>
                {{ number_format($ttXuLyHighCount) }} học viên có mã <strong>lớn hơn 04</strong> trên phần mềm mới (tên trạng thái lấy từ <code>DM_TrangThai</code>).
                PM cũ thường chỉ cần giai đoạn đầu — trước khi đồng bộ, chọn <strong>mặc định cho cả khóa</strong> hoặc từng dòng cam ở cột <strong>TT ghi bản cũ</strong>.
            </div>
        @endif

        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card card-panel h-100">
                    <div class="card-header bg-light">
                        Học viên — phần mềm mới
                        @if ($meta)
                            <span class="badge badge-secondary ml-1">{{ number_format((int) ($meta['count'] ?? 0)) }}</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive hoc-vien-dong-bo-preview hoc-vien-dong-bo-source-scroll">
                            <table class="table table-sm table-bordered table-striped table-hover table-data mb-0 hoc-vien-dong-bo-table-nguon">
                                <colgroup>
                                    <col class="col-stt">
                                    <col class="col-ma-dk">
                                    <col class="col-ho-ten">
                                    <col class="col-so-gplx">
                                    <col class="col-don-vi-cap">
                                    <col class="col-hang">
                                    <col class="col-tt-xu-ly">
                                    <col class="col-anh">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="col-stt">STT</th>
                                        <th class="col-ma-dk">Mã ĐK</th>
                                        <th class="col-ho-ten">Họ và tên</th>
                                        <th class="col-so-gplx">Số GPLX</th>
                                        <th class="col-don-vi-cap">Đơn vị cấp</th>
                                        <th class="col-hang">Hạng</th>
                                        <th class="col-tt-xu-ly" title="NguoiLX_HoSo.TT_XuLy — DM_TrangThai">TT xử lý</th>
                                        <th class="col-anh">Ảnh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sourceRows as $index => $row)
                                        <tr @class(['hoc-vien-dong-bo-row-tt-xu-ly' => ! empty($row['tt_xu_ly_canh_bao'])])>
                                            <td class="col-stt">{{ $index + 1 }}</td>
                                            <td class="col-ma-dk"><code>{{ $row['ma_dk'] }}</code></td>
                                            <td class="col-ho-ten" title="{{ $row['ho_ten'] }}">{{ $row['ho_ten'] }}</td>
                                            <td class="col-so-gplx" title="{{ $row['so_gplx_da_co'] ?? '' }}">{{ $row['so_gplx_da_co'] ?: '—' }}</td>
                                            <td class="col-don-vi-cap" title="{{ $row['don_vi_cap_hien_thi'] ?? ($row['don_vi_cap_gplx_da_co'] ?? '') }}">{{ $row['don_vi_cap_hien_thi'] ?? ($row['don_vi_cap_gplx_da_co'] ?: '—') }}</td>
                                            <td class="col-hang" title="{{ $row['hang_gplx_hien_thi'] ?? ($row['hang_gplx'] ?? '') }}">{{ $row['hang_gplx_hien_thi'] ?? ($row['hang_gplx'] ?: '—') }}</td>
                                            <td class="col-tt-xu-ly" title="{{ $row['tt_xu_ly_hien_thi'] ?? '' }}">
                                                {{ $row['tt_xu_ly_hien_thi'] ?? '—' }}
                                                @if (! empty($row['tt_xu_ly_canh_bao']))
                                                    <span class="badge badge-warning d-block mt-1" style="font-size: 0.7rem;">&gt; 04</span>
                                                @endif
                                            </td>
                                            <td class="col-anh">
                                                @include('PMGPLXMOTO.danh-muc._dong-bo-anh-thumb', [
                                                    'anhUrl' => $row['anh_url'] ?? null,
                                                    'duongDanAnh' => $row['duong_danh_anh'] ?? '',
                                                    'hoTen' => $row['ho_ten'] ?? '',
                                                    'maDk' => $row['ma_dk'] ?? '',
                                                ])
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">Không có học viên trong khóa nguồn</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card card-panel h-100">
                    <div class="card-header bg-light">
                        Dự kiến đưa sang phần mềm cũ
                        @if ($maKhDich !== '' && $meta)
                            <span class="badge badge-info ml-1">{{ number_format((int) ($meta['count'] ?? 0)) }}</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @if ($maKhDich === '')
                            <div class="p-3 text-muted">Chọn <strong>mã khóa học đích</strong> và bấm <strong>Xem trước</strong> để hiện mã ĐK dự kiến (thay tiền tố mã khóa).</div>
                        @else
                            <div class="table-responsive hoc-vien-dong-bo-planned-scroll hoc-vien-dong-bo-preview">
                                <table class="table table-sm table-bordered table-striped table-hover table-data mb-0 hoc-vien-dong-bo-table-dich">
                                    <colgroup>
                                        <col class="col-stt">
                                        <col class="col-ma-dk">
                                        <col class="col-ho-ten">
                                        <col class="col-so-gplx">
                                        <col class="col-ngay-tt-gplx">
                                        <col class="col-dvc-hien-tai">
                                        <col class="col-dvc-ban-cu">
                                        <col class="col-hang">
                                        <col class="col-tt-xu-ly">
                                        <col class="col-anh">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="col-stt">STT</th>
                                            <th class="col-ma-dk col-ma-dk-du-kien">Mã ĐK dự kiến</th>
                                            <th class="col-ho-ten">Họ và tên</th>
                                            <th class="col-so-gplx">Số GPLX</th>
                                            <th class="col-ngay-tt-gplx">Ngày TT GPLX</th>
                                            <th class="col-dvc-hien-tai">ĐVC hiện tại</th>
                                            <th class="col-dvc-ban-cu">ĐVC bản cũ</th>
                                            <th class="col-hang">Hạng</th>
                                            <th class="col-tt-xu-ly">TT ghi bản cũ</th>
                                            <th class="col-anh">Ảnh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($plannedRows as $index => $row)
                                            @php
                                                $maDkNguonRow = (string) ($row['ma_dk_nguon'] ?? '');
                                                $ttChoiceRow = $ttXuLyPerHv[$maDkNguonRow] ?? '';
                                                $srcAnhRow = $maDkNguonRow !== '' ? ($sourceByMaDk[$maDkNguonRow] ?? null) : null;
                                                $anhUrlNguon = is_array($srcAnhRow) ? ($srcAnhRow['anh_url'] ?? null) : ($row['anh_url'] ?? null);
                                                $duongDanAnhNguon = is_array($srcAnhRow)
                                                    ? trim((string) ($srcAnhRow['duong_danh_anh'] ?? ''))
                                                    : trim((string) ($row['duong_danh_anh'] ?? ''));
                                            @endphp
                                            <tr @class([
                                                'hoc-vien-dong-bo-row-conflict' => ! empty($row['ton_tai']),
                                                'hoc-vien-dong-bo-row-dvc-doi' => ! empty($row['don_vi_cap_doi']),
                                                'hoc-vien-dong-bo-row-tt-xu-ly' => ! empty($row['tt_xu_ly_canh_bao']),
                                            ])>
                                                <td class="col-stt">
                                                    @if (empty($row['ton_tai']))
                                                        <span class="text-success font-weight-bold" title="Chưa tồn tại trên bản cũ — sẽ thêm mới">+</span>
                                                    @else
                                                        <span class="text-warning font-weight-bold" title="Đã tồn tại — sẽ cập nhật">↻</span>
                                                    @endif
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="col-ma-dk col-ma-dk-du-kien">
                                                    <code @class(['text-primary' => empty($row['ton_tai']), 'text-warning' => ! empty($row['ton_tai'])])>{{ $row['ma_dk'] ?? '—' }}</code>
                                                    @if (! empty($row['ton_tai']))
                                                        <span class="badge badge-warning ml-1">Sẽ cập nhật</span>
                                                    @endif
                                                </td>
                                                <td class="col-ho-ten" title="{{ $row['ho_ten'] }}">{{ $row['ho_ten'] }}</td>
                                                <td class="col-so-gplx" title="{{ $row['so_gplx_da_co'] ?? '' }}">{{ $row['so_gplx_da_co'] }}</td>
                                                <td class="col-ngay-tt-gplx">
                                                    {{ $row['ngay_tt_gplx_da_co'] ?: '—' }}
                                                    @if (! empty($row['ngay_tt_sau_moc']))
                                                        <span class="badge badge-info ml-1" title="Sau 01/03/2025 → ĐVC bản cũ = 00">Sau mốc</span>
                                                    @endif
                                                </td>
                                                <td class="col-dvc-hien-tai" title="{{ $row['don_vi_cap_goc_hien_thi'] ?? ($row['don_vi_cap_gplx_da_co_goc'] ?? '') }}">{{ $row['don_vi_cap_goc_hien_thi'] ?? ($row['don_vi_cap_gplx_da_co_goc'] ?: '—') }}</td>
                                                <td class="col-dvc-ban-cu">
                                                    @if (! empty($row['don_vi_cap_doi']))
                                                        <span class="text-muted" title="{{ $row['don_vi_cap_goc_hien_thi'] ?? '' }}">{{ $row['don_vi_cap_goc_hien_thi'] ?? ($row['don_vi_cap_gplx_da_co_goc'] ?: ($row['dvc_tu_so_hien_thi'] ?? ($row['dvc_tu_so_gplx'] ?: '—'))) }}</span>
                                                        <span class="mx-1">→</span>
                                                        <strong class="text-primary" title="{{ $row['don_vi_cap_ban_cu_hien_thi'] ?? '' }}">{{ $row['don_vi_cap_ban_cu_hien_thi'] ?? ($row['don_vi_cap_gplx_da_co'] ?: '—') }}</strong>
                                                    @else
                                                        <span title="{{ $row['don_vi_cap_ban_cu_hien_thi'] ?? '' }}">{{ $row['don_vi_cap_ban_cu_hien_thi'] ?? ($row['don_vi_cap_gplx_da_co'] ?: '—') }}</span>
                                                    @endif
                                                </td>
                                                <td class="col-hang" title="{{ $row['hang_gplx_hien_thi'] ?? ($row['hang_gplx'] ?? '') }}">{{ $row['hang_gplx_hien_thi'] ?? ($row['hang_gplx'] ?: '—') }}</td>
                                                <td class="col-tt-xu-ly">
                                                    <span title="{{ $row['tt_xu_ly_nguon_hien_thi'] ?? '' }}">{{ $row['tt_xu_ly_nguon_hien_thi'] ?? '—' }}</span>
                                                    @if (! empty($row['tt_xu_ly_canh_bao']) && $maDkNguonRow !== '')
                                                        <select class="form-control form-control-sm dong-bo-tt-xu-ly-per-hv dong-bo-tt-xu-ly-select mt-1"
                                                                data-ma-dk="{{ $maDkNguonRow }}"
                                                                data-nguon="{{ \App\Support\PMGPLXMOTO\DongBoTtXuLy::displayCode($row['tt_xu_ly_nguon'] ?? '') }}">
                                                            <option value="keep" @selected($ttChoiceRow === '' || $ttChoiceRow === 'keep')>Giữ {{ \App\Support\PMGPLXMOTO\DongBoTtXuLy::displayCode($row['tt_xu_ly_nguon'] ?? '') }}</option>
                                                            <option value="03" @selected($ttChoiceRow === '03')>03 — đã nhận ảnh</option>
                                                            <option value="04" @selected($ttChoiceRow === '04')>04</option>
                                                        </select>
                                                    @endif
                                                </td>
                                                <td class="col-anh">
                                                    @include('PMGPLXMOTO.danh-muc._dong-bo-anh-planned-cell', [
                                                        'anhUrl' => $anhUrlNguon,
                                                        'duongDanAnh' => $duongDanAnhNguon,
                                                        'hoTen' => $row['ho_ten'] ?? '',
                                                        'maDk' => $maDkNguonRow,
                                                        'anhUrlCu' => $row['anh_url_cu'] ?? null,
                                                        'duongDanAnhCu' => $row['duong_danh_anh_cu'] ?? '',
                                                        'khacPath' => ! empty($row['duong_danh_anh_khac']),
                                                    ])
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">Không có dữ liệu</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="small text-muted px-3 py-2 border-top">
                                <strong>Đơn vị cấp</strong> và cột kết quả trên hồ sơ copy nguyên từ phần mềm mới (không đổi theo mốc 01/03/2025).
                                Cột <strong>ĐVC bản cũ</strong> = giá trị sẽ ghi <code>DonViCapGPLXDaCo</code> trên DB cũ.
                                Ảnh trên = path PM mới; nhãn <strong>cũ</strong> nếu <code>DuongDanAnh</code> trên DB bản cũ khác.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($maKhDich !== '' && count($sourceRows) > 0 && $updateCount > 0)
            <div class="card card-panel">
                <div class="card-body text-muted">
                    {{ number_format($updateCount) }} học viên đã có mã ĐK trên bản cũ — sẽ được <strong>cập nhật</strong> (kèm giấy tờ) khi đồng bộ.
                </div>
            </div>
        @endif

        @if ($canTest || $canSync)
            <div class="card card-panel">
                <div class="card-body">
                    @if ($ttXuLyHighCount > 0)
                        <div class="border rounded p-2 mb-3 bg-light" id="dong-bo-tt-xu-ly-mac-dinh">
                            <div class="small font-weight-bold mb-2">Mặc định <code>TT_XuLy</code> ghi sang bản cũ (áp dụng các dòng &gt; 04 chưa chọn riêng)</div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tt_xuly_mac_dinh" id="tt_xuly_mac_dinh_keep" value="keep"
                                       @checked($ttXuLyMacDinh === 'keep')>
                                <label class="form-check-label small" for="tt_xuly_mac_dinh_keep">Giữ nguyên như PM mới</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tt_xuly_mac_dinh" id="tt_xuly_mac_dinh_03" value="03"
                                       @checked($ttXuLyMacDinh === '03')>
                                <label class="form-check-label small" for="tt_xuly_mac_dinh_03">Ghi <code>03</code> (đã nhận ảnh)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tt_xuly_mac_dinh" id="tt_xuly_mac_dinh_04" value="04"
                                       @checked($ttXuLyMacDinh === '04')>
                                <label class="form-check-label small" for="tt_xuly_mac_dinh_04">Ghi <code>04</code></label>
                            </div>
                        </div>
                    @endif
                    <div class="d-flex flex-wrap align-items-center">
                        @if ($canTest)
                            <form method="POST" action="{{ route('pmgplxmoto.dm.hoc-vien.dong-bo.test-mot') }}" class="mb-2 mr-3 d-flex flex-wrap align-items-end"
                                  id="form-test-hoc-vien"
                                  onsubmit="return dongBoSubmitWithTtXuLy(this) && confirmDongBoTestMot();">
                                @csrf
                                <input type="hidden" name="ma_kh_nguon" value="{{ $maKhNguon }}">
                                <input type="hidden" name="ma_kh_dich" value="{{ $maKhDich }}">
                                <div class="form-group mb-0 mr-2">
                                    <label for="ma_dk_nguon_test" class="small font-weight-bold mb-1">Mã ĐK nguồn (test)</label>
                                    <input type="text" name="ma_dk_nguon" id="ma_dk_nguon_test"
                                           class="form-control form-control-sm"
                                           style="min-width: 280px;"
                                           value="{{ $defaultMaDkNguon }}"
                                           placeholder="vd. 44016-20260718-000001"
                                           autocomplete="off"
                                           required>
                                </div>
                                <button type="submit" class="btn btn-outline-primary mb-0">
                                    Test học viên
                                </button>
                            </form>
                        @endif

                        @if ($canSync)
                            <form method="POST" action="{{ route('pmgplxmoto.dm.hoc-vien.dong-bo.store') }}" class="mb-2 mr-3" id="form-dong-bo-store"
                                  onsubmit="return dongBoSubmitWithTtXuLy(this) && confirm('Đồng bộ {{ number_format($syncableCount) }} học viên từ khóa {{ $maKhNguon }} sang khóa {{ $maKhDich }} trên bản cũ?{{ $updateCount > 0 ? '\n'.number_format($updateCount).' học viên sẽ được cập nhật.' : '' }}{{ $ttXuLyHighCount > 0 ? '\n'.number_format($ttXuLyHighCount).' HV có TT_XuLy > 04 — kiểm tra lựa chọn ghi bản cũ.' : '' }}');">
                                @csrf
                                <input type="hidden" name="ma_kh_nguon" value="{{ $maKhNguon }}">
                                <input type="hidden" name="ma_kh_dich" value="{{ $maKhDich }}">
                                <button type="submit" class="btn btn-info">
                                    Xác nhận đồng bộ sang bản cũ
                                </button>
                            </form>
                        @endif
                    </div>

                    @if ($canTest)
                        <div class="small text-muted mt-1 w-100">
                            Nhập <strong>mã ĐK trên phần mềm mới</strong> (cột trái).
                            Sau test dùng <strong>Khôi phục</strong> để xóa trên bản cũ nếu cần.
                        </div>
                    @elseif ($canSync)
                        <div class="small text-muted mt-1">
                            Mã ĐK: thay tiền tố khóa, giữ phần ngày-thứ tự (vd. <code>45003-20260831-000002</code>).
                            @if ($updateCount > 0)
                                Dòng vàng = đã có trên bản cũ, sẽ cập nhật khi đồng bộ.
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <div class="modal fade" id="dongBoAnhLonModal" tabindex="-1" role="dialog" aria-labelledby="dongBoAnhLonLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="dongBoAnhLonLabel">Ảnh chân dung</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="dongBoAnhLonImg" src="" alt="">
                    <p class="small text-muted mt-2 mb-0 text-left">
                        <span id="dongBoAnhLonHoTen"></span>
                        <code id="dongBoAnhLonMaDk" class="d-block mt-1"></code>
                        <span id="dongBoAnhLonPath" class="d-block mt-1 text-break"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function dongBoSubmitWithTtXuLy(form) {
        var $form = $(form);
        $form.find('input.dong-bo-tt-xu-ly-hidden').remove();
        var macDinh = $('input[name="tt_xuly_mac_dinh"]:checked').val() || 'keep';
        $form.append($('<input type="hidden" class="dong-bo-tt-xu-ly-hidden" name="tt_xuly_mac_dinh">').val(macDinh));
        $('.dong-bo-tt-xu-ly-per-hv').each(function () {
            var $sel = $(this);
            var maDk = ($sel.data('ma-dk') || '').toString();
            if (maDk === '') {
                return;
            }
            $form.append(
                $('<input type="hidden" class="dong-bo-tt-xu-ly-hidden">')
                    .attr('name', 'tt_xuly[' + maDk + ']')
                    .val($sel.val() || 'keep')
            );
        });
        return true;
    }

    function dongBoMoAnhLon(btn) {
        var $btn = $(btn);
        var url = $btn.data('anh-url') || '';
        var title = $btn.data('anh-title') || 'Ảnh chân dung';
        var maDk = $btn.data('anh-ma-dk') || '';
        var path = $btn.data('anh-path') || '';
        $('#dongBoAnhLonLabel').text(title);
        $('#dongBoAnhLonHoTen').text(title !== '' ? title : '');
        $('#dongBoAnhLonMaDk').text(maDk !== '' ? maDk : '');
        $('#dongBoAnhLonPath').text(path !== '' ? path : '');
        $('#dongBoAnhLonImg').attr('src', url);
        $('#dongBoAnhLonModal').modal('show');
    }

    var dongBoPlannedBySource = @json($plannedBySourceMaDk);
    var dongBoSourceByMaDk = @json(collect($sourceRows)->keyBy('ma_dk'));

    function confirmDongBoTestMot() {
        var $inp = $('#ma_dk_nguon_test');
        if (!$inp.length) {
            return true;
        }
        var src = ($inp.val() || '').trim();
        if (src === '') {
            alert('Nhập mã ĐK nguồn.');
            return false;
        }
        var planned = dongBoPlannedBySource[src] || {};
        var source = dongBoSourceByMaDk[src] || {};
        var hoTen = source.ho_ten || planned.ho_ten || '';
        var dst = planned.ma_dk || '';
        var msg = 'Test đồng bộ 1 học viên';
        if (hoTen) {
            msg += ': ' + hoTen;
        }
        msg += '\n' + src;
        if (dst) {
            msg += ' → ' + dst;
        }
        msg += '\n\nTiếp tục?';
        return confirm(msg);
    }

    function initKhoaHocSelect2(selector, placeholder) {
        var $el = $(selector);
        $el.select2({
            theme: 'bootstrap4',
            placeholder: placeholder,
            allowClear: true,
            width: '100%',
            dropdownParent: $el.closest('.form-group'),
            language: {
                noResults: function () { return 'Không tìm thấy khóa học'; }
            },
            templateResult: function (data) {
                if (!data.id) {
                    return data.text;
                }
                var label = $(data.element).data('khoa-label');
                return label || data.text;
            },
            templateSelection: function (data) {
                if (!data.id) {
                    return placeholder;
                }
                var label = $(data.element).data('khoa-label');
                return label || data.text;
            }
        });
    }

    initKhoaHocSelect2('#ma_kh_nguon', '— Chọn khóa nguồn —');
    initKhoaHocSelect2('#ma_kh_dich', '— Chọn khóa đích —');

    $('#ma_kh_nguon').on('change', function () {
        if ($(this).val()) {
            $('#form-chon-khoa').submit();
        }
    });
</script>
@endpush
