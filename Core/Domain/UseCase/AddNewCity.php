<?php


namespace Core\Domain\UseCase;


use Core\Domain\Entity\City;
use Core\Domain\Repository\CityRepository;
use Core\Domain\Exception\MissingCityNameException;
use Core\Domain\UseCase\AddNewCity\Command;
use Core\Domain\UseCase\AddNewCity\Responder;

class AddNewCity
{
  /**
   * @var CityRepository
   */
  private $cityRepository;

  /**
   * @param CityRepository $cityRepository
   */
  public function __construct(CityRepository $cityRepository)
  {
    $this->cityRepository = $cityRepository;
  }

  /**
   * @param Command $command
   * @param Responder $responder
   */
  public function execute(Command $command, Responder $responder) {
    if (empty($command->getName())) {
      $responder->addingCityFailed(new MissingCityNameException());
      return;
    }

    $cityId = uniqid();
    $city = $this->cityRepository->add(new City($cityId, $command->getName()));

    $responder->cityAddedSuccessfully($city);
  }
}