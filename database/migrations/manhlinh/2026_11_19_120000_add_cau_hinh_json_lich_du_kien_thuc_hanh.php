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
        if (! $schema->hasTable('QldtMotoLichDuKienThucHanh')) {
            return;
        }

        $schema->table('QldtMotoLichDuKienThucHanh', function (Blueprint $table): void {
            if (! Schema::connection('sqlsrv_manhlinh_moto')->hasColumn('QldtMotoLichDuKienThucHanh', 'CauHinhJson')) {
                $table->text('CauHinhJson')->nullable();
            }
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);
        if (! $schema->hasTable('QldtMotoLichDuKienThucHanh')) {
            return;
        }

        $schema->table('QldtMotoLichDuKienThucHanh', function (Blueprint $table): void {
            if (Schema::connection('sqlsrv_manhlinh_moto')->hasColumn('QldtMotoLichDuKienThucHanh', 'CauHinhJson')) {
                $table->dropColumn('CauHinhJson');
            }
        });
    }
};