<?php


namespace Core\Domain\Entity;


class City
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
   * @var District[]
   */
  private $districts;

  /**
   * @param string $id
   * @param string $name
   */
  public function __construct(string $id, string $name)
  {
    $this->id = $id;
    $this->name = $name;
    $this->districts = [];
  }

  /**
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId($id)
  {
    $this->id = $id;
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
   * @return District[]
   */
  public function getDistricts(): array
  {
    return $this->districts;
  }

  /**
   * @param District $district
   */
  public function addDistrict(District $district): void
  {
    $this->districts[] = $district;
  }

  /**
   * @return array
   */
  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'districts' => $this->districts
    ];
  }
}