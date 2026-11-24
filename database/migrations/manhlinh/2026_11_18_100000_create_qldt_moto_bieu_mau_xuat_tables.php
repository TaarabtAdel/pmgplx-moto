<?php

use App\Support\ManhlinhMoto;
use App\Support\QldtMoto\BieuMauXuatDongBo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Danh mục biểu mẫu xuất + lịch sử xuất QLĐT XE MÁY.
 */
return new class extends Migration
{
    public function getConnection(): ?string
    {
        return ManhlinhMoto::connection();
    }

    public function up(): void
    {
        $schema = Schema::connection($this->getConnection());

        if (! $schema->hasTable('QldtMotoBieuMauXuat')) {
            $schema->create('QldtMotoBieuMauXuat', function (Blueprint $table) {
                $table->increments('Id');
                $table->string('Ma', 80);
                $table->string('TenRouteExport', 160)->unique('UX_QldtMotoBieuMauXuat_TenRoute');
                $table->string('TenBieuMau', 255);
                $table->string('LoaiXuat', 40)->nullable();
                $table->unsignedSmallInteger('ThuTu')->default(0);
                $table->boolean('HoatDong')->default(true);

                $table->index(['Ma', 'ThuTu'], 'IX_QldtMotoBieuMauXuat_Ma_ThuTu');
            });
        }

        if (! $schema->hasTable('QldtMotoLichSuXuatFile')) {
            $schema->create('QldtMotoLichSuXuatFile', function (Blueprint $table) {
                $table->increments('Id');
                $table->unsignedInteger('BieuMauId')->nullable();
                $table->string('TenRoute', 160);
                $table->string('TenChucNang', 255)->nullable();
                $table->string('DuongDan', 500);
                $table->string('PhuongThuc', 10);
                $table->string('TenFile', 255)->nullable();
                $table->string('LoaiFile', 20)->nullable();
                $table->string('SoCongVan', 80)->nullable();
                $table->date('NgayVanBan')->nullable();
                $table->string('MaKh', 50)->nullable();
                $table->text('ThamSoJson')->nullable();
                $table->unsignedInteger('NguoiXuatId')->nullable();
                $table->dateTime('ThoiGianXuat');
                $table->string('DiaChiIp', 45)->nullable();

                $table->index(['ThoiGianXuat'], 'IX_QldtMotoLichSuXuatFile_ThoiGian');
                $table->index(['TenRoute', 'ThoiGianXuat'], 'IX_QldtMotoLichSuXuatFile_Route_ThoiGian');
                $table->index(['NguoiXuatId', 'ThoiGianXuat'], 'IX_QldtMotoLichSuXuatFile_Nguoi_ThoiGian');
                $table->index('BieuMauId', 'IX_QldtMotoLichSuXuatFile_BieuMau');
                $table->index('SoCongVan', 'IX_QldtMotoLichSuXuatFile_SoCongVan');
                $table->index('NgayVanBan', 'IX_QldtMotoLichSuXuatFile_NgayVanBan');
                $table->index('MaKh', 'IX_QldtMotoLichSuXuatFile_MaKh');
            });
        }

        (new BieuMauXuatDongBo)->dongBoTuConfig();
    }

    public function down(): void
    {
        $conn = $this->getConnection();
        Schema::connection($conn)->dropIfExists('QldtMotoLichSuXuatFile');
        Schema::connection($conn)->dropIfExists('QldtMotoBieuMauXuat');
    }
};