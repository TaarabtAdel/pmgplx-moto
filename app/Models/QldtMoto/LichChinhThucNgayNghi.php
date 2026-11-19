<?php

namespace App\Models\QldtMoto;

use Illuminate\Database\Eloquent\Model;

class LichChinhThucNgayNghi extends Model
{
    protected $connection = 'sqlsrv_manhlinh_moto';

    protected $table = 'QldtMotoLichChinhThucNgayNghi';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'LichChinhThucId',
        'Ngay',
        'GhiChu',
    ];

    protected $casts = [
        'Ngay' => 'date',
    ];
}
