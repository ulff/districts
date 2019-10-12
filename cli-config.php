<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use DI\ContainerBuilder;

require __DIR__ . '/vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

//$settings = include '/../src/settings.php';
$settings = require __DIR__ . '/settings.php';
$settings($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();


// $settings = include 'src/settings.php';
// $settings = $settings['settings']['doctrine'];

$doctrineSetting = $container->get('settings')['doctrine'];

$config = Setup::createAnnotationMetadataConfiguration(
    $doctrineSetting['meta']['entity_path'],
    $doctrineSetting['meta']['auto_generate_proxies'],
    $doctrineSetting['meta']['proxy_dir'],
    $doctrineSetting['meta']['cache'],
    false
);

$em = EntityManager::create($doctrineSetting['connection'], $config);
$helperSet = ConsoleRunner::createHelperSet($em);
return ConsoleRunner::run($helperSet, []);