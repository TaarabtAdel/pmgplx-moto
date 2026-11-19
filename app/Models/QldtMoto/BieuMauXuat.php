<?php

namespace App\Models\QldtMoto;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Danh mục biểu mẫu / loại file xuất QLĐT Ô TÔ. */
class BieuMauXuat extends Model
{
    protected $connection = 'sqlsrv_manhlinh_moto';

    protected $table = 'QldtMotoBieuMauXuat';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'Ma',
        'TenRouteExport',
        'TenBieuMau',
        'LoaiXuat',
        'ThuTu',
        'HoatDong',
    ];

    protected $casts = [
        'HoatDong' => 'boolean',
    ];

    public function lichSus(): HasMany
    {
        return $this->hasMany(LichSuXuatFile::class, 'BieuMauId', 'Id');
    }
}