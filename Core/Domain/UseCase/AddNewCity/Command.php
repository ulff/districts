<?php


namespace Core\Domain\UseCase\AddNewCity;


class Command
{
  private $name;

  /**
   * @param string|null $name
   */
  public function __construct(?string $name)
  {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

}