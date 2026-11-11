@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', 'Danh sách KQĐT khóa — bản cũ')

@push('styles')
<style>
    .kqtb-dat { color: #198754; font-weight: 600; }
    .kqtb-khongdat { color: #dc3545; font-weight: 600; }
</style>
@endpush

@section('content')
    <div class="mb-3">
        <a href="{{ route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.form', array_filter(['ma_khoa_hoc' => $maKhoaHoc])) }}" class="btn btn-sm btn-outline-info">Cập nhật KQĐT</a>
        <a href="{{ route('pmgplxmoto.dm.hoc-vien.dong-bo.form') }}" class="btn btn-sm btn-outline-secondary ml-1">Đồng bộ học viên</a>
    </div>

    <div class="card card-panel mb-3">
        <div class="card-header bg-light">Danh sách kết quả khóa học (bản cũ)</div>
        <div class="card-body">
            <p class="text-muted small mb-3">Tương đương <strong>DanhSachKetQuaKhoaHoc</strong> trong Publish KQTN A1.</p>

            <form method="GET" action="{{ route('pmgplxmoto.dm.hoc-vien.ket-qua-khoa-hoc.form') }}" class="form-inline flex-wrap mb-3">
                <label class="mr-2 mb-2">Mã khóa học:</label>
                <input type="text" name="ma_khoa_hoc" class="form-control form-control-sm mr-3 mb-2" style="min-width:14rem"
                       value="{{ $maKhoaHoc }}" required>
                <label class="mr-2 mb-2">Kết quả:</label>
                <select name="ket_qua" class="form-control form-control-sm mr-3 mb-2">
                    <option value="" @selected($ketQua === '')>Tất cả</option>
                    <option value="DAT" @selected($ketQua === 'DAT')>Đạt</option>
                    <option value="KHONGDAT" @selected($ketQua === 'KHONGDAT')>Không đạt</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary mb-2">Tìm kiếm</button>
            </form>

            @if (! empty($meta['ten_khoa_hoc']))
                <p class="mb-2">Tên khóa học: <strong class="text-primary">{{ $meta['ten_khoa_hoc'] }}</strong></p>
            @endif

            @if ($maKhoaHoc !== '')
                <div class="d-flex flex-wrap mb-3 small">
                    <span class="mr-4">Tổng học viên: <strong>{{ number_format($meta['tong'] ?? 0) }}</strong></span>
                    <span class="mr-4 kqtb-dat">Đạt: <strong>{{ number_format($meta['dat'] ?? 0) }}</strong></span>
                    <span class="kqtb-khongdat">Không đạt: <strong>{{ number_format($meta['khong_dat'] ?? 0) }}</strong></span>
                </div>
            @endif

            @if ($maKhoaHoc !== '' && count($rows) > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>STT</th>
                            <th>Mã ĐK</th>
                            <th>Số BD</th>
                            <th>Họ và tên</th>
                            <th>Ngày sinh</th>
                            <th>CCCD</th>
                            <th>Hạng</th>
                            <th>TG bắt đầu</th>
                            <th>TG kết thúc</th>
                            <th>Kết quả</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <td class="text-center">{{ $row['stt'] }}</td>
                                <td><code class="small">{{ $row['ma_dk'] }}</code></td>
                                <td class="text-center">{{ $row['so_bd'] }}</td>
                                <td>{{ $row['ho_ten'] }}</td>
                                <td class="text-center">{{ $row['ngay_sinh'] }}</td>
                                <td>{{ $row['so_cmt'] }}</td>
                                <td class="text-center">{{ $row['hang_dao_tao'] }}</td>
                                <td class="text-center">{{ $row['tg_bat_dau_text'] }}</td>
                                <td class="text-center">{{ $row['tg_ket_thuc_text'] }}</td>
                                <td class="text-center">
                                    <span class="kqtb-{{ $row['ket_qua_css'] }}">{{ $row['ket_qua_text'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif ($maKhoaHoc !== '')
                <p class="text-muted mb-0">Không có dữ liệu phù hợp.</p>
            @endif
        </div>
    </div>
@endsection
