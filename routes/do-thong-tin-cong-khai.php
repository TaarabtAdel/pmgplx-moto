<?php

use App\Http\Controllers\PMGPLXMOTO\DoThongTinHocVienController;
use Illuminate\Support\Facades\Route;

Route::get('/dt/{token}', [DoThongTinHocVienController::class, 'dienThoai'])
    ->where('token', '[A-Za-z0-9]{32}')
    ->name('do-thong-tin.dien-thoai');

Route::post('/dt/{token}/ocr', [DoThongTinHocVienController::class, 'ocrDienThoai'])
    ->where('token', '[A-Za-z0-9]{32}')
    ->middleware('throttle:30,1')
    ->name('do-thong-tin.dien-thoai.ocr');
