<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    public string $baseURL = 'http://localhost:8080/';
    public array $allowedHosts = [];
    public string $indexPage = '';
    public bool $forceGlobalSecureRequests = false;
    public string $sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler';
    public string $sessionCookieName = 'ci_session';
    public int $sessionExpiration = 7200;
    public bool $sessionMatchIP = false;
    public int $sessionTimeToUpdate = 300;
    public bool $sessionRegenerateDestroy = false;
    public string $sessionSavePath = WRITEPATH . 'session';
    public array $cookiePrefix = [];
    public string $cookieDomain = '';
    public int $cookiePath = '/';
    public bool $cookieSecure = false;
    public string $cookieHTTPOnly = true;
    public string $cookieSameSite = 'Lax';
    public string $CSRFProtection = 'session';
    public string $CSRFCookieName = 'csrf_test_name';
    public int $CSRFExpire = 7200;
    public string $CSRFRedirect = true;
    public string $CSRFSameSite = 'Lax';
    public bool $CSPEnabled = false;
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $appTimezone = 'UTC';
    public string $charset = 'UTF-8';
}
