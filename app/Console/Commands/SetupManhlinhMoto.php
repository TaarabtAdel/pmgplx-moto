<?php

namespace App\Console\Commands;

use App\Support\ManhlinhMoto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetupManhlinhMoto extends Command
{
    protected $signature = 'manhlinh-moto:setup
                            {--skip-create-db : Bỏ qua CREATE DATABASE (DB đã có sẵn)}';

    protected $description = 'Tạo database MANHLINH_MOTO và chạy migration tối thiểu cho app mô tô';

    public function handle(): int
    {
        $db = ManhlinhMoto::databaseName();
        $conn = ManhlinhMoto::connection();

        if (! preg_match('/^[A-Za-z0-9_]+$/', $db)) {
            $this->error('Tên database không hợp lệ: '.$db);

            return self::FAILURE;
        }

        if (! $this->option('skip-create-db')) {
            try {
                DB::connection('sqlsrv_master')->statement(
                    "IF DB_ID(N'{$db}') IS NULL CREATE DATABASE [{$db}]"
                );
                $this->info("Database [{$db}] sẵn sàng.");
            } catch (\Throwable $e) {
                $this->error('Không tạo được database: '.$e->getMessage());

                return self::FAILURE;
            }
        }

        try {
            Artisan::call('migrate', [
                '--database' => $conn,
                '--path' => 'database/migrations/manhlinh_moto',
                '--force' => true,
            ]);
            $this->line(Artisan::output());
            $this->info('Migration MANHLINH_MOTO xong.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Migrate lỗi: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
