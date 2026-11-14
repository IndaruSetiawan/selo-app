<?php

use Dotenv\Dotenv;

if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) return false;
}

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
require ROOT_PATH . '/vendor/autoload.php';
require APP_PATH . '/Core/helpers.php';
Dotenv::createImmutable(ROOT_PATH)->safeload();
session_start();

spl_autoload_register(function (string $class) {
    $file = APP_PATH . '/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (str_starts_with($class, 'App\\') && is_file($file)) {
        require $file;
    }
});

$router = new App\Core\Router();
require ROOT_PATH . '/config/routes.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);