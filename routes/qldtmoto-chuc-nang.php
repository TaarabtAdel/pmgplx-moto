<?php

use App\Http\Controllers\QldtMoto\BaoCaoDangKyController;
use App\Http\Controllers\QldtMoto\BienBanXacNhanPhoBienNoiQuyController;
use App\Http\Controllers\QldtMoto\BaoCaoDeNghiSatHachController;
use App\Http\Controllers\QldtMoto\BienBanPhanCongToChamController;
use App\Http\Controllers\QldtMoto\DanhSachHocSinhLaiXeController;
use App\Http\Controllers\QldtMoto\HopDongDaoTaoLaiXeController;
use App\Http\Controllers\QldtMoto\KeHoachDaoTaoController;
use App\Http\Controllers\QldtMoto\LamLichChinhThucController;
use App\Http\Controllers\QldtMoto\LichHocLyThuyetThucHanhController;
use App\Http\Controllers\QldtMoto\LichHocLyThuyetThucHanhTungGiaoVienController;
use App\Http\Controllers\QldtMoto\QuyetDinhThanhLapLopHocController;
use App\Http\Controllers\QldtMoto\QuyetDinhThoiHocController;
use App\Http\Controllers\QldtMoto\QuyetDinhToChucKiemTraBbPhanCongController;
use App\Http\Controllers\QldtMoto\SoPhanCongGvXeTapLaiController;
use App\Http\Controllers\QldtMoto\QuyetDinhBaoLuuController;
use App\Http\Controllers\QldtMoto\QuyetDinhDieuDongXeController;
use App\Http\Controllers\QldtMoto\QuyetDinhDoiGiaoVienController;
use App\Http\Controllers\QldtMoto\QuyetDinhDoiXeController;
use App\Http\Controllers\QldtMoto\QuyetDinhHoanThanhKhoaHocController;
use App\Http\Controllers\QldtMoto\BaoCaoPhienHocLoiController;
use App\Http\Controllers\QldtMoto\QuyetDinhHuyPhienController;
use App\Http\Controllers\QldtMoto\SoTheoDoiKetQuaHocTapController;
use App\Http\Controllers\QldtMoto\SoTayGiaoVienLyThuyetController;
use App\Http\Controllers\QldtMoto\SoTayGiaoVienThucHanhController;
use App\Http\Controllers\QldtMoto\ThanhLapHoiDongXetKtmController;
use App\Http\Controllers\QldtMoto\CauHinhBaiDayController;
use App\Http\Controllers\QldtMoto\LichDuKien\ThucHanhController;
use App\Http\Controllers\QldtMoto\ThongBaoKiemTraKetThucMonController;
use App\Http\Controllers\QldtMoto\ThongBaoTapTrungThucHanhController;
use App\Http\Controllers\QldtMoto\TaoNhomZaloHocVienController;
use App\Http\Controllers\QldtMoto\TaiLieuHuongDanHocVienController;
use App\Http\Controllers\QldtMoto\ThongBaoLichThiChinhThucZaloController;
use App\Http\Controllers\QldtMoto\ThongBaoLichThiDuKienController;
use App\Http\Controllers\QldtMoto\TrangController;
use App\Http\Controllers\QldtMoto\XuatBaoCaoKtmController;
use App\Models\QldtMoto\BaiDay;
use App\Support\MenuQldt;
use Illuminate\Support\Facades\Route;

    Route::get('/1-bao-cao-dang-ky', [BaoCaoDangKyController::class, 'index'])->name('bao-cao-dang-ky.index');
    Route::post('/1-bao-cao-dang-ky/xuat', [BaoCaoDangKyController::class, 'export'])->name('bao-cao-dang-ky.export');
    Route::get('/1-bao-cao-dang-ky/khoa/{maKh}', [BaoCaoDangKyController::class, 'khoaJson'])->name('bao-cao-dang-ky.khoa');

    $slugKeHoach = '1-ke-hoach-dao-tao';
    Route::get('/'.$slugKeHoach, [KeHoachDaoTaoController::class, 'index'])->name('ke-hoach-dao-tao.index');
    Route::post('/'.$slugKeHoach.'/xuat', [KeHoachDaoTaoController::class, 'export'])->name('ke-hoach-dao-tao.export');

    $slugHopDong = '1-hop-dong-dao-tao-lai-xe-cua-tung-hoc-vien';
    Route::get('/'.$slugHopDong, [HopDongDaoTaoLaiXeController::class, 'index'])->name('hop-dong-dao-tao.index');
    Route::post('/'.$slugHopDong.'/xuat', [HopDongDaoTaoLaiXeController::class, 'export'])->name('hop-dong-dao-tao.export');

    $slugDanhSachHv = '1-danh-sach-hoc-vien-lai-xe';
    Route::get('/'.$slugDanhSachHv, [DanhSachHocSinhLaiXeController::class, 'index'])->name('danh-sach-hoc-vien-lai-xe.index');
    Route::post('/'.$slugDanhSachHv.'/xuat', [DanhSachHocSinhLaiXeController::class, 'export'])->name('danh-sach-hoc-vien-lai-xe.export');
    Route::get('/'.$slugDanhSachHv.'/khoa/{maKh}', [DanhSachHocSinhLaiXeController::class, 'khoaJson'])->name('danh-sach-hoc-vien-lai-xe.khoa');

    $slugDieuDongXe = '1-quyet-dinh-dieu-dong-xe-cua-tung-giao-vien';
    Route::get('/'.$slugDieuDongXe, [QuyetDinhDieuDongXeController::class, 'index'])->name('quyet-dinh-dieu-dong-xe.index');
    Route::post('/'.$slugDieuDongXe.'/xem-truoc', [QuyetDinhDieuDongXeController::class, 'previewStore'])->name('quyet-dinh-dieu-dong-xe.preview.store');
    Route::get('/'.$slugDieuDongXe.'/xem-truoc', [QuyetDinhDieuDongXeController::class, 'preview'])->name('quyet-dinh-dieu-dong-xe.preview');
    Route::get('/'.$slugDieuDongXe.'/huy', [QuyetDinhDieuDongXeController::class, 'cancel'])->name('quyet-dinh-dieu-dong-xe.cancel');
    Route::get('/'.$slugDieuDongXe.'/xuat-word', [QuyetDinhDieuDongXeController::class, 'export'])->name('quyet-dinh-dieu-dong-xe.export');

    $slugBcSatHach = '3-bao-cao-de-nghi-sat-hach';
    Route::get('/'.$slugBcSatHach, [BaoCaoDeNghiSatHachController::class, 'index'])->name('bao-cao-de-nghi-sat-hach.index');
    Route::post('/'.$slugBcSatHach.'/xuat', [BaoCaoDeNghiSatHachController::class, 'export'])->name('bao-cao-de-nghi-sat-hach.export');

    $slugTbTapTrung = '2-thong-bao-thoi-gian-tap-trung-hoc-thuc-hanh-lai-xe';
    Route::get('/'.$slugTbTapTrung, [ThongBaoTapTrungThucHanhController::class, 'index'])->name('thong-bao-tap-trung-thuc-hanh.index');
    Route::post('/'.$slugTbTapTrung.'/xuat', [ThongBaoTapTrungThucHanhController::class, 'export'])->name('thong-bao-tap-trung-thuc-hanh.export');

    $slugHoanThanh = '2-quyet-dinh-hoan-thanh-khoa-danh-sach-hoc-vien';
    Route::get('/'.$slugHoanThanh, [QuyetDinhHoanThanhKhoaHocController::class, 'index'])->name('quyet-dinh-hoan-thanh-khoa-hoc.index');
    Route::post('/'.$slugHoanThanh.'/xuat', [QuyetDinhHoanThanhKhoaHocController::class, 'export'])->name('quyet-dinh-hoan-thanh-khoa-hoc.export');

    $slugTbKtm = '2-thong-bao-thoi-gian-kiem-tra-ket-thuc-mon';
    Route::get('/'.$slugTbKtm, [ThongBaoKiemTraKetThucMonController::class, 'index'])->name('thong-bao-kiem-tra-ket-thuc-mon.index');
    Route::get('/'.$slugTbKtm.'/tim-hoc-vien', [ThongBaoKiemTraKetThucMonController::class, 'timHocVien'])->name('thong-bao-kiem-tra-ket-thuc-mon.tim-hoc-vien');
    Route::post('/'.$slugTbKtm.'/xuat', [ThongBaoKiemTraKetThucMonController::class, 'export'])->name('thong-bao-kiem-tra-ket-thuc-mon.export');

    $slugHoiDong = '2-quyet-dinh-thanh-lap-hoi-dong-xet-hoan-thanh';
    Route::get('/'.$slugHoiDong, [ThanhLapHoiDongXetKtmController::class, 'index'])->name('thanh-lap-hoi-dong-xet-ktm.index');
    Route::post('/'.$slugHoiDong.'/xuat-quyet-dinh', [ThanhLapHoiDongXetKtmController::class, 'exportQuyetDinh'])->name('thanh-lap-hoi-dong-xet-ktm.export-quyet-dinh');
    Route::post('/'.$slugHoiDong.'/xuat-danh-sach', [ThanhLapHoiDongXetKtmController::class, 'exportDanhSach'])->name('thanh-lap-hoi-dong-xet-ktm.export-danh-sach');
    Route::post('/'.$slugHoiDong.'/xuat-ca-hai', [ThanhLapHoiDongXetKtmController::class, 'exportCaHai'])->name('thanh-lap-hoi-dong-xet-ktm.export-ca-hai');

    $slugQdBbKtm = '2-bien-ban-hop-xet-tot-nghiep';
    Route::get('/'.$slugQdBbKtm, [QuyetDinhToChucKiemTraBbPhanCongController::class, 'index'])->name('quyet-dinh-to-chuc-ktm-bb.index');
    Route::post('/'.$slugQdBbKtm.'/xuat-quyet-dinh', [QuyetDinhToChucKiemTraBbPhanCongController::class, 'exportQuyetDinh'])->name('quyet-dinh-to-chuc-ktm-bb.export-quyet-dinh');
    Route::post('/'.$slugQdBbKtm.'/xuat-bien-ban', [QuyetDinhToChucKiemTraBbPhanCongController::class, 'exportBienBan'])->name('quyet-dinh-to-chuc-ktm-bb.export-bien-ban');
    Route::post('/'.$slugQdBbKtm.'/xuat-ca-hai', [QuyetDinhToChucKiemTraBbPhanCongController::class, 'exportCaHai'])->name('quyet-dinh-to-chuc-ktm-bb.export-ca-hai');

    $slugToCham = '2-bien-ban-phan-cong-nhiem-vu-to-cham-kiem-tra';
    Route::get('/'.$slugToCham, [BienBanPhanCongToChamController::class, 'index'])->name('bien-ban-phan-cong-to-cham.index');
    Route::post('/'.$slugToCham.'/xuat', [BienBanPhanCongToChamController::class, 'export'])->name('bien-ban-phan-cong-to-cham.export');

    $slugBaoLuu = '2-quyet-dinh-bao-luu';
    Route::get('/'.$slugBaoLuu, [QuyetDinhBaoLuuController::class, 'index'])->name('quyet-dinh-bao-luu.index');
    Route::post('/'.$slugBaoLuu.'/xuat', [QuyetDinhBaoLuuController::class, 'export'])->name('quyet-dinh-bao-luu.export');

    $slugThoiHoc = '2-quyet-dinh-cho-thoi-hoc';
    Route::get('/'.$slugThoiHoc, [QuyetDinhThoiHocController::class, 'index'])->name('quyet-dinh-cho-thoi-hoc.index');
    Route::post('/'.$slugThoiHoc.'/xuat', [QuyetDinhThoiHocController::class, 'export'])->name('quyet-dinh-cho-thoi-hoc.export');

    $slugBaoCaoPhienLoi = '2-bao-cao-phien-hoc-loi';
    Route::get('/'.$slugBaoCaoPhienLoi, [BaoCaoPhienHocLoiController::class, 'index'])->name('bao-cao-phien-hoc-loi.index');
    Route::get('/'.$slugBaoCaoPhienLoi.'/khoa/{maKh}', [BaoCaoPhienHocLoiController::class, 'khoaJson'])->name('bao-cao-phien-hoc-loi.khoa');
    Route::get('/'.$slugBaoCaoPhienLoi.'/tim-hoc-vien', [BaoCaoPhienHocLoiController::class, 'timHocVien'])->name('bao-cao-phien-hoc-loi.tim-hoc-vien');
    Route::post('/'.$slugBaoCaoPhienLoi.'/xuat-word', [BaoCaoPhienHocLoiController::class, 'exportWord'])->name('bao-cao-phien-hoc-loi.export-word');
    Route::post('/'.$slugBaoCaoPhienLoi.'/xuat-danh-sach', [BaoCaoPhienHocLoiController::class, 'exportDanhSach'])->name('bao-cao-phien-hoc-loi.export-danh-sach');
    Route::post('/'.$slugBaoCaoPhienLoi.'/xuat-ca-hai', [BaoCaoPhienHocLoiController::class, 'exportCaHai'])->name('bao-cao-phien-hoc-loi.export-ca-hai');

    $slugHuyPhien = '2-quyet-dinh-huy-phien-kem-danh-sach-huy-phien';
    Route::get('/'.$slugHuyPhien, [QuyetDinhHuyPhienController::class, 'index'])->name('quyet-dinh-huy-phien.index');
    Route::get('/'.$slugHuyPhien.'/khoa/{maKh}', [QuyetDinhHuyPhienController::class, 'khoaJson'])->name('quyet-dinh-huy-phien.khoa');
    Route::post('/'.$slugHuyPhien.'/xuat-word', [QuyetDinhHuyPhienController::class, 'exportWord'])->name('quyet-dinh-huy-phien.export-word');
    Route::post('/'.$slugHuyPhien.'/xuat-danh-sach', [QuyetDinhHuyPhienController::class, 'exportDanhSach'])->name('quyet-dinh-huy-phien.export-danh-sach');
    Route::post('/'.$slugHuyPhien.'/xuat-ca-hai', [QuyetDinhHuyPhienController::class, 'exportCaHai'])->name('quyet-dinh-huy-phien.export-ca-hai');

    $slugDoiXe = '2-quyet-dinh-doi-xe';
    Route::get('/'.$slugDoiXe, [QuyetDinhDoiXeController::class, 'index'])->name('quyet-dinh-doi-xe.index');
    Route::get('/'.$slugDoiXe.'/khoa/{maKh}', [QuyetDinhDoiXeController::class, 'khoaJson'])->name('quyet-dinh-doi-xe.khoa');
    Route::post('/'.$slugDoiXe.'/xem-truoc', [QuyetDinhDoiXeController::class, 'previewStore'])->name('quyet-dinh-doi-xe.preview.store');
    Route::get('/'.$slugDoiXe.'/xem-truoc', [QuyetDinhDoiXeController::class, 'preview'])->name('quyet-dinh-doi-xe.preview');
    Route::get('/'.$slugDoiXe.'/xuat-word', [QuyetDinhDoiXeController::class, 'export'])->name('quyet-dinh-doi-xe.export');
    Route::get('/'.$slugDoiXe.'/huy', [QuyetDinhDoiXeController::class, 'cancel'])->name('quyet-dinh-doi-xe.cancel');
    Route::get('/'.$slugDoiXe.'/lam-moi', [QuyetDinhDoiXeController::class, 'reset'])->name('quyet-dinh-doi-xe.reset');

    $slugDoiGv = '2-quyet-dinh-doi-giao-vien';
    Route::get('/'.$slugDoiGv, [QuyetDinhDoiGiaoVienController::class, 'index'])->name('quyet-dinh-doi-giao-vien.index');
    Route::get('/'.$slugDoiGv.'/khoa/{maKh}', [QuyetDinhDoiGiaoVienController::class, 'khoaJson'])->name('quyet-dinh-doi-giao-vien.khoa');
    Route::post('/'.$slugDoiGv.'/xem-truoc', [QuyetDinhDoiGiaoVienController::class, 'previewStore'])->name('quyet-dinh-doi-giao-vien.preview.store');
    Route::get('/'.$slugDoiGv.'/xem-truoc', [QuyetDinhDoiGiaoVienController::class, 'preview'])->name('quyet-dinh-doi-giao-vien.preview');
    Route::get('/'.$slugDoiGv.'/huy', [QuyetDinhDoiGiaoVienController::class, 'cancel'])->name('quyet-dinh-doi-giao-vien.cancel');
    Route::get('/'.$slugDoiGv.'/lam-moi', [QuyetDinhDoiGiaoVienController::class, 'reset'])->name('quyet-dinh-doi-giao-vien.reset');
    Route::get('/'.$slugDoiGv.'/xuat-word', [QuyetDinhDoiGiaoVienController::class, 'export'])->name('quyet-dinh-doi-giao-vien.export');

    $slugSoTheoDoiKq = '1-so-theo-doi-ket-qua-hoc-tap-cua-hoc-vien';
    Route::get('/'.$slugSoTheoDoiKq, [SoTheoDoiKetQuaHocTapController::class, 'index'])->name('so-theo-doi-ket-qua-hoc-tap.index');
    Route::post('/'.$slugSoTheoDoiKq.'/xuat', [SoTheoDoiKetQuaHocTapController::class, 'export'])->name('so-theo-doi-ket-qua-hoc-tap.export');
    Route::get('/'.$slugSoTheoDoiKq.'/khoa/{maKh}', [SoTheoDoiKetQuaHocTapController::class, 'khoaJson'])->name('so-theo-doi-ket-qua-hoc-tap.khoa');

    $slugSoTayGvLt = '1-so-tay-gv-phap-luat-giao-thong';
    Route::get('/'.$slugSoTayGvLt, [SoTayGiaoVienLyThuyetController::class, 'index'])->name('so-tay-giao-vien-ly-thuyet.index');
    Route::post('/'.$slugSoTayGvLt.'/xuat', [SoTayGiaoVienLyThuyetController::class, 'export'])->name('so-tay-giao-vien-ly-thuyet.export');
    Route::get('/'.$slugSoTayGvLt.'/khoa/{maKh}', [SoTayGiaoVienLyThuyetController::class, 'khoaJson'])->name('so-tay-giao-vien-ly-thuyet.khoa');

    $slugSoTayGvTh = '1-so-tay-gv-thuc-hanh-lai-xe';
    Route::get('/'.$slugSoTayGvTh, [SoTayGiaoVienThucHanhController::class, 'index'])->name('so-tay-giao-vien-thuc-hanh.index');
    Route::post('/'.$slugSoTayGvTh.'/xuat', [SoTayGiaoVienThucHanhController::class, 'export'])->name('so-tay-giao-vien-thuc-hanh.export');
    Route::get('/'.$slugSoTayGvTh.'/khoa/{maKh}', [SoTayGiaoVienThucHanhController::class, 'khoaJson'])->name('so-tay-giao-vien-thuc-hanh.khoa');

    $slugSoPhanCong = '1-so-phan-cong-giao-vien-xe-tham-gia-giang-day-cua-khoa';
    Route::get('/'.$slugSoPhanCong, [SoPhanCongGvXeTapLaiController::class, 'index'])->name('so-phan-cong-gv-xe-tap-lai.index');
    Route::post('/'.$slugSoPhanCong.'/xem-truoc', [SoPhanCongGvXeTapLaiController::class, 'previewStore'])->name('so-phan-cong-gv-xe-tap-lai.preview.store');
    Route::get('/'.$slugSoPhanCong.'/xem-truoc', [SoPhanCongGvXeTapLaiController::class, 'preview'])->name('so-phan-cong-gv-xe-tap-lai.preview');
    Route::get('/'.$slugSoPhanCong.'/xuat', [SoPhanCongGvXeTapLaiController::class, 'export'])->name('so-phan-cong-gv-xe-tap-lai.export');
    Route::post('/'.$slugSoPhanCong.'/nhap-phan-cong', [SoPhanCongGvXeTapLaiController::class, 'import'])->name('so-phan-cong-gv-xe-tap-lai.import');
    Route::get('/'.$slugSoPhanCong.'/huy', [SoPhanCongGvXeTapLaiController::class, 'cancel'])->name('so-phan-cong-gv-xe-tap-lai.cancel');
    Route::get('/'.$slugSoPhanCong.'/khoa/{maKh}', [SoPhanCongGvXeTapLaiController::class, 'khoaJson'])->name('so-phan-cong-gv-xe-tap-lai.khoa');

    $slugPhoBien = '1-bien-ban-xac-nhan-pho-bien-noi-quy-quy-che-chuong-trinh-dao';
    Route::get('/'.$slugPhoBien, [BienBanXacNhanPhoBienNoiQuyController::class, 'index'])->name('bien-ban-pho-bien-noi-quy.index');
    Route::post('/'.$slugPhoBien.'/xuat', [BienBanXacNhanPhoBienNoiQuyController::class, 'export'])->name('bien-ban-pho-bien-noi-quy.export');

    $slugQuyetDinh = '1-quyet-dinh-thanh-lap-lop-hoc-danh-sach-hoc-vien';
    Route::get('/'.$slugQuyetDinh, [QuyetDinhThanhLapLopHocController::class, 'index'])->name('quyet-dinh-thanh-lap-lop-hoc.index');
    Route::post('/'.$slugQuyetDinh.'/xuat', [QuyetDinhThanhLapLopHocController::class, 'export'])->name('quyet-dinh-thanh-lap-lop-hoc.export');
    Route::get('/'.$slugQuyetDinh.'/khoa/{maKh}', [QuyetDinhThanhLapLopHocController::class, 'khoaJson'])->name('quyet-dinh-thanh-lap-lop-hoc.khoa');

    $slugLamLichChinhThuc = '1-lam-lich-chinh-thuc';
    Route::get('/'.$slugLamLichChinhThuc, [LamLichChinhThucController::class, 'index'])->name('lam-lich-chinh-thuc.index');

    $slugTaoNhomZalo = '1-tao-nhom-zalo-hoc-vien';
    Route::get('/'.$slugTaoNhomZalo, [TaoNhomZaloHocVienController::class, 'index'])->name('tao-nhom-zalo-hoc-vien.index');

    $slugThongBaoLichThiDuKien = '1-thong-bao-lich-thi-du-kien';
    Route::get('/'.$slugThongBaoLichThiDuKien, [ThongBaoLichThiDuKienController::class, 'index'])->name('thong-bao-lich-thi-du-kien.index');

    $slugTaiLieuHuongDan = '1-tai-lieu-huong-dan-hoc-vien';
    Route::get('/'.$slugTaiLieuHuongDan, [TaiLieuHuongDanHocVienController::class, 'index'])->name('tai-lieu-huong-dan-hoc-vien.index');

    $slugThongBaoLichThiCtZalo = '1-thong-bao-lich-thi-chinh-thuc-zalo';
    Route::get('/'.$slugThongBaoLichThiCtZalo, [ThongBaoLichThiChinhThucZaloController::class, 'index'])->name('thong-bao-lich-thi-chinh-thuc-zalo.index');

    Route::prefix('1-lich-du-kien-ly-thuyet')->name('lich-du-kien.ly-thuyet.')->group(function () {
        Route::get('/', [LichHocLyThuyetThucHanhController::class, 'index'])->name('index');
        Route::post('/xuat-excel', [LichHocLyThuyetThucHanhController::class, 'export'])->name('export');
    });

    $slugLichTungGv = '1-lich-hoc-ly-thuyet-va-lich-hoc-thuc-hanh-cua-tung-giao-vien';
    Route::get('/'.$slugLichTungGv, [LichHocLyThuyetThucHanhTungGiaoVienController::class, 'index'])
        ->name('lich-hoc-lt-th-tung-giao-vien.index');
    Route::post('/'.$slugLichTungGv.'/xuat-excel', [LichHocLyThuyetThucHanhTungGiaoVienController::class, 'exportExcel'])
        ->name('lich-hoc-lt-th-tung-giao-vien.export-excel');

    Route::prefix('1-lich-du-kien-thuc-hanh')->name('lich-du-kien.thuc-hanh.')->group(function () {
        Route::get('/', [ThucHanhController::class, 'index'])->name('index');
        Route::get('/tao', [ThucHanhController::class, 'create'])->name('create');
        Route::post('/', [ThucHanhController::class, 'store'])->name('store');
        Route::get('/{lich}', [ThucHanhController::class, 'edit'])->whereNumber('lich')->name('edit');
        Route::get('/{lich}/xuat-excel', [ThucHanhController::class, 'exportExcel'])->whereNumber('lich')->name('export-excel');
        Route::put('/{lich}', [ThucHanhController::class, 'update'])->whereNumber('lich')->name('update');
        Route::delete('/{lich}', [ThucHanhController::class, 'destroy'])->whereNumber('lich')->name('destroy');
    });

    $slugXuatBaoCaoKtm = '2-dua-vao-file-cua-nhan-lam-danh-sach-vang-dat-truot-bien-ban';
    Route::get('/'.$slugXuatBaoCaoKtm, [XuatBaoCaoKtmController::class, 'index'])->name('xuat-bao-cao-ktm.index');
    Route::post('/'.$slugXuatBaoCaoKtm, [XuatBaoCaoKtmController::class, 'store'])->name('xuat-bao-cao-ktm.store');
    Route::get('/'.$slugXuatBaoCaoKtm.'/xem-truoc', [XuatBaoCaoKtmController::class, 'preview'])->name('xuat-bao-cao-ktm.preview');
    Route::post('/'.$slugXuatBaoCaoKtm.'/tham-so', [XuatBaoCaoKtmController::class, 'updateThamSo'])->name('xuat-bao-cao-ktm.update-tham-so');
    Route::post('/'.$slugXuatBaoCaoKtm.'/xuat/{loai}', [XuatBaoCaoKtmController::class, 'export'])->where('loai', 'bbth|chi-tiet|chung|zip')->name('xuat-bao-cao-ktm.export');
    Route::get('/'.$slugXuatBaoCaoKtm.'/huy', [XuatBaoCaoKtmController::class, 'cancel'])->name('xuat-bao-cao-ktm.cancel');

