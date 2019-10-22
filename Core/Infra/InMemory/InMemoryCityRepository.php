<?php

namespace Core\Infra\InMemory;

use Core\Domain\Entity\City;
use Core\Domain\Repository\CityRepository;

class InMemoryCityRepository implements CityRepository
{
  private $repo;

  public function __construct()
  {
    $this->repo = [];
  }

  public function add(City $city): City
  {
    $this->repo[$city->getId()] = $city;
    return $city;
  }

  public function getList(array $params = []): array
  {
    return $this->repo;
  }

  public function getOne(int $id): City
  {
    return $this->repo[$id];
  }

  public function update(City $city): City
  {
    $this->repo[$city->getId()] = $city;
  }

  public function delete(int $id): bool
  {
    unset($this->repo[$id]);
    return true;
  }
}