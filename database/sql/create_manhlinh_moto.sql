-- Tạo DB app PMGPLX-MOTO (chạy trên master hoặc: php artisan manhlinh-moto:setup)
IF DB_ID(N'MANHLINH_MOTO') IS NULL
    CREATE DATABASE [MANHLINH_MOTO];
