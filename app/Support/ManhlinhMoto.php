<?php

namespace App\Support;

/** Tên connection SQL Server cho DB app mô tô (MANHLINH_MOTO). */
final class ManhlinhMoto
{
    public static function connection(): string
    {
        return (string) config('moto.manhlinh_connection', 'sqlsrv_manhlinh_moto');
    }

    public static function databaseName(): string
    {
        return (string) config('database.connections.'.self::connection().'.database', 'MANHLINH_MOTO');
    }
}
