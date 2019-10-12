<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use App\Districts;
use App\Download;
use GuzzleHttp\Client;
use App\Resource\DownloadResource;
use App\Action\DownloadAction;

require_once __DIR__ . '/vendor/autoload.php';
// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$settings = require __DIR__ . '/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/dependencies.php';
$dependencies($containerBuilder);

// Build PHP-DI Container instance
//$container = $containerBuilder->build();
AppFactory::setContainer($containerBuilder->build());
$app = AppFactory::create();


const DISTRICTS = [
    0 => [1,'Aniołki','4608', '2,31 km2', 1],
    1 => [2, 'Młyniska', '2846', '4,18 km2', 1],
    2 => [3, 'Krakowiec-Górki Zachodnie', '1877', '8,38 km2', 1],
    3 => [4, 'Podgórze Duchackie', '53747', '954,00 ha', 2],
    4 => [5, 'Podgórze', '36885', '2566,71 ha', 2],
    5 => [6, 'Bieńczyce', '41112', '369,90', 2]
];

const CITIES = [
    0 => [
        'id' => 1, 
        'name' => 'Gdańsk', 
        'url' => 'https://www.gdansk.pl/subpages/dzielnice/[dzielnice]/html/dzielnice_mapa_alert.php?id=1'
    ],
    1 => [
        'id' => 2, 
        'name' => 'Kraków', 
        'url' => '//appimeri.um.krakow.pl/app-pub-dzl/pages/DzlViewGlw.jsf?id=10'
        ]
];


$app->get('/districts', function (Request $request, Response $response, $args) {
    $districts = new Districts();
    $data = $districts->fetchAll(DISTRICTS);
    //var_dump($data);
    $response->getBody()->write(json_encode($data));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
});

$app->get('/download/{name}', function (Request $request, Response $response, $args) {
    
    $download = new Download(new Client(), CITIES);
    if($args['name'] == 'all') {
        $download->fetchAll();
    }
    
    $response->getBody()->write(json_encode('ok'));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);

});

$app->get('/api/download/', DownloadAction::class.':insertAll');

$app->run();
