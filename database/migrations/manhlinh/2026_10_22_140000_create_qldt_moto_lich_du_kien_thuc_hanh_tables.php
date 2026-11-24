<?php

use App\Support\ManhlinhMoto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lịch dự kiến thực hành (QLĐT Ô TÔ → Làm lịch dự kiến → Thực hành), ngày nghỉ, các cặp giáo viên sáng/chiều + xe
 * và các buổi đã sinh (chụp lại nội dung/giờ của bài dạy lúc sinh lịch).
 * Mã GV / biển số tham chiếu bảng GiaoVien / XeTap bên DB PMGPLX (khác DB nên không đặt khoá ngoại).
 */
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
            $schema->create('QldtMotoLichDuKienThucHanh', function (Blueprint $table) {
                $table->increments('Id');
                $table->string('TenKhoa', 255);
                $table->string('Hang', 10);
                $table->date('NgayBatDauThucHanh');
                $table->string('XeTuDong', 20)->nullable();
                $table->date('CabinCapTruocNgay')->nullable();
                $table->unsignedInteger('NguoiTaoId')->nullable();
                $table->dateTime('NgaySinhLich')->nullable();
                $table->dateTime('NgayTao')->nullable();
                $table->dateTime('NgayCapNhat')->nullable();

                $table->index(['Hang', 'NgayBatDauThucHanh'], 'IX_QldtMotoLichDuKienThucHanh_Hang_Ngay');
            });
        }

        if (! $schema->hasTable('QldtMotoLichDuKienThucHanhNgayNghi')) {
            $schema->create('QldtMotoLichDuKienThucHanhNgayNghi', function (Blueprint $table) {
                $table->increments('Id');
                $table->unsignedInteger('LichDuKienId');
                $table->date('Ngay');
                $table->string('GhiChu', 255)->nullable();

                $table->unique(['LichDuKienId', 'Ngay'], 'UX_QldtMotoLichDuKienThucHanhNgayNghi_Lich_Ngay');
                $table->foreign('LichDuKienId', 'FK_QldtMotoLichDuKienThucHanhNgayNghi_Lich')
                    ->references('Id')->on('QldtMotoLichDuKienThucHanh')
                    ->cascadeOnDelete();
            });
        }

        if (! $schema->hasTable('QldtMotoLichDuKienThucHanhCapGv')) {
            $schema->create('QldtMotoLichDuKienThucHanhCapGv', function (Blueprint $table) {
                $table->increments('Id');
                $table->unsignedInteger('LichDuKienId');
                $table->unsignedSmallInteger('ThuTu')->default(0);
                $table->string('MaGvSang', 20)->nullable();
                $table->string('MaGvChieu', 20)->nullable();
                $table->string('BienSoXe', 20)->nullable();

                $table->index(['LichDuKienId', 'ThuTu'], 'IX_QldtMotoLichDuKienThucHanhCapGv_Lich');
                $table->foreign('LichDuKienId', 'FK_QldtMotoLichDuKienThucHanhCapGv_Lich')
                    ->references('Id')->on('QldtMotoLichDuKienThucHanh')
                    ->cascadeOnDelete();
            });
        }

        if (! $schema->hasTable('QldtMotoLichDuKienThucHanhBuoi')) {
            $schema->create('QldtMotoLichDuKienThucHanhBuoi', function (Blueprint $table) {
                $table->increments('Id');
                $table->unsignedInteger('LichDuKienId');
                $table->unsignedSmallInteger('CapThuTu');
                $table->string('Ca', 10);
                $table->string('MaGv', 20)->nullable();
                $table->string('BienSoXe', 20)->nullable();
                $table->date('Ngay');
                $table->unsignedSmallInteger('NgaySo');
                $table->string('NoiDung', 500);
                $table->string('GioBatDau', 50)->nullable();
                $table->string('GioKetThuc', 50)->nullable();
                $table->decimal('SoGio', 5, 2)->nullable();

                $table->index(['LichDuKienId', 'Ngay'], 'IX_QldtMotoLichDuKienThucHanhBuoi_Lich_Ngay');
                $table->foreign('LichDuKienId', 'FK_QldtMotoLichDuKienThucHanhBuoi_Lich')
                    ->references('Id')->on('QldtMotoLichDuKienThucHanh')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());
        $schema->dropIfExists('QldtMotoLichDuKienThucHanhBuoi');
        $schema->dropIfExists('QldtMotoLichDuKienThucHanhCapGv');
        $schema->dropIfExists('QldtMotoLichDuKienThucHanhNgayNghi');
        $schema->dropIfExists('QldtMotoLichDuKienThucHanh');
    }
};
