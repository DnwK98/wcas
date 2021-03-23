<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(__DIR__) . '/.env');

if (isset($_SERVER['APP_DEBUG']) && $_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}
