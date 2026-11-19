<?php

namespace App\Http\Controllers\QldtMoto;

use App\Http\Controllers\Controller;
use App\Support\MenuQldt;
use Illuminate\View\View;

/** QLĐT mô tô → thông báo lịch thi chính thức cho HV (Zalo): chỉ hướng dẫn. */
class ThongBaoLichThiChinhThucZaloController extends Controller
{
    private const SLUG = '1-thong-bao-lich-thi-chinh-thuc-zalo';

    public function index(): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        return view('QldtMoto.thong-bao-lich-thi-chinh-thuc-zalo.index', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
        ]);
    }
}