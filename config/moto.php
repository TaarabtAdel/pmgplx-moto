<?php

/**
 * Ứng dụng PMGPLX-MOTO độc lập: GPLX mô tô + DB app riêng MANHLINH_MOTO (không dùng MANHLINH ô tô).
 */
return [
    /** true = không đăng nhập, không phân quyền (mạng nội bộ). false = cần DB Hệ Thống + đăng nhập. */
    'standalone' => env('MOTO_STANDALONE', true),

    /** Connection Laravel cho bảng app (HocVienDoThongTin, NguoiDung, …). */
    'manhlinh_connection' => env('DB_MANHLINH_CONNECTION', 'sqlsrv_manhlinh_moto'),
];
