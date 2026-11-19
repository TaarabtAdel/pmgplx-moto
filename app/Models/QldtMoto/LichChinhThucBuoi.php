<?php

namespace App\Models\QldtMoto;

use Illuminate\Database\Eloquent\Model;

/** Một buổi lịch chính thức — có thể kéo đổi thứ tự bài trong cùng cặp·ca. */
class LichChinhThucBuoi extends Model
{
    protected $connection = 'sqlsrv_manhlinh_moto';

    protected $table = 'QldtMotoLichChinhThucBuoi';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'LichChinhThucId',
        'CapThuTu',
        'Ca',
        'MaGv',
        'BienSoXe',
        'Ngay',
        'NgaySo',
        'NoiDung',
        'GioBatDau',
        'GioKetThuc',
        'SoGio',
        'ThuTuBai',
    ];

    protected $casts = [
        'CapThuTu' => 'integer',
        'Ngay' => 'date',
        'NgaySo' => 'integer',
        'SoGio' => 'float',
        'ThuTuBai' => 'integer',
    ];
}
