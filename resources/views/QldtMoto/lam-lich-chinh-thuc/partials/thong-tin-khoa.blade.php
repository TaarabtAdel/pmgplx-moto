@php
    $khoa = $khoa ?? null;
@endphp

<div class="alert alert-secondary border mb-3">
    <p class="small mb-2">
        <strong>Chỉ ghi / in lịch ra file Excel</strong> (mẫu A1-K67) — <strong class="text-danger">không nhập lịch vào PMGPLX</strong>
        (<code>KhoaHoc_GiaoVien</code> / <code>KhoaHoc_XeTap</code>).
    </p>
    @if ($khoa)
        <div class="row text-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="small text-muted">Khoá (PMGPLX-MOTO)</div>
                <div class="h5 mb-0">{{ $khoa->TenKH }}</div>
                <div class="small text-muted"><code>{{ $khoa->MaKH }}</code></div>
            </div>
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="small text-muted">Ngày khai giảng</div>
                <div class="h4 mb-0 text-primary">{{ $khoa->NgayKG?->format('d/m/Y') ?? '—' }}</div>
                <div class="small text-muted">Cột Ngày KG (danh mục khoá)</div>
            </div>
            <div class="col-md-4">
                <div class="small text-muted">Ngày bế giảng</div>
                <div class="h4 mb-0 text-success">{{ $khoa->NgayBG?->format('d/m/Y') ?? '—' }}</div>
                <div class="small text-muted">Cột Ngày BG (danh mục khoá)</div>
            </div>
        </div>
        @if ($khoa->TongSoHV)
            <p class="small text-muted mb-0 mt-2 text-center">Tổng số HV trên PM: <strong>{{ $khoa->TongSoHV }}</strong> · Hạng {{ $khoa->HangGPLX ?? $khoa->HangDT ?? '—' }}</p>
        @endif
    @else
        <p class="mb-2 text-warning small">
            Chưa liên kết khoá PM — chọn mã khoá bên dưới để hiển thị <strong>Ngày khai giảng</strong> và <strong>Ngày bế giảng</strong>.
        </p>
    @endif
</div>