<?php


namespace Core\Domain\Entity;


class District
{
  /**
   * @var string
   */
  private $id;

  /**
   * @var string
   */
  private $name;

  /**
   * @var int
   */
  private $population;

  /**
   * @var float
   */
  private $area;

  /**
   * @var City
   */
  private $city;

  /**
   * @param string $id
   * @param string $name
   * @param int $population
   * @param float $area
   * @param City $city
   */
  public function __construct(string $id, string $name, int $population, float $area, City $city)
  {
    $this->id = $id;
    $this->name = $name;
    $this->population = $population;
    $this->area = $area;
    $this->city = $city;
  }

  /**
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return int
   */
  public function getPopulation()
  {
    return $this->population;
  }

  /**
   * @param int $population
   */
  public function setPopulation($population)
  {
    $this->population = $population;
  }

  /**
   * @return float
   */
  public function getArea()
  {
    return $this->area;
  }

  /**
   * @param float $area
   */
  public function setArea($area)
  {
    $this->area = $area;
  }

  /**
   * @return City
   */
  public function getCity()
  {
    return $this->city;
  }

  /**
   * @param City $city
   */
  public function setCity($city)
  {
    $this->city = $city;
  }
}