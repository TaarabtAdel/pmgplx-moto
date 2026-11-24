<?php

use App\Support\ManhlinhMoto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection(): ?string
    {
        return ManhlinhMoto::connection();
    }

    public function up(): void
    {
        $schema = Schema::connection($this->getConnection());

        if (! $schema->hasTable('NguoiDung')) {
            $schema->create('NguoiDung', function (Blueprint $table) {
                $table->increments('Id');
                $table->string('Ten', 150);
                $table->string('SoDienThoai', 20);
                $table->string('MatKhau', 255);
                $table->dateTime('NgayTao')->nullable();
                $table->dateTime('NgayCapNhat')->nullable();

                $table->unique('SoDienThoai', 'UX_NguoiDung_SoDienThoai');
            });
        }

        if (! $schema->hasTable('NguoiDungVaiTro')) {
            $schema->create('NguoiDungVaiTro', function (Blueprint $table) {
                $table->increments('Id');
                $table->unsignedInteger('NguoiDungId');
                $table->string('MaVaiTro', 50);

                $table->unique(['NguoiDungId', 'MaVaiTro'], 'UX_NguoiDungVaiTro_NguoiDung_VaiTro');
                $table->foreign('NguoiDungId', 'FK_NguoiDungVaiTro_NguoiDung')
                    ->references('Id')
                    ->on('NguoiDung')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());
        $schema->dropIfExists('NguoiDungVaiTro');
        $schema->dropIfExists('NguoiDung');
    }
};
