<?php

namespace App\Providers;

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
        $this->ensureSqliteDoThongTin();
    }

    /** File sqlite + bảng HocVienDoThongTin (icon «đã dò» trên danh sách học viên). */
    private function ensureSqliteDoThongTin(): void
    {
        $path = (string) config('database.connections.sqlite.database');
        if ($path === '') {
            return;
        }

        if (! is_dir(dirname($path))) {
            @mkdir(dirname($path), 0755, true);
        }
        if (! file_exists($path)) {
            touch($path);
        }

        try {
            if (! Schema::connection('sqlite')->hasTable('HocVienDoThongTin')) {
                Artisan::call('migrate', [
                    '--force' => true,
                    '--path' => 'database/migrations/2026_11_09_000000_create_hoc_vien_do_thong_tin_sqlite_table.php',
                ]);
            }
        } catch (\Throwable) {
            // Không chặn trang nếu sqlite lỗi (quyền ghi, v.v.).
        }
    }
}
