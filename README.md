# KHGPLX — PMGPLX-MOTO (ứng dụng riêng)

Bản tách từ `laravel/`, **chỉ** tab **PMGPLX-MOTO**:

- Quản lý giáo viên, học viên, xe, khoá học (bản mới)
- Học viên bản cũ (`pmgplxmotoold`)
- Đồng bộ học viên sang bản cũ, nhập từ file, dò thông tin CCCD/GPLX

## Cơ sở dữ liệu

| Connection Laravel       | Biến `.env` (live)        | Database mẫu |
|--------------------------|---------------------------|--------------|
| `sqlsrv_moto` / `sqlsrv` | `DB_DATABASE`             | `GPLX_CDB_CSDT_MOTO` |
| `sqlsrv_moto_old`        | `DB_DATABASE_2`           | `GPLX_CSDT` |
| `sqlsrv_manhlinh_moto`   | `DB_DATABASE_3`           | `MANHLINH` hoặc `MANHLINH_MOTO` (Docker) |

`MANHLINH_MOTO` chỉ gồm bảng app mô tô (không copy MANHLINH ô tô): `HocVienDoThongTin`, `NguoiDung`, `VaiTro`, `PhanQuyen`, …

```bash
php artisan manhlinh-moto:setup
```

## Cài đặt

```bash
cd laravel-moto
cp .env.example .env
php artisan key:generate
composer install   # nếu chưa có vendor
php artisan manhlinh-moto:setup
```

## Chạy

Mặc định `MOTO_STANDALONE=true`: **không** đăng nhập, menu đầy đủ.

Chạy dev:

```bash
php artisan serve --port=8081
```

## Docker (port 8081)

Dùng **cùng** container SQL Server với app ô tô (`8080`):

```bash
cd ..   # thư mục khgplx (có docker-compose.yml)
docker compose up -d db app          # ô tô — nếu chưa chạy
docker compose -f docker-compose.yml -f docker-compose.moto.yml up -d --build app-moto

# lần đầu (trong laravel-moto/.env có APP_KEY)
docker compose -f docker-compose.yml -f docker-compose.moto.yml exec app-moto php artisan key:generate
docker compose -f docker-compose.yml -f docker-compose.moto.yml exec app-moto php artisan manhlinh-moto:setup
```

Mở **http://localhost:8081** → PMGPLX-MOTO.

Biến `DB_*` trong container được set bởi `docker-compose.moto.yml` (`GPLX_MOTO_BAN_MOI` / `GPLX_MOTO_BAN_CU`). File `laravel-moto/.env` vẫn cần `APP_KEY` và tuỳ chọn Google Vision (dò thông tin).

## Tách khỏi `laravel/`

PMGPLX-MOTO chỉ còn trong repo/thư mục `laravel-moto/` (đã gỡ khỏi `laravel/`). Sửa mô tô tại đây, không rsync từ `laravel/`.
