<?php


namespace Core\Domain\Repository;


use Core\Domain\Entity\City;

interface CityRepository
{
  /**
   * @param City $city
   * @return City
   */
  public function add(City $city): City;

  /**
   * @param array $params
   * @return City[]
   */
  public function getList(array $params = []): array;

  /**
   * @param int $id
   * @return City
   */
  public function getOne(int $id): City;

  /**
   * @param City $city
   * @return City
   */
  public function update(City $city): City;

  /**
   * @param int $id
   * @return bool
   */
  public function delete(int $id): bool;
}