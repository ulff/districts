<?php


namespace Core\Domain\Exception;


class MissingCityNameException extends \Exception
{
  public function __construct()
  {
    $this->message = 'Missing city name';
  }
}