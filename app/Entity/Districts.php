<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\City;

/**
 * @ORM\Entity
 * @ORM\Table(name="districts")
 */
class Districts 
{

    /**
     * @var int
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, options={"collation":"utf8_polish_ci"})
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $area;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $population;

        /**
     * @var City[]|ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="City", inversedBy="districts")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=false)
     * @var \entities\City
     */
    private $city;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Districts
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set area.
     *
     * @param float $area
     *
     * @return Districts
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area.
     *
     * @return float
     */
    public function getArea()
    {
        return $this->area;
    }

   

    /**
     * Set city.
     *
     * @param \App\Entity\City $city
     *
     * @return Districts
     */
    public function setCity(\App\Entity\City $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return \App\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set population.
     *
     * @param int $population
     *
     * @return Districts
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /**
     * Get population.
     *
     * @return int
     */
    public function getPopulation()
    {
        return $this->population;
    }

}
