<?php

/**
 * Ứng dụng PMGPLX-MOTO độc lập: chỉ 2 DB GPLX mô tô, không MANHLINH / ô tô.
 */
return [
    /** true = không đăng nhập, không phân quyền (mạng nội bộ). false = cần DB Hệ Thống + đăng nhập. */
    'standalone' => env('MOTO_STANDALONE', true),
];
