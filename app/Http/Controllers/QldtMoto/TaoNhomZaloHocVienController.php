<?php

namespace App\Http\Controllers\QldtMoto;

use App\Http\Controllers\Controller;
use App\Support\MenuQldt;
use Illuminate\View\View;

/** QLĐT mô tô → «Tạo nhóm Zalo học viên»: chỉ hướng dẫn thao tác trên Zalo. */
class TaoNhomZaloHocVienController extends Controller
{
    private const SLUG = '1-tao-nhom-zalo-hoc-vien';

    public function index(): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        return view('QldtMoto.tao-nhom-zalo-hoc-vien.index', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
        ]);
    }
}