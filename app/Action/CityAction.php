<?php
namespace App\Action;

use Core\Domain\Entity\City;
use Core\Domain\Repository\CityRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Core\Domain\UseCase\AddNewCity;

class CityAction implements AddNewCity\Responder
{
  private $cityRepository;
  private $view;
  private $addNewCityUseCase;
  private $response;

  public function __construct(CityRepository $cityRepository, Twig $view)
  {
    $this->cityRepository = $cityRepository;
    $this->view = $view;
    $this->addNewCityUseCase = new AddNewCity($cityRepository);
  }

  public function addCity(Request $request, Response $response)
  {
    $this->response = $response;
    $body = $request->getParsedBody();

    $this->addNewCityUseCase->execute(new AddNewCity\Command($body['name']), $this);

    return $this->response;
  }

  public function cityAddedSuccessfully(City $city)
  {
    $this->response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(201)
      ->getBody()->write(json_encode($city->toArray()));
  }

  public function addingCityFailed(\Exception $exception)
  {
    $this->response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(400)
      ->getBody()->write(json_encode(['message' => $exception->getMessage()]));
  }
}