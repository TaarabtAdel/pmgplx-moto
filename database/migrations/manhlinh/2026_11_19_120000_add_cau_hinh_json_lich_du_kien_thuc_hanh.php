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
        if (! $schema->hasTable('QldtMotoLichDuKienThucHanh')) {
            return;
        }
        if ($schema->hasColumn('QldtMotoLichDuKienThucHanh', 'CauHinhJson')) {
            return;
        }

        $schema->table('QldtMotoLichDuKienThucHanh', function (Blueprint $table): void {
            $table->text('CauHinhJson')->nullable();
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());
        if (! $schema->hasTable('QldtMotoLichDuKienThucHanh')) {
            return;
        }
        if (! $schema->hasColumn('QldtMotoLichDuKienThucHanh', 'CauHinhJson')) {
            return;
        }

        $schema->table('QldtMotoLichDuKienThucHanh', function (Blueprint $table): void {
            $table->dropColumn('CauHinhJson');
        });
    }
};