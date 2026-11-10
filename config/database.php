<?php

use Illuminate\Support\Str;

$motoBanMoi = [
    'driver' => 'sqlsrv',
    'host' => env('DB_HOST_4', env('DB_HOST', 'ADMIN-PC')),
    'port' => env('DB_PORT_4', env('DB_PORT', '1433')),
    'database' => env('DB_DATABASE_4', env('DB_DATABASE', 'GPLX_CDB_CSDT_MOTO')),
    'username' => env('DB_USERNAME_4', env('DB_USERNAME', 'sa')),
    'password' => env('DB_PASSWORD_4', env('DB_PASSWORD', '')),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'encrypt' => env('DB_ENCRYPT', 'yes'),
    'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
    'options' => [
        PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
    ],
];

return [

    'default' => env('DB_CONNECTION', 'sqlsrv_moto'),

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => database_path(env('DB_SQLITE_FILE', 'database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        /** Live: `DB_CONNECTION=sqlsrv` + `DB_DATABASE` — cùng DB với `sqlsrv_moto`. */
        'sqlsrv' => $motoBanMoi,

        'sqlsrv_moto' => $motoBanMoi,

        /** Phần mềm cũ — `DB_DATABASE_2` (vd. GPLX_CSDT). */
        'sqlsrv_moto_old' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_5', env('DB_HOST_2', env('DB_HOST', 'ADMIN-PC'))),
            'port' => env('DB_PORT_5', env('DB_PORT_2', env('DB_PORT', '1433'))),
            'database' => env('DB_DATABASE_5', env('DB_DATABASE_2', 'GPLX_CSDT')),
            'username' => env('DB_USERNAME_5', env('DB_USERNAME_2', env('DB_USERNAME', 'sa'))),
            'password' => env('DB_PASSWORD_5', env('DB_PASSWORD_2', env('DB_PASSWORD', ''))),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'yes'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
            'options' => [
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
            ],
        ],

        /** App (dò thông tin, đăng nhập) — `DB_DATABASE_3` (live: MANHLINH; Docker có thể MANHLINH_MOTO). */
        'sqlsrv_manhlinh_moto' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_3', env('DB_HOST', 'ADMIN-PC')),
            'port' => env('DB_PORT_3', env('DB_PORT', '1433')),
            'database' => env('DB_DATABASE_3', 'MANHLINH'),
            'username' => env('DB_USERNAME_3', env('DB_USERNAME', 'sa')),
            'password' => env('DB_PASSWORD_3', env('DB_PASSWORD', '')),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'yes'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
            'options' => [
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
            ],
        ],

        'sqlsrv_master' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_3', env('DB_HOST', 'ADMIN-PC')),
            'port' => env('DB_PORT_3', env('DB_PORT', '1433')),
            'database' => 'master',
            'username' => env('DB_USERNAME_3', env('DB_USERNAME', 'sa')),
            'password' => env('DB_PASSWORD_3', env('DB_PASSWORD', '')),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'encrypt' => env('DB_ENCRYPT', 'yes'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
            'options' => [
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
            ],
        ],

    ],

    'migrations' => 'migrations',

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
