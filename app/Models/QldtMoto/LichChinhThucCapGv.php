<?php

namespace App\Models\QldtMoto;

use Illuminate\Database\Eloquent\Model;

class LichChinhThucCapGv extends Model
{
    protected $connection = 'sqlsrv_manhlinh_moto';

    protected $table = 'QldtMotoLichChinhThucCapGv';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'LichChinhThucId',
        'ThuTu',
        'MaGvSang',
        'MaGvChieu',
        'BienSoXe',
    ];

    protected $casts = [
        'ThuTu' => 'integer',
    ];
}
