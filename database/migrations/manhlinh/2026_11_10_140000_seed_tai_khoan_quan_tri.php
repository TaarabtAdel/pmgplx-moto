<?php

use App\Support\ManhlinhMoto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection(): ?string
    {
        return ManhlinhMoto::connection();
    }

    private const SO_DIEN_THOAI = 'quantri-ml';

    /** @var list<string> */
    private const VAI_TRO = ['admin', 'it'];

    public function up(): void
    {
        $conn = $this->getConnection();
        $schema = Schema::connection($conn);
        if (! $schema->hasTable('NguoiDung') || ! $schema->hasTable('NguoiDungVaiTro')) {
            return;
        }

        $db = DB::connection($conn);
        if ($db->table('NguoiDung')->where('SoDienThoai', self::SO_DIEN_THOAI)->exists()) {
            return;
        }

        $db->transaction(function () use ($db): void {
            $id = $db->table('NguoiDung')->insertGetId([
                'Ten' => 'Quản trị Mô tô',
                'SoDienThoai' => self::SO_DIEN_THOAI,
                'MatKhau' => Hash::make('ML2026@'),
                'NgayTao' => now(),
                'NgayCapNhat' => now(),
            ]);

            foreach (self::VAI_TRO as $maVaiTro) {
                $db->table('NguoiDungVaiTro')->insert([
                    'NguoiDungId' => $id,
                    'MaVaiTro' => $maVaiTro,
                ]);
            }
        });
    }

    public function down(): void
    {
        // Giữ tài khoản khi rollback.
    }
};
