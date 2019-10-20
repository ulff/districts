<?php


namespace Core\Domain\UseCase\AddNewCity;


use Core\Domain\Entity\City;

interface Responder
{
  public function cityAddedSuccessfully(City $city);

  public function addingCityFailed(\Exception $exception);
}