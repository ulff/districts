<?php
declare(strict_types=1);
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use App\Resource\DownloadResource;
use App\Action\DownloadAction;
use GuzzleHttp\Client;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        EntityManager::class => function(ContainerInterface $c) {
            $settings = $c->get('settings');
            $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
                $settings['doctrine']['meta']['entity_path'],
                $settings['doctrine']['meta']['auto_generate_proxies'],
                $settings['doctrine']['meta']['proxy_dir'],
                $settings['doctrine']['meta']['cache'],
                false
            );
            return \Doctrine\ORM\EntityManager::create($settings['doctrine']['connection'], $config);
        },
        HttpClient::class => function(ContainerInterface $c) {
            return new Client();
        },
        DownloadAction::class => function(ContainerInterface $c) {
            $downloadResource = new \App\Resource\DownloadResource($c->get(EntityManager::class), $c->get(HttpClient::class));
            return new App\Action\DownloadAction($downloadResource);
        },
        
    ]);
};
