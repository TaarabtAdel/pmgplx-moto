<?php

use App\Support\HeThong\CayChucNang;
use App\Support\ManhlinhMoto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection(): ?string
    {
        return ManhlinhMoto::connection();
    }

    public function up(): void
    {
        $conn = $this->getConnection();
        $schema = Schema::connection($conn);
        if ($schema->hasTable('PhanQuyen')) {
            return;
        }

        $schema->create('PhanQuyen', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('MaVaiTro', 50);
            $table->string('MaChucNang', 255);
            $table->unsignedInteger('NguoiCapNhatId')->nullable();
            $table->dateTime('NgayCapNhat')->nullable();

            $table->unique(['MaVaiTro', 'MaChucNang'], 'UX_PhanQuyen_VaiTro_ChucNang');
        });

        $now = now();
        $rows = [];
        foreach (['it'] as $vaiTro) {
            foreach (CayChucNang::tatCaKhoaLa() as $khoa) {
                $rows[] = ['MaVaiTro' => $vaiTro, 'MaChucNang' => $khoa, 'NgayCapNhat' => $now];
            }
        }
        foreach (array_chunk($rows, 200) as $chunk) {
            DB::connection($conn)->table('PhanQuyen')->insert($chunk);
        }
    }

    public function down(): void
    {
        Schema::connection($this->getConnection())->dropIfExists('PhanQuyen');
    }
};
