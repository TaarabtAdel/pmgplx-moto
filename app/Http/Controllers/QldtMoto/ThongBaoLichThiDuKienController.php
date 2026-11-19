<?php

namespace App\Http\Controllers\QldtMoto;

use App\Http\Controllers\Controller;
use App\Support\MenuQldt;
use Illuminate\View\View;

/** QLĐT mô tô → «Thông báo lịch thi dự kiến»: chỉ hướng dẫn xin lịch dự kiến CSGT. */
class ThongBaoLichThiDuKienController extends Controller
{
    private const SLUG = '1-thong-bao-lich-thi-du-kien';

    public function index(): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        return view('QldtMoto.thong-bao-lich-thi-du-kien.index', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
        ]);
    }
}