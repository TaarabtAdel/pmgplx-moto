<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('sqlsrv_manhlinh_moto');
        if (! $schema->hasTable('QldtMotoLichChinhThucBuoi')) {
            return;
        }

        $schema->table('QldtMotoLichChinhThucBuoi', function (Blueprint $table) use ($schema): void {
            if (! $schema->hasColumn('QldtMotoLichChinhThucBuoi', 'SoHocVien')) {
                $table->unsignedSmallInteger('SoHocVien')->nullable();
            }
        });
    }

    public function down(): void
    {
        $schema = Schema::connection('sqlsrv_manhlinh_moto');
        if (! $schema->hasTable('QldtMotoLichChinhThucBuoi')) {
            return;
        }

        $schema->table('QldtMotoLichChinhThucBuoi', function (Blueprint $table) use ($schema): void {
            if ($schema->hasColumn('QldtMotoLichChinhThucBuoi', 'SoHocVien')) {
                $table->dropColumn('SoHocVien');
            }
        });
    }
};