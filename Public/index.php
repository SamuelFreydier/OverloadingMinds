<?php
namespace web;

use App\Src\Autoloader;

session_start();
require_once __DIR__ . '/../App/Src/Autoloader.php';
Autoloader::register();

$app = require_once __DIR__ . '/../App/Bootstrap.php';
$app->run();