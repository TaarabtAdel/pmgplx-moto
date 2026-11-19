<?php

namespace App\Support\QldtMoto;

use App\Models\PMGPLXMOTO\KhoaHoc;
use Carbon\Carbon;

/** Lịch LT hạng A — mẫu `4. LICH HỌC LT A1K67`. */
final class LichHocLyThuyetThucHanhA1
{
    /** @var array<string, array{nhan: string, phong_lt: string, phong_kt: string, ghi_chu_kt: string}> */
    public const LOP = [
        '01' => [
            'nhan' => 'Lớp 01',
            'phong_lt' => 'Phòng học lý thuyết 1',
            'phong_kt' => 'Phòng kỹ thuật ô tô 1',
            'ghi_chu_kt' => 'Ca 17-19h',
        ],
        '02' => [
            'nhan' => 'Lớp 02',
            'phong_lt' => 'Phòng học lý thuyết 2',
            'phong_kt' => 'Phòng kỹ thuật ô tô 2',
            'ghi_chu_kt' => 'Ca 19-21h',
        ],
        '03' => [
            'nhan' => 'Lớp 03',
            'phong_lt' => 'Phòng học lý thuyết 1',
            'phong_kt' => 'Phòng kỹ thuật ô tô 1',
            'ghi_chu_kt' => 'Ca 17-19h',
        ],
    ];

    /**
     * @return array{
     *     lop: string,
     *     so_hoc_vien: int,
     *     ngay_hoc: ?string,
     *     ngay_ky: ?string,
     *     buoi: list<array{mon: string, tong_gio: float, so_gio: float, dia_diem: string, gv: string, ghi_chu: string}>
     * }
     */
    public static function cauHinhMacDinh(?KhoaHoc $khoa, string $lop = '02'): array
    {
        $lop = self::chuanHoaLop($lop);
        $preset = self::LOP[$lop];
        $ngay = $khoa?->NgayKG ? Carbon::parse($khoa->NgayKG)->format('Y-m-d') : null;
        $soHv = (int) ($khoa?->TongSoHV ?? 0);
        if ($soHv <= 0) {
            $soHv = match ($lop) {
                '01', '02' => 80,
                default => 76,
            };
        }

        return [
            'lop' => $lop,
            'so_hoc_vien' => $soHv,
            'ngay_hoc' => $ngay,
            'ngay_ky' => $ngay,
            'buoi' => [
                [
                    'mon' => 'Pháp luật về giao thông đường bộ',
                    'tong_gio' => 7.0,
                    'so_gio' => 7.0,
                    'dia_diem' => $preset['phong_lt'],
                    'gv' => '',
                    'ghi_chu' => '',
                ],
                [
                    'mon' => 'Kỹ thuật lái xe',
                    'tong_gio' => 2.0,
                    'so_gio' => 2.0,
                    'dia_diem' => $preset['phong_kt'],
                    'gv' => '',
                    'ghi_chu' => $preset['ghi_chu_kt'],
                ],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>|null  $raw
     * @return array{
     *     lop: string,
     *     so_hoc_vien: int,
     *     ngay_hoc: ?string,
     *     ngay_ky: ?string,
     *     buoi: list<array{mon: string, tong_gio: float, so_gio: float, dia_diem: string, gv: string, ghi_chu: string}>
     * }
     */
    public static function normalizeCauHinh(?array $raw, ?KhoaHoc $khoa): array
    {
        $base = self::cauHinhMacDinh($khoa, (string) ($raw['lop'] ?? '02'));
        $raw = is_array($raw) ? $raw : [];

        $buoi = [];
        foreach (range(0, 1) as $i) {
            $b = is_array($raw['buoi'][$i] ?? null) ? $raw['buoi'][$i] : [];
            $mac = $base['buoi'][$i];
            $buoi[] = [
                'mon' => trim((string) ($b['mon'] ?? $mac['mon'])) ?: $mac['mon'],
                'tong_gio' => max(0.5, (float) ($b['tong_gio'] ?? $mac['tong_gio'])),
                'so_gio' => max(0.5, (float) ($b['so_gio'] ?? $mac['so_gio'])),
                'dia_diem' => trim((string) ($b['dia_diem'] ?? $mac['dia_diem'])) ?: $mac['dia_diem'],
                'gv' => trim((string) ($b['gv'] ?? $mac['gv'])),
                'ghi_chu' => trim((string) ($b['ghi_chu'] ?? $mac['ghi_chu'])),
            ];
        }

        $ngayHoc = trim((string) ($raw['ngay_hoc'] ?? ''));
        $ngayKy = trim((string) ($raw['ngay_ky'] ?? ''));

        return [
            'lop' => self::chuanHoaLop((string) ($raw['lop'] ?? $base['lop'])),
            'so_hoc_vien' => max(1, (int) ($raw['so_hoc_vien'] ?? $base['so_hoc_vien'])),
            'ngay_hoc' => self::ngayHopLe($ngayHoc) ?? $base['ngay_hoc'],
            'ngay_ky' => self::ngayHopLe($ngayKy) ?? $base['ngay_ky'],
            'buoi' => $buoi,
        ];
    }

    public static function tieuDe(KhoaHoc $khoa, string $lop, int $soHv): string
    {
        $soKhoa = CheDoDaoTaoMoto::soKhoaTuTen($khoa->TenKH);
        $hang = CheDoDaoTaoMoto::chuanHoaHang($khoa->HangGPLX ?? $khoa->HangDT);
        $lop = self::chuanHoaLop($lop);
        $soKhoaPart = $soKhoa !== '' ? ' KHÓA '.$soKhoa : '';

        return 'LỊCH HỌC LÝ THUYẾT ĐÀO TẠO LÁI XE HẠNG '.$hang.$soKhoaPart
            .' - LỚP '.str_pad($lop, 2, '0', STR_PAD_LEFT)
            .' ('.$soHv.' HỌC VIÊN)';
    }

    public static function chuanHoaLop(string $lop): string
    {
        $lop = ltrim(trim($lop), '0');
        if ($lop === '') {
            $lop = '1';
        }

        return match ($lop) {
            '1', '01' => '01',
            '2', '02' => '02',
            '3', '03' => '03',
            default => '02',
        };
    }

    private static function ngayHopLe(string $ymd): ?string
    {
        if ($ymd === '' || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $ymd)) {
            return null;
        }

        return $ymd;
    }
}