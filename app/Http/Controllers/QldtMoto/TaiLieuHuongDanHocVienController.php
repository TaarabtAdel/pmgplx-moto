<?php

namespace App\Http\Controllers\QldtMoto;

use App\Http\Controllers\Controller;
use App\Support\MenuQldt;
use Illuminate\View\View;

/** QLĐT mô tô → «Cung cấp tài liệu hướng dẫn học viên»: chỉ hướng dẫn, không chức năng PM. */
class TaiLieuHuongDanHocVienController extends Controller
{
    private const SLUG = '1-tai-lieu-huong-dan-hoc-vien';

    public function index(): View
    {
        $trang = MenuQldt::timTrang(self::SLUG);
        abort_if($trang === null, 404);

        return view('QldtMoto.tai-lieu-huong-dan-hoc-vien.index', [
            'item' => $trang['item'],
            'duongDan' => $trang['duong_dan'],
        ]);
    }
}