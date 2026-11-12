<?php

namespace App\Support\PMGPLXMOTO;

use Illuminate\Support\Facades\Schema;

/**
 * Cột INSERT giống PUBLISH_DATA_GPLX/sql/03-copy-khoa-hoc-makh-dich.sql (và COPY_DATA_GPLX.dll).
 * Thứ tự cột giữ theo script; khi chạy chỉ lấy cột tồn tại trên cả DB nguồn và đích.
 */
class CopyBanCuColumnLists
{
    /**
     * Metadata khóa (CopyDuLieuKhoaHoc / 04-reference): copy sang đích, giữ MaKH đích + MaCSDT/MaSoGTVT đích.
     *
     * @var list<string>
     */
    public const KHOA_HOC = [
        'TenKH', 'HangGPLX', 'HangDT', 'SoQD_KhaiGiang', 'NgayQD_KhaiGiang', 'NgayKG', 'NgayBG',
        'MucTieuDT', 'NgayThi', 'NgaySH', 'TongSoHV', 'SoHVTotNghiep', 'SoHVDuocCapGPLX',
        'ThoiGianDT', 'SoNgayOnKT', 'SoNgayThucHoc', 'SoNgayNghiLe', 'TongSoNgay', 'GhiChu',
        'TrangThai', 'NguoiTao', 'NguoiSua', 'NgayTao', 'NgaySua', 'TT_Xuly',
    ];

    /** @var list<string> */
    public const BAO_CAO_I = [
        'MaBCI', 'MaCSDT', 'MaKH', 'SoBaoCao', 'NgayBaoCao', 'SoGP', 'NgayCapGP',
        'LuuLuongGP', 'SoHocSinh', 'NgayKG', 'NgayBG', 'NgayTiepNhan',
        'ThoiGianTiepNhan', 'ThoiGianDaoTao', 'LuuLuong', 'BoTriHocVienXeTap',
        'GhiChu', 'TrangThai', 'NguoiTao', 'NguoiSua', 'NgayTao', 'NgaySua',
        'SoHSCanhBao', 'TT_Xuly',
    ];

    /** @var list<string> */
    public const NGUOI_LX = [
        'MaDK', 'DonViNhanHSo', 'HoDemNLX', 'TenNLX', 'HoVaTen',
        'MaQuocTich', 'NgaySinh', 'NoiTT', 'NoiTT_MaDVHC', 'NoiTT_MaDVQL',
        'NoiCT', 'NoiCT_MaDVHC', 'NoiCT_MaDVQL', 'SoCMT', 'NgayCapCMT',
        'NoiCapCMT', 'GhiChu', 'TrangThai', 'NguoiTao', 'NguoiSua',
        'NgayTao', 'NgaySua', 'GioiTinh', 'HoVaTenIn', 'SO_CMND_CU',
    ];

    /** @var list<string> */
    public const NGUOI_LX_HO_SO = [
        'MaDK', 'SoHoSo', 'MaCSDT', 'MaSoGTVT', 'MaDVNhanHSo',
        'NgayNhanHSo', 'NguoiNhanHSo', 'NgayHenTra', 'MaLoaiHs', 'TT_XuLy',
        'DuongDanAnh', 'ChatLuongAnh', 'NgayThuNhanAnh', 'NguoiThuNhanAnh',
        'SoGPLXDaCo', 'HangGPLXDaCo', 'DonViCapGPLXDaCo', 'NoiCapGPLXDaCo',
        'NgayCapGPLXDaCo', 'NgayHHGPLXDaCo', 'NgayTTGPLXDaCo', 'DonViHocLX',
        'NamHocLX', 'HangGPLX', 'SoNamLX', 'SoKmLXAnToan', 'GiayCNSK',
        'LyDoCapDoi', 'MucDichCapDoi', 'NoiDungSH', 'MaKhoaHoc', 'HangDaoTao',
        'SoGiayCNTN', 'SoCCN', 'MaBC1', 'BC1_TuoiTS', 'BC1_ThamNien',
        'MaBC2', 'KetQuaBC2', 'MaLyDoTCBC2', 'MaKySH', 'SoBD', 'LanSH',
        'SoQDSH', 'NgayQDSH', 'KetQua_LyThuyet', 'NhanXet_LyThuyet',
        'KetQua_Hinh', 'NhanXet_Hinh', 'KetQua_Duong', 'NhanXet_Duong',
        'KetQuaSH', 'SoQDTT', 'NgayQDTT', 'NguoiKy', 'GhiChu', 'NguoiTao',
        'NguoiSua', 'NgayTao', 'NgaySua', 'SoGPLXTmp', 'NgayKTBC1', 'NguoiKTBC1',
        'NgayKTBC2', 'NguoiKTBC2', 'MaIn', 'KetQuaDoiSanhTW', 'GhiChuKQDSTW',
        'ChuKy', 'TrangThai', 'MaHTCap', 'TT_XuLy_Old', 'KQ_BC1',
        'KQ_BC1_GhiChu', 'Transfer_flag', 'VaoSoCNNSo', 'NgayVaoSoCNN',
        'XepLoaiTotNghiep', 'NgayCapCCN', 'SoQuyetDinhTN', 'NgayRaQDTN',
        'SoSoTN', 'NgayVaoSoTN', 'NgayInGiayTN', 'NamcapLandau',
        'MaTrichNgang', 'CoQuanQuanLyGPLX', 'CHON_IN_GPLX',
        'KetQuaSHM', 'NhanXet_MoPhong',
    ];

    /**
     * @return list<string>
     */
    public static function forTable(
        string $table,
        string $sourceConnection = 'sqlsrv_moto',
        string $targetConnection = 'sqlsrv_moto_old'
    ): array {
        $base = match ($table) {
            'KhoaHoc' => self::KHOA_HOC,
            'BaoCaoI' => self::BAO_CAO_I,
            'NguoiLX' => self::NGUOI_LX,
            'NguoiLX_HoSo' => self::NGUOI_LX_HO_SO,
            default => [],
        };

        if ($base === []) {
            return [];
        }

        if (! Schema::connection($sourceConnection)->hasTable($table)
            || ! Schema::connection($targetConnection)->hasTable($table)) {
            return [];
        }

        $onSource = Schema::connection($sourceConnection)->getColumnListing($table);
        $onTarget = Schema::connection($targetConnection)->getColumnListing($table);
        $allowed = array_flip(array_values(array_intersect($onSource, $onTarget)));

        return array_values(array_filter($base, static fn (string $col): bool => isset($allowed[$col])));
    }
}
