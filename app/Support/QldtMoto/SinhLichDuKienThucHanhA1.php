<?php

namespace App\Support\QldtMoto;

use App\Models\QldtMoto\LichDuKienThucHanh;
use App\Models\QldtMoto\LichDuKienThucHanhBuoi;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/** Sinh lịch TH đơn giản: mỗi ngày một dòng (mẫu 5. LỊCH HỌC THỰC HÀNH.xlsx). */
final class SinhLichDuKienThucHanhA1
{
    /**
     * @return array{so_buoi: int, ngay_ket_thuc: ?string, canh_bao: list<string>, khoi: array<string, array<int, array{0: string, 1: string}>>}
     */
    public function sinh(LichDuKienThucHanh $lich): array
    {
        $cauHinh = CheDoThucHanhMoto::normalizeCauHinh(
            $lich->CauHinhJson ? json_decode((string) $lich->CauHinhJson, true) : null
        );

        $canhBao = [];
        if (trim($cauHinh['ten_gv']) === '') {
            $canhBao[] = 'Nhập giáo viên hướng dẫn.';

            return ['so_buoi' => 0, 'ngay_ket_thuc' => null, 'canh_bao' => $canhBao, 'khoi' => []];
        }

        $batDau = CarbonImmutable::parse($lich->NgayBatDauThucHanh)->startOfDay();
        $xeChinh = $cauHinh['xe'][0] ?? null;
        $phanBo = CheDoThucHanhMoto::phanBoNgayThucHanh(
            $cauHinh['tong_so_hv'],
            $cauHinh['so_hv_ngay_day_du'],
            $cauHinh['so_gio_ngay_day_du']
        );

        $buoiRows = [];
        foreach ($phanBo as $i => $ngayCfg) {
            $ngay = $batDau->addDays($i);
            $buoiRows[] = [
                'LichDuKienId' => $lich->Id,
                'CapThuTu' => 1,
                'Ca' => 'sang',
                'MaGv' => $cauHinh['ma_gv'] !== '' ? $cauHinh['ma_gv'] : null,
                'BienSoXe' => $xeChinh,
                'Ngay' => $ngay->format('Y-m-d'),
                'NgaySo' => $i + 1,
                'NoiDung' => $cauHinh['noi_dung'],
                'GioBatDau' => null,
                'GioKetThuc' => null,
                'SoGio' => $ngayCfg['so_gio'],
                'SoHocVien' => $ngayCfg['so_hv'],
            ];
        }

        DB::connection('sqlsrv_manhlinh_moto')->transaction(function () use ($lich, $buoiRows): void {
            LichDuKienThucHanhBuoi::query()->where('LichDuKienId', $lich->Id)->delete();
            foreach (array_chunk($buoiRows, 100) as $chunk) {
                LichDuKienThucHanhBuoi::query()->insert($chunk);
            }
            $lich->NgaySinhLich = now();
            $lich->save();
        });

        $ketThuc = $buoiRows !== [] ? end($buoiRows)['Ngay'] : null;

        return [
            'so_buoi' => count($buoiRows),
            'ngay_ket_thuc' => $ketThuc,
            'canh_bao' => $canhBao,
            'khoi' => [],
        ];
    }
}