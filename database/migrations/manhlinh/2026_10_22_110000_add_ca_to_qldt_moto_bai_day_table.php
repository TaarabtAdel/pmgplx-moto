<?php

use App\Support\ManhlinhMoto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Bài dạy tách theo ca (sáng / chiều) trong từng hạng. */
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
        $db = DB::connection($conn);

        if (! $schema->hasColumn('QldtMotoBaiDay', 'Ca')) {
            $schema->table('QldtMotoBaiDay', function (Blueprint $table) {
                $table->string('Ca', 10)->default('sang');
            });
        }

        $db->statement(
            "IF EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'IX_QldtMotoBaiDay_Hang_NgaySo' AND object_id = OBJECT_ID(N'dbo.QldtMotoBaiDay'))
                DROP INDEX [IX_QldtMotoBaiDay_Hang_NgaySo] ON [QldtMotoBaiDay]"
        );
        $db->statement(
            "IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'IX_QldtMotoBaiDay_Hang_Ca_ThuTu' AND object_id = OBJECT_ID(N'dbo.QldtMotoBaiDay'))
                CREATE INDEX [IX_QldtMotoBaiDay_Hang_Ca_ThuTu] ON [QldtMotoBaiDay]([Hang], [Ca], [ThuTu])"
        );
    }

    public function down(): void
    {
        $conn = $this->getConnection();
        $schema = Schema::connection($conn);
        $db = DB::connection($conn);

        $db->statement(
            "IF EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'IX_QldtMotoBaiDay_Hang_Ca_ThuTu' AND object_id = OBJECT_ID(N'dbo.QldtMotoBaiDay'))
                DROP INDEX [IX_QldtMotoBaiDay_Hang_Ca_ThuTu] ON [QldtMotoBaiDay]"
        );
        if ($schema->hasColumn('QldtMotoBaiDay', 'Ca')) {
            // Cột có default constraint — SQL Server cần xoá constraint trước.
            $db->statement(
                "DECLARE @c sysname = (SELECT d.name FROM sys.default_constraints d
                    JOIN sys.columns c ON c.default_object_id = d.object_id
                    WHERE d.parent_object_id = OBJECT_ID(N'dbo.QldtMotoBaiDay') AND c.name = N'Ca');
                 IF @c IS NOT NULL EXEC(N'ALTER TABLE [QldtMotoBaiDay] DROP CONSTRAINT [' + @c + N']');"
            );
            $schema->table('QldtMotoBaiDay', function (Blueprint $table) {
                $table->dropColumn('Ca');
            });
        }
        $db->statement(
            'CREATE INDEX [IX_QldtMotoBaiDay_Hang_NgaySo] ON [QldtMotoBaiDay]([Hang], [NgaySo], [ThuTu])'
        );
    }
};
