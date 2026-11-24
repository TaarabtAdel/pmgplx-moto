<?php

use App\Support\ManhlinhMoto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kết quả «Dò thông tin» — lưu MANHLINH_MOTO, không ghi GPLX mô tô.
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
        if ($schema->hasTable('HocVienDoThongTin')) {
            return;
        }

        $schema->create('HocVienDoThongTin', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('MaDK', 50);
            $table->string('MaKhoaHoc', 50)->nullable();
            $table->string('HoTen', 150)->nullable();
            $table->string('SoCCCD', 20)->nullable();
            $table->date('NgaySinh')->nullable();
            $table->string('NoiThuongTru', 300)->nullable();
            $table->date('NgayCapCCCD')->nullable();
            $table->string('SoGPLX', 30)->nullable();
            $table->date('NgayCapGPLX')->nullable();
            $table->date('NgayTrungTuyen')->nullable();
            $table->unsignedInteger('NguoiCapNhatId')->nullable();
            $table->dateTime('NgayTao')->nullable();
            $table->dateTime('NgayCapNhat')->nullable();
            $table->string('KetLuan', 20)->nullable();
            $table->boolean('KhopHoTen')->nullable();
            $table->boolean('KhopSoCCCD')->nullable();
            $table->boolean('KhopNgaySinh')->nullable();
            $table->boolean('KhopNoiThuongTru')->nullable();
            $table->boolean('KhopNgayCapCCCD')->nullable();
            $table->boolean('KhopSoGPLX')->nullable();
            $table->boolean('KhopNgayCapGPLX')->nullable();
            $table->boolean('KhopNgayTrungTuyen')->nullable();

            $table->unique('MaDK', 'UX_HocVienDoThongTin_MaDK');
            $table->index('SoCCCD', 'IX_HocVienDoThongTin_SoCCCD');
            $table->index('KetLuan', 'IX_HocVienDoThongTin_KetLuan');
        });
    }

    public function down(): void
    {
        Schema::connection($this->getConnection())->dropIfExists('HocVienDoThongTin');
    }
};
