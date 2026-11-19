<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sqlsrv_manhlinh_moto';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);
        if (! $schema->hasTable('QldtMotoLichDuKienThucHanhBuoi')) {
            return;
        }

        $schema->table('QldtMotoLichDuKienThucHanhBuoi', function (Blueprint $table): void {
            if (! Schema::connection('sqlsrv_manhlinh_moto')->hasColumn('QldtMotoLichDuKienThucHanhBuoi', 'SoHocVien')) {
                $table->unsignedSmallInteger('SoHocVien')->nullable();
            }
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);
        if (! $schema->hasTable('QldtMotoLichDuKienThucHanhBuoi')) {
            return;
        }

        $schema->table('QldtMotoLichDuKienThucHanhBuoi', function (Blueprint $table): void {
            if (Schema::connection('sqlsrv_manhlinh_moto')->hasColumn('QldtMotoLichDuKienThucHanhBuoi', 'SoHocVien')) {
                $table->dropColumn('SoHocVien');
            }
        });
    }
};