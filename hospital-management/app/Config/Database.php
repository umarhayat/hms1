<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public array $default = [
        'DSN'          => '',
        'hostname'     => env('database.default.hostname', 'localhost'),
        'username'     => env('database.default.username', 'root'),
        'password'     => env('database.default.password', ''),
        'database'     => env('database.default.database', 'hospital_db'),
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberConnect'=> null,
        'options'      => [\
            \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_ORACLE_NULLS => \PDO::NULL_NATURAL,
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ];

    public bool $migrationsEnabled = true;
    public string $migrationsTable = 'migrations';
}
