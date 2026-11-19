<?php

namespace App\Models\QldtMoto;

use Illuminate\Database\Eloquent\Model;

class LichDuKienThucHanhNgayNghi extends Model
{
    protected $connection = 'sqlsrv_manhlinh_moto';

    protected $table = 'QldtMotoLichDuKienThucHanhNgayNghi';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'LichDuKienId',
        'Ngay',
        'GhiChu',
    ];

    protected $casts = [
        'Ngay' => 'date',
    ];
}
