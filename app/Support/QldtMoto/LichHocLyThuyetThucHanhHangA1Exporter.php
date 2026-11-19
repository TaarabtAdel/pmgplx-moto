<?php

namespace App\Support\QldtMoto;

use App\Models\PMGPLXMOTO\KhoaHoc;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class LichHocLyThuyetThucHanhHangA1Exporter
{
    private const TEMPLATE = 'templates/moto/lich-hoc-lt-a1.xlsx';

    private const ROW_TIEU_DE = 4;

    private const ROW_DATA_START = 6;

    private const FOOTER_NGAY = 10;

    private const FOOTER_CHU_KY = 17;

    /**
     * @param  array{
     *     lop: string,
     *     so_hoc_vien: int,
     *     ngay_hoc: ?string,
     *     ngay_ky: ?string,
     *     buoi: list<array{mon: string, tong_gio: float, so_gio: float, dia_diem: string, gv: string, ghi_chu: string}>
     * }  $cauHinh
     */
    public function download(KhoaHoc $khoa, array $cauHinh, string $filePrefix = 'lich-lt-a1'): BinaryFileResponse
    {
        if ($cauHinh['ngay_hoc'] === null) {
            throw new \RuntimeException('Chọn ngày học lý thuyết.');
        }

        $template = resource_path(self::TEMPLATE);
        if (! is_file($template)) {
            throw new \RuntimeException('Chưa có file mẫu: '.self::TEMPLATE);
        }

        $spreadsheet = IOFactory::load($template);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue(
            'A'.self::ROW_TIEU_DE,
            LichHocLyThuyetThucHanhA1::tieuDe($khoa, $cauHinh['lop'], $cauHinh['so_hoc_vien'])
        );

        $ngayHoc = Carbon::parse($cauHinh['ngay_hoc'])->startOfDay();
        $row = self::ROW_DATA_START;
        foreach ($cauHinh['buoi'] as $buoi) {
            $sheet->setCellValue('A'.$row, '=IF(WEEKDAY(B'.$row.',1)=1,"CN","T"&WEEKDAY(B'.$row.',1))');
            $sheet->setCellValue('B'.$row, Date::PHPToExcel($ngayHoc));
            $sheet->getStyle('B'.$row)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            $sheet->setCellValue('C'.$row, (float) $buoi['tong_gio']);
            $sheet->setCellValue('D'.$row, (float) $buoi['so_gio']);
            $sheet->setCellValue('E'.$row, $buoi['mon']);
            $sheet->setCellValue('F'.$row, $buoi['dia_diem']);
            $sheet->setCellValue('G'.$row, $buoi['gv']);
            $sheet->setCellValue('H'.$row, $buoi['ghi_chu']);
            $row++;
        }

        $ngayKy = $cauHinh['ngay_ky']
            ? Carbon::parse($cauHinh['ngay_ky'])
            : Carbon::now();
        $sheet->setCellValue(
            'F'.self::FOOTER_NGAY,
            '                       Quảng Trị, ngày '.$ngayKy->format('d')
            .' tháng '.$ngayKy->format('m').' năm '.$ngayKy->format('Y')
        );

        $tmp = storage_path('app/tmp/lich-lt-a1-'.uniqid('', true).'.xlsx');
        @mkdir(dirname($tmp), 0755, true);
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save($tmp);

        $safe = preg_replace('/[^\p{L}\p{N}\-_]+/u', '-', (string) $khoa->TenKH) ?: 'lop';
        $filename = $filePrefix.'-'.$safe.'-lop-'.$cauHinh['lop'].'.xlsx';

        return response()->download($tmp, $filename)->deleteFileAfterSend(true);
    }
}