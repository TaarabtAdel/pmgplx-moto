<?php

use App\Http\Controllers\DaoTao\NhapXmlBienBanTongHopController;
use Illuminate\Support\Facades\Route;

Route::prefix('daotao')->name('daotao.')->group(function () {
    Route::prefix('phong-dao-tao')->name('pdt.')->group(function () {
        Route::get('/cong-cu-nhap/nhap-xml-bien-ban', [NhapXmlBienBanTongHopController::class, 'create'])
            ->name('cong-cu-nhap.nhap-xml-bien-ban');
        Route::post('/cong-cu-nhap/nhap-xml-bien-ban', [NhapXmlBienBanTongHopController::class, 'store'])
            ->name('cong-cu-nhap.nhap-xml-bien-ban.store');
        Route::post('/cong-cu-nhap/nhap-xml-bien-ban/xuat-tong/bat-dau', [NhapXmlBienBanTongHopController::class, 'exportTongStart'])
            ->name('cong-cu-nhap.nhap-xml-bien-ban.export-tong.start');
        Route::post('/cong-cu-nhap/nhap-xml-bien-ban/xuat-tong/add', [NhapXmlBienBanTongHopController::class, 'exportTongAdd'])
            ->name('cong-cu-nhap.nhap-xml-bien-ban.export-tong.add');
        Route::post('/cong-cu-nhap/nhap-xml-bien-ban/xuat-tong/pdf-step', [NhapXmlBienBanTongHopController::class, 'exportTongPdfStep'])
            ->name('cong-cu-nhap.nhap-xml-bien-ban.export-tong.pdf-step');
        Route::get('/cong-cu-nhap/nhap-xml-bien-ban/xuat-tong/tai/{job}', [NhapXmlBienBanTongHopController::class, 'exportTongDownload'])
            ->where('job', '[0-9a-fA-F-]{36}')
            ->name('cong-cu-nhap.nhap-xml-bien-ban.export-tong.download');
        Route::get('/cong-cu-nhap/nhap-xml-bien-ban/{id}/xuat', [NhapXmlBienBanTongHopController::class, 'export'])
            ->whereNumber('id')
            ->name('cong-cu-nhap.nhap-xml-bien-ban.export');
    });
});