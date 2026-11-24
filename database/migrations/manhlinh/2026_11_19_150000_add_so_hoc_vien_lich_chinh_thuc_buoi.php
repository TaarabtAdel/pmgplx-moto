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
        if (! $schema->hasTable('QldtMotoLichChinhThucBuoi')) {
            return;
        }
        if ($schema->hasColumn('QldtMotoLichChinhThucBuoi', 'SoHocVien')) {
            return;
        }

        $schema->table('QldtMotoLichChinhThucBuoi', function (Blueprint $table): void {
            $table->unsignedSmallInteger('SoHocVien')->nullable();
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());
        if (! $schema->hasTable('QldtMotoLichChinhThucBuoi')) {
            return;
        }
        if (! $schema->hasColumn('QldtMotoLichChinhThucBuoi', 'SoHocVien')) {
            return;
        }

        $schema->table('QldtMotoLichChinhThucBuoi', function (Blueprint $table): void {
            $table->dropColumn('SoHocVien');
        });
    }
};