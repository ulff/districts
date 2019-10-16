<?php
declare(strict_types=1);
use DI\ContainerBuilder;

define('APP_ROOT', __DIR__);

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true,
            'doctrine' => [
                'dev_mode' => true,
                'cache_dir' => false,
                'metadata_dirs' => [APP_ROOT . '/src/Domain'],
                'connection' => [
                    'driver' => 'pdo_mysql',
                    'host' => getenv('MYSQL_HOST'),
                    'port' => 3306,
                    'dbname' => getenv('MYSQL_DBNAME'),
                    'user' => getenv('MYSQL_USER'),
                    'password' => getenv('MYSQL_PASSWORD'),
                    'charset'   => 'utf8',
                    'collation' => 'utf8_polish_ci',
                ],
                'meta' => [
                    'entity_path' => [
                        'app/Entity'
                    ],
                    'auto_generate_proxies' => true,
                    'proxy_dir' =>  __DIR__.'/cache/proxies',
                    'cache' => null,
                ],
            ]
        ],
    ]);
};