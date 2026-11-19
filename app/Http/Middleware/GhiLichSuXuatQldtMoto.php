<?php

namespace App\Http\Middleware;

use App\Support\QldtMoto\LichSuXuatGhiNhan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Sau khi xuất file QLĐT XE MÁY thành công, ghi một dòng vào QldtMotoLichSuXuatFile. */
class GhiLichSuXuatQldtMoto
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        LichSuXuatGhiNhan::tuPhanHoi($request, $response);

        return $response;
    }
}