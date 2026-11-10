<?php

namespace App\Providers;

use App\Support\ManhlinhMoto;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();
        $this->ensureManhlinhMotoSchema();
    }

    /** DB MANHLINH_MOTO + bảng HocVienDoThongTin (icon «đã dò» trên danh sách học viên). */
    private function ensureManhlinhMotoSchema(): void
    {
        $conn = ManhlinhMoto::connection();

        try {
            if (! Schema::connection($conn)->hasTable('HocVienDoThongTin')) {
                Artisan::call('manhlinh-moto:setup');
            }
        } catch (\Throwable) {
            // SQL chưa chạy hoặc chưa tạo DB — không chặn trang.
        }
    }
}
