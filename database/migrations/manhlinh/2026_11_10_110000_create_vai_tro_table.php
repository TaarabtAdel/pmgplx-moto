<?php

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

    /** Vai trò tối thiểu cho PMGPLX-MOTO (không Đào tạo / Tuyển sinh ô tô). */
    private const MAC_DINH = [
        ['admin', 'Admin', true],
        ['it', 'IT', false],
    ];

    public function up(): void
    {
        $conn = $this->getConnection();
        $schema = Schema::connection($conn);
        if ($schema->hasTable('VaiTro')) {
            return;
        }

        $schema->create('VaiTro', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('MaVaiTro', 50);
            $table->string('TenVaiTro', 100);
            $table->unsignedInteger('ThuTu')->default(0);
            $table->boolean('LaHeThong')->default(false);
            $table->dateTime('NgayTao')->nullable();

            $table->unique('MaVaiTro', 'UX_VaiTro_MaVaiTro');
        });

        $now = now();
        foreach (self::MAC_DINH as $i => [$ma, $ten, $heThong]) {
            DB::connection($conn)->table('VaiTro')->insert([
                'MaVaiTro' => $ma,
                'TenVaiTro' => $ten,
                'ThuTu' => $i + 1,
                'LaHeThong' => $heThong,
                'NgayTao' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::connection($this->getConnection())->dropIfExists('VaiTro');
    }
};
