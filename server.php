<?php

use App\Common\HttpServer\Server;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/bootstrap.php';

Server::Create($_SERVER)
    ->setPort(8080)
    ->setRequestsPerGC(500)
    ->run();
