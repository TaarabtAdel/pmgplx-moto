<?php

namespace App\Http\Controllers\QldtMoto;

use App\Http\Controllers\Controller;
use App\Support\MenuQldt;
use Illuminate\View\View;

/** QLĐT mô tô → «Làm lịch chính thức»: chỉ trang hướng dẫn (KG/BG, không nhập PM). */
class LamLichChinhThucController extends Controller
{
    private const SLUG = '1-lam-lich-chinh-thuc';

    public function index(): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        return view('QldtMoto.lam-lich-chinh-thuc.index', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
        ]);
    }
}