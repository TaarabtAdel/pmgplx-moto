<?php

namespace App\Models\QldtMoto;

use Illuminate\Database\Eloquent\Model;

/** Một cặp giáo viên sáng / chiều dùng chung một xe trong lịch dự kiến thực hành. */
class LichDuKienThucHanhCapGv extends Model
{
    protected $connection = 'sqlsrv_manhlinh_moto';

    protected $table = 'QldtMotoLichDuKienThucHanhCapGv';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'LichDuKienId',
        'ThuTu',
        'MaGvSang',
        'MaGvChieu',
        'BienSoXe',
    ];

    protected $casts = [
        'ThuTu' => 'integer',
    ];
}
