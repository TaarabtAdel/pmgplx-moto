@extends('PMGPLXMOTO.layouts.quan-ly')

@section('title', 'Cập nhật KQĐT — bản cũ')

@push('styles')
<style>
    .kqtb-dat { color: #198754; font-weight: 600; }
    .kqtb-khongdat { color: #dc3545; font-weight: 600; }
</style>
@endpush

@section('content')
    <div class="mb-3">
        <a href="{{ route('pmgplxmoto.dm.hoc-vien.dong-bo.form') }}" class="btn btn-sm btn-outline-secondary">← Đồng bộ học viên</a>
        <a href="{{ route('pmgplxmoto.dm.hoc-vien.ket-qua-khoa-hoc.form', array_filter(['ma_khoa_hoc' => $maKhoaHoc])) }}" class="btn btn-sm btn-outline-primary ml-1">Xem lại KQĐT khóa</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card card-panel mb-3">
        <div class="card-header bg-light">Cập nhật kết quả đào tạo (phần mềm cũ)</div>
        <div class="card-body">
            <p class="text-muted small mb-3">
                Tương đương <strong>CapNhatKetQuaDaoTao</strong> (Publish KQTN A1) — cập nhật
                <code>TGBatDau</code>, <code>TGKetThuc</code>, <code>KetLuanCSDT</code> trên <strong>sqlsrv bản cũ</strong>
                sau khi đồng bộ học viên.
            </p>
            <form method="GET" action="{{ route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.form') }}" class="form-inline flex-wrap mb-3">
                <label class="mr-2 mb-2">Mã khóa học (bản cũ):</label>
                <input type="text" name="ma_khoa_hoc" class="form-control form-control-sm mr-2 mb-2" style="min-width:14rem"
                       value="{{ $maKhoaHoc }}" placeholder="VD: 45003K…" required>
                @if ($maKhNguon !== '')
                    <input type="hidden" name="ma_kh_nguon" value="{{ $maKhNguon }}">
                @endif
                <button type="submit" class="btn btn-sm btn-primary mb-2">Tải danh sách</button>
            </form>

            @if (! empty($meta['ten_khoa_hoc']))
                <div class="mb-3 font-weight-bold text-primary">{{ $meta['ten_khoa_hoc'] }}</div>
            @endif

            @if ($maKhoaHoc !== '' && count($rows) > 0)
                <form method="POST" action="{{ route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.store') }}"
                      onsubmit="return confirm('Cập nhật kết quả cho học viên đã chọn?');">
                    @csrf
                    <input type="hidden" name="ma_khoa_hoc" value="{{ $maKhoaHoc }}">
                    @if ($maKhNguon !== '')
                        <input type="hidden" name="ma_kh_nguon" value="{{ $maKhNguon }}">
                    @endif

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="form-row align-items-end">
                            <div class="form-group col-md-3 mb-2">
                                <label class="small mb-0">Thời gian bắt đầu</label>
                                <input type="date" name="tg_bat_dau" class="form-control form-control-sm" value="{{ old('tg_bat_dau') }}">
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="small mb-0">Thời gian kết thúc</label>
                                <input type="date" name="tg_ket_thuc" class="form-control form-control-sm" value="{{ old('tg_ket_thuc') }}">
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="small mb-0">Kết luận tại CSDT</label>
                                <select name="ket_luan" class="form-control form-control-sm">
                                    <option value="">— Không đổi —</option>
                                    <option value="1" @selected(old('ket_luan') === '1')>Đạt</option>
                                    <option value="0" @selected(old('ket_luan') === '0')>Không đạt</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <button type="submit" class="btn btn-info btn-sm btn-block">Cập nhật học viên đã chọn</button>
                            </div>
                        </div>
                        <p class="small text-muted mb-0">Chỉ ghi các ô bạn nhập; ô trống giữ nguyên trên DB.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th style="width:2.5rem">
                                    <input type="checkbox" title="Chọn tất cả" onclick="kqtbCheckAll(this)">
                                </th>
                                <th>STT</th>
                                <th>SBD</th>
                                <th>Mã ĐK</th>
                                <th>Họ và tên</th>
                                <th>Ngày sinh</th>
                                <th>CCCD</th>
                                <th>Hạng</th>
                                <th>Bắt đầu</th>
                                <th>Kết thúc</th>
                                <th>Kết luận</th>
                                <th>TT</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="kqtb-chon" name="ma_dk[]" value="{{ $row['ma_dk'] }}">
                                    </td>
                                    <td class="text-center">{{ $row['stt'] }}</td>
                                    <td class="text-center">{{ $row['so_bd'] }}</td>
                                    <td><code class="small">{{ $row['ma_dk'] }}</code></td>
                                    <td>{{ $row['ho_ten'] }}</td>
                                    <td class="text-center">{{ $row['ngay_sinh'] }}</td>
                                    <td>{{ $row['so_cmt'] }}</td>
                                    <td class="text-center">{{ $row['hang_dao_tao'] }}</td>
                                    <td class="text-center">{{ $row['tg_bat_dau_text'] }}</td>
                                    <td class="text-center">{{ $row['tg_ket_thuc_text'] }}</td>
                                    <td class="text-center">
                                        <span class="{{ ($row['ket_luan'] ?? null) === 1 ? 'kqtb-dat' : (($row['ket_luan'] ?? null) === 0 ? 'kqtb-khongdat' : '') }}">
                                            {{ $row['ket_luan_text'] ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @include('PMGPLXMOTOOLD.danh-muc._trang-thai-icon', ['active' => (int) ($row['trang_thai'] ?? 0) === 1])
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <form method="POST" action="{{ route('pmgplxmoto.dm.hoc-vien.cap-nhat-kqtb.copy-tu-moi') }}" class="mt-4 border-top pt-3"
                      onsubmit="return confirm('Copy KQĐT từ bản mới cho toàn bộ học viên khóa đích?');">
                    @csrf
                    <input type="hidden" name="ma_khoa_hoc" value="{{ $maKhoaHoc }}">
                    <label class="small font-weight-bold d-block mb-2">Hoặc copy từ phần mềm mới (theo map Mã ĐK đồng bộ)</label>
                    <div class="form-inline flex-wrap">
                        <label class="mr-2 mb-2">Khóa nguồn (mới):</label>
                        <select name="ma_kh_nguon" class="form-control form-control-sm mr-2 mb-2" required style="min-width:16rem">
                            <option value="">— Chọn —</option>
                            @foreach ($khoaMoi as $kh)
                                <option value="{{ $kh->MaKH }}" @selected($maKhNguon === $kh->MaKH)>{{ $kh->TenKH }} ({{ $kh->MaKH }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-success mb-2">Copy KQĐT cả khóa</button>
                    </div>
                </form>
            @elseif ($maKhoaHoc !== '')
                <p class="text-muted mb-0">Không có học viên trong khóa này trên bản cũ.</p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function kqtbCheckAll(source) {
        document.querySelectorAll('input.kqtb-chon').forEach(function (el) {
            el.checked = source.checked;
        });
    }
</script>
@endpush
