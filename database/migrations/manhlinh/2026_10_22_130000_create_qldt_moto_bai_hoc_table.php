<?php

use App\Support\ManhlinhMoto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Bài học theo hạng + ca (tên + khung giờ BĐ/KT); sáng và chiều có danh sách riêng. Bài dạy từng ngày trỏ tới bài học (BaiHocId),
 * nên sửa khung giờ của bài học là mọi ngày đang gán bài đó đổi theo.
 * Dữ liệu cũ: mỗi Nội dung khác nhau trong một hạng + ca thành một bài học, giờ lấy theo dòng xuất hiện đầu tiên.
 */
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

        if (! $schema->hasTable('QldtMotoBaiHoc')) {
            $schema->create('QldtMotoBaiHoc', function (Blueprint $table) {
                $table->increments('Id');
                $table->string('Hang', 10);
                $table->string('Ca', 10);
                $table->string('Ten', 500);
                $table->string('GioBatDau', 50)->nullable();
                $table->string('GioKetThuc', 50)->nullable();
                $table->integer('ThuTu')->default(0);
                $table->dateTime('NgayTao')->nullable();
                $table->dateTime('NgayCapNhat')->nullable();
                $table->unique(['Hang', 'Ca', 'Ten'], 'UX_QldtMotoBaiHoc_Hang_Ca_Ten');
            });
        }

        if (! $schema->hasColumn('QldtMotoBaiDay', 'BaiHocId')) {
            $schema->table('QldtMotoBaiDay', function (Blueprint $table) {
                $table->unsignedInteger('BaiHocId')->nullable();
            });
        }

        if ($schema->hasColumn('QldtMotoBaiDay', 'NoiDung')) {
            $now = Carbon::now();
            $baiHocId = [];
            $thuTu = [];
            $rows = $db->table('QldtMotoBaiDay')->orderBy('Hang')->orderBy('Ca')->orderBy('ThuTu')->orderBy('Id')->get();
            foreach ($rows as $r) {
                $ten = trim((string) preg_replace('/\s+/u', ' ', (string) $r->NoiDung));
                $tab = $r->Hang.'|'.$r->Ca;
                $key = $tab.'|'.mb_strtolower($ten);
                if (! isset($baiHocId[$key])) {
                    $thuTu[$tab] = ($thuTu[$tab] ?? 0) + 1;
                    $baiHocId[$key] = $db->table('QldtMotoBaiHoc')->insertGetId([
                        'Hang' => $r->Hang,
                        'Ca' => $r->Ca,
                        'Ten' => $ten,
                        'GioBatDau' => $r->GioBatDau,
                        'GioKetThuc' => $r->GioKetThuc,
                        'ThuTu' => $thuTu[$tab],
                        'NgayTao' => $now,
                        'NgayCapNhat' => $now,
                    ]);
                }
                $db->table('QldtMotoBaiDay')->where('Id', $r->Id)->update(['BaiHocId' => $baiHocId[$key]]);
            }

            $schema->table('QldtMotoBaiDay', function (Blueprint $table) {
                $table->dropColumn(['NoiDung', 'GioBatDau', 'GioKetThuc']);
            });
        }

        $db->statement('ALTER TABLE [QldtMotoBaiDay] ALTER COLUMN [BaiHocId] INT NOT NULL');
        $db->statement(
            "IF NOT EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = N'FK_QldtMotoBaiDay_BaiHoc')
                ALTER TABLE [QldtMotoBaiDay] ADD CONSTRAINT [FK_QldtMotoBaiDay_BaiHoc] FOREIGN KEY ([BaiHocId]) REFERENCES [QldtMotoBaiHoc]([Id])"
        );
        $db->statement(
            "IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'IX_QldtMotoBaiDay_BaiHocId' AND object_id = OBJECT_ID(N'dbo.QldtMotoBaiDay'))
                CREATE INDEX [IX_QldtMotoBaiDay_BaiHocId] ON [QldtMotoBaiDay]([BaiHocId])"
        );
    }

    public function down(): void
    {
        $conn = $this->getConnection();
        $schema = Schema::connection($conn);
        $db = DB::connection($conn);

        if (! $schema->hasColumn('QldtMotoBaiDay', 'NoiDung')) {
            $schema->table('QldtMotoBaiDay', function (Blueprint $table) {
                $table->string('NoiDung', 500)->nullable();
                $table->string('GioBatDau', 50)->nullable();
                $table->string('GioKetThuc', 50)->nullable();
            });
        }
        $db->statement(
            'UPDATE d SET d.[NoiDung] = h.[Ten], d.[GioBatDau] = h.[GioBatDau], d.[GioKetThuc] = h.[GioKetThuc]
             FROM [QldtMotoBaiDay] d JOIN [QldtMotoBaiHoc] h ON h.[Id] = d.[BaiHocId]'
        );
        $db->statement("UPDATE [QldtMotoBaiDay] SET [NoiDung] = N'' WHERE [NoiDung] IS NULL");
        $db->statement('ALTER TABLE [QldtMotoBaiDay] ALTER COLUMN [NoiDung] NVARCHAR(500) NOT NULL');

        $db->statement(
            "IF EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = N'FK_QldtMotoBaiDay_BaiHoc')
                ALTER TABLE [QldtMotoBaiDay] DROP CONSTRAINT [FK_QldtMotoBaiDay_BaiHoc]"
        );
        $db->statement(
            "IF EXISTS (SELECT 1 FROM sys.indexes WHERE name = N'IX_QldtMotoBaiDay_BaiHocId' AND object_id = OBJECT_ID(N'dbo.QldtMotoBaiDay'))
                DROP INDEX [IX_QldtMotoBaiDay_BaiHocId] ON [QldtMotoBaiDay]"
        );
        if ($schema->hasColumn('QldtMotoBaiDay', 'BaiHocId')) {
            $schema->table('QldtMotoBaiDay', function (Blueprint $table) {
                $table->dropColumn('BaiHocId');
            });
        }
        $schema->dropIfExists('QldtMotoBaiHoc');
    }
};
