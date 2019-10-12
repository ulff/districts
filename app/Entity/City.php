<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Districts;

/**
 * @ORM\Entity
 * @ORM\Table(name="cities")
 */
class City
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Districts", mappedBy="city")
     * @var Doctrine\Common\Collection\ArrayCollection
     */
    private $districts;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->districts = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return City
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
     * Add district.
     *
     * @param \App\Entity\Districts $district
     *
     * @return City
     */
    public function addDistrict(\App\Entity\Districts $district)
    {
        $this->districts[] = $district;

        return $this;
    }

    /**
     * Remove district.
     *
     * @param \App\Entity\Districts $district
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDistrict(\App\Entity\Districts $district)
    {
        return $this->districts->removeElement($district);
    }

    /**
     * Get districts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDistricts()
    {
        return $this->districts;
    }
}
