<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use App\Resource\DownloadResource;
use App\Action\DownloadAction;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

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
AppFactory::setContainer($containerBuilder->build());
$app = AppFactory::create();

$app->add(TwigMiddleware::createFromContainer($app, Twig::class));

const CITIES = [
    // 0 => [
    //     'id' => 1, 
    //     'name' => 'Gdańsk', 
    //     'url' => 'https://www.gdansk.pl/subpages/dzielnice/[dzielnice]/html/dzielnice_mapa_alert.php?id='
    // ],
    1 => [
        'id' => 2, 
        'name' => 'Kraków', 
        'url' => '//appimeri.um.krakow.pl/app-pub-dzl/pages/DzlViewGlw.jsf?id='
        ]
];

/**
 * API
 */
$app->get('/api/download/all', DownloadAction::class.':insertFromUrl');

$app->post('/api/cities/{cityName}/districts', DistrictsAction::class.':addDistrict');
$app->put('/api/cities/{cityName}/districts', DistrictsAction::class.':updateDistrict');
$app->delete('/api/cities/{cityName}/districts/{districtId}', DistrictsAction::class.':deleteDistrict');
$app->get('/api/cities/{cityName}/districts', DistrictsAction::class.':fetchDistrict');

/**
 * WEB
 */
$app->map(['GET', 'POST'],'/districts/add', DistrictsAction::class.':addDistrict')->setName('add');
$app->get('/districts/edit/{districtId}', DistrictsAction::class.':editDistrict')->setName('edit');
$app->post('/districts/update/{districtId}', DistrictsAction::class.':updateDistrict')->setName('update');
$app->post('/districts/delete[/{districtId}]', DistrictsAction::class.':deleteDistrict')->setName('delete');
$app->get('/districts[/{cityName}]', DistrictsAction::class.':fetchDistrict')->setName('fetch');
$app->get('/districts/all/{districtId}', DistrictsAction::class.':showDistrict')->setName('show');

$app->get('/', function(Request $request, Response $response, $args){
    return $response->withStatus(302)->withHeader('Location', '/districts');
});

$app->run();