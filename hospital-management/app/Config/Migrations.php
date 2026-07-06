<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Migrations extends BaseConfig
{
    public bool $enabled = true;
    public string $table = 'migrations';
    public int $timestamp = 0;
    public string $filePattern = '*.php';
}
