<?php

namespace DarkTec;

session_start();

use DarkTec\Http\Request;
use DarkTec\Http\MiddlewareEngine;
use DarkTec\Router\Router;
use DarkTec\Helpers\Container;
use DarkTec\Helpers\DB;
use Dotenv\Dotenv;


class Application
{

    public function __construct()
    {

        // Load env files
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();

        // Create Di container
        $container = Container::getInstance();

        // Load all config and add into container
        $config = [];
        $directory = __DIR__ . '/config';
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $path = $item->getPathname();
            $filename = str_replace('.php', '', $item->getFilename());
            if ($item->isFile()) {
                $config[$filename] = include $path;
            }
        }

        $container->set('config', $config);

        // Error handling
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        // Global middlewares
        $middleware = new MiddlewareEngine(new Request());
        $container->set('middlewareEngine', $middleware);

        // Init DB singleton
        DB::init();

        // Init router and set routes
        Router::init($container);
    }

    public function start() {
        Router::match($_SERVER['REQUEST_METHOD'], strtok($_SERVER["REQUEST_URI"], '?'));
    }
}
