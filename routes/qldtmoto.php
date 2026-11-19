<?php

use App\Http\Controllers\QldtMoto\BaoCaoDeNghiSatHachController;
use App\Http\Controllers\QldtMoto\SoTayGiaoVienLyThuyetController;
use App\Http\Controllers\QldtMoto\TrangController;
use App\Support\MenuQldt;
use Illuminate\Support\Facades\Route;

/*
| QLĐT XE MÁY — menu config/menu_qldt.php, prefix /qldt-moto.
| Màn xuất: routes/qldtmoto-chuc-nang.php (port từ ô tô, slug theo menu xe máy).
*/

Route::prefix('qldt-moto')->name('qldtmoto.')->group(function (): void {
    Route::get('/', [TrangController::class, 'index'])->name('index');

    require base_path('routes/qldtmoto-chuc-nang.php');

    $slugSoTayKtlx = '1-so-tay-gv-ky-thuat-lai-xe';
    Route::get('/'.$slugSoTayKtlx, [SoTayGiaoVienLyThuyetController::class, 'index'])
        ->defaults('menu_trang', $slugSoTayKtlx)
        ->name('so-tay-gv-ky-thuat-lai-xe.index');
    Route::post('/'.$slugSoTayKtlx.'/xuat', [SoTayGiaoVienLyThuyetController::class, 'export'])
        ->defaults('menu_trang', $slugSoTayKtlx)
        ->name('so-tay-gv-ky-thuat-lai-xe.export');
    Route::get('/'.$slugSoTayKtlx.'/khoa/{maKh}', [SoTayGiaoVienLyThuyetController::class, 'khoaJson'])
        ->defaults('menu_trang', $slugSoTayKtlx)
        ->name('so-tay-gv-ky-thuat-lai-xe.khoa');

    foreach (['3-lam-bao-cao-2-va-danh-sach', '3-ket-xuat-danh-sach-bc2'] as $slugBc2) {
        $tenRoute = MenuQldt::tenRouteTrang($slugBc2);
        Route::get('/'.$slugBc2, [BaoCaoDeNghiSatHachController::class, 'index'])
            ->defaults('menu_trang', $slugBc2)
            ->name($tenRoute.'.index');
        Route::post('/'.$slugBc2.'/xuat', [BaoCaoDeNghiSatHachController::class, 'export'])
            ->defaults('menu_trang', $slugBc2)
            ->name($tenRoute.'.export');
    }

    $slugBaoCaoGuiSo = '2-bao-cao-hoan-thanh-gui-so';
    Route::get('/'.$slugBaoCaoGuiSo, [BaoCaoDeNghiSatHachController::class, 'index'])
        ->defaults('menu_trang', $slugBaoCaoGuiSo)
        ->name(MenuQldt::tenRouteTrang($slugBaoCaoGuiSo).'.index');

    foreach (MenuQldt::tatCaTrang() as $slug) {
        Route::get('/'.$slug, [TrangController::class, 'show'])
            ->defaults('trang', $slug)
            ->name(MenuQldt::tenRouteTrang($slug));
    }
});