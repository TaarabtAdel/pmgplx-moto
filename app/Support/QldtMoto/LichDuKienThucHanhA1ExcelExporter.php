<?php

namespace App\Support\QldtMoto;

use App\Models\PMGPLXMOTO\GiaoVien;
use App\Models\QldtMoto\LichChinhThuc;
use App\Models\QldtMoto\LichDuKienThucHanh;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class LichDuKienThucHanhA1ExcelExporter
{
    private const TEMPLATE = 'templates/moto/lich-du-kien-thuc-hanh-a1.xlsx';

    private const ROW_TIEU_DE = 4;

    private const ROW_GV = 5;

    private const ROW_HEADER = 6;

    /** Dòng dữ liệu đầu tiên (sau header). */
    private const ROW_DATA_START = 7;

    /** Số dòng dữ liệu có sẵn trên file mẫu. */
    private const ROW_DATA_COUNT_TEMPLATE = 6;

    /** Dòng «Nơi nhận» / ngày ký (ô E, merge E:G). */
    private const ROW_FOOTER_NGAY = 13;

    public function downloadChinhThuc(LichChinhThuc $lich): BinaryFileResponse
    {
        $lich->loadMissing(['buois', 'lichDuKien:Id,CauHinhJson']);

        $snapshot = new LichDuKienThucHanh([
            'TenKhoa' => $lich->TenKhoa,
            'Hang' => $lich->Hang,
            'CauHinhJson' => $lich->lichDuKien?->CauHinhJson,
        ]);
        $snapshot->Id = $lich->Id;
        $snapshot->setRelation('buois', $lich->buois);

        return $this->download($snapshot);
    }

    public function download(LichDuKienThucHanh $lich): BinaryFileResponse
    {
        $cauHinh = CheDoThucHanhMoto::normalizeCauHinh(
            $lich->CauHinhJson ? json_decode((string) $lich->CauHinhJson, true) : null
        );
        $lich->loadMissing(['buois']);

        $buois = $lich->buois->sortBy('Ngay')->values();
        if ($buois->isEmpty()) {
            throw new \RuntimeException('Chưa có lịch đã sinh.');
        }

        $tenGv = trim($cauHinh['ten_gv']);
        if ($tenGv === '' && $cauHinh['ma_gv'] !== '') {
            $tenGv = GiaoVien::query()->find($cauHinh['ma_gv'])?->HoTen ?? $cauHinh['ma_gv'];
        }

        $tmp = storage_path('app/tmp/lich-th-a1-'.uniqid('', true).'.xlsx');
        @mkdir(dirname($tmp), 0755, true);

        $template = resource_path(self::TEMPLATE);
        if (! is_file($template)) {
            throw new \RuntimeException('Chưa có file mẫu: '.self::TEMPLATE);
        }

        $spreadsheet = IOFactory::load($template);
        $sheet = $spreadsheet->getActiveSheet();

        $hang = strtoupper(trim((string) $lich->Hang));
        $tenKhoa = trim((string) $lich->TenKhoa);
        $soKhoa = CheDoDaoTaoMoto::soKhoaTuTen($tenKhoa);
        $hauToKhoa = preg_match('/A1K\d+/i', $tenKhoa) && $soKhoa !== ''
            ? ' KHÓA '.$soKhoa
            : ($tenKhoa !== '' ? ' '.$tenKhoa : '');
        $tieuDe = 'LỊCH HỌC THỰC HÀNH LÁI XE HẠNG '.$hang.$hauToKhoa;

        $sheet->setCellValue('A'.self::ROW_TIEU_DE, $tieuDe);
        $sheet->setCellValue('A'.self::ROW_GV, 'GIÁO VIÊN HƯỚNG DẪN: '.$tenGv);

        $soDong = $buois->count();
        $chenThem = $soDong - self::ROW_DATA_COUNT_TEMPLATE;
        if ($chenThem > 0) {
            $sheet->insertNewRowBefore(self::ROW_FOOTER_NGAY, $chenThem);
        }

        $chuoiXe = CheDoThucHanhMoto::chuoiXeTap($cauHinh['xe']);
        $diaDiem = trim($cauHinh['dia_diem']);
        $hvNgayDayDu = $cauHinh['so_hv_ngay_day_du'];

        for ($i = 0; $i < $soDong; $i++) {
            $row = self::ROW_DATA_START + $i;
            $buoi = $buois[$i];
            $ngay = Carbon::parse($buoi->Ngay)->startOfDay();
            $soGio = (float) ($buoi->SoGio ?? $cauHinh['so_gio_ngay_day_du']);
            $soHv = (int) ($buoi->SoHocVien ?? $hvNgayDayDu);

            $sheet->setCellValue('A'.$row, '=IF(WEEKDAY(B'.$row.',1)=1,"CN","T"&WEEKDAY(B'.$row.',1))');
            $sheet->setCellValue('B'.$row, Date::PHPToExcel($ngay));
            $sheet->getStyle('B'.$row)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            self::setNumberCell($sheet, 'C'.$row, $soGio);
            $sheet->setCellValue('D'.$row, (string) ($buoi->NoiDung ?: $cauHinh['noi_dung']));
            $sheet->setCellValue('E'.$row, $diaDiem !== '' ? ' '.$diaDiem.' ' : ' ');
            self::setNumberCell($sheet, 'F'.$row, $soHv);
            $sheet->setCellValue('G'.$row, $chuoiXe);
        }

        if ($soDong < self::ROW_DATA_COUNT_TEMPLATE) {
            $dongCuoiMau = self::ROW_DATA_START + self::ROW_DATA_COUNT_TEMPLATE - 1;
            for ($row = self::ROW_DATA_START + $soDong; $row <= $dongCuoiMau; $row++) {
                self::xoaDongDuLieu($sheet, $row);
            }
        }

        $footerRow = self::ROW_FOOTER_NGAY + max(0, $chenThem);
        $ngayKy = $cauHinh['ngay_ky']
            ? Carbon::parse($cauHinh['ngay_ky'])
            : Carbon::now();
        $sheet->setCellValue(
            'E'.$footerRow,
            '                                                      Quảng Trị, ngày '.$ngayKy->format('d')
            .' tháng '.$ngayKy->format('m').' năm '.$ngayKy->format('Y')
        );

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tmp);

        $safe = preg_replace('/[^\p{L}\p{N}\-_]+/u', '-', $tenKhoa) ?: 'lich-th';
        $filename = 'lich-hoc-thuc-hanh-'.$safe.'-'.$lich->Id.'.xlsx';

        return response()->download($tmp, $filename)->deleteFileAfterSend(true);
    }

    private static function setNumberCell(Worksheet $sheet, string $coordinate, float $value): void
    {
        if (abs($value - round($value)) < 0.001) {
            $sheet->setCellValue($coordinate, (int) round($value));
        } else {
            $sheet->setCellValue($coordinate, $value);
        }
    }

    private static function xoaDongDuLieu(Worksheet $sheet, int $row): void
    {
        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $col) {
            $sheet->setCellValue($col.$row, null);
        }
    }
}