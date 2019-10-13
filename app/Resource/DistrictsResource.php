<?php
namespace App\Resource;

use Doctrine\ORM\EntityManager;
use App\Entity\Districts;
use App\Entity\City;

class DistrictsResource
{
    protected $entityManager = null;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function fetchByName($cityName = 'all')
    {
        $response = array();
        if ($cityName === 'all') {
            $query = $this->entityManager->getRepository('App\Entity\Districts')->findAll();
            if($query) {
                foreach ($query as $district) {
                    $response[] = array(
                        'name' => $district->getName(), 
                        'population' => $district->getPopulation(),
                        'area' => $district->getArea(),
                        'city' => $district->getCity()->getName()
                    );
                }
            }
        } else {
            $query = $this->entityManager->getRepository('App\Entity\Districts')
                ->createQueryBuilder('d')
                ->select('d')
                ->innerJoin('d.city', 'c')
                ->where('c.name = :city')
                ->setParameter('city', $cityName)
                ->getQuery()     
                ->getResult();

            if($query) {
                foreach ($query as $district) {
                    $response[] = array(
                        'id' => $district->getId(),
                        'name' => $district->getName(), 
                        'population' => $district->getPopulation(),
                        'area' => $district->getArea(),
                        'city' => $district->getCity()->getName()
                    );
                }
            }  
        }
        $responseCode = (count($response)>0) ? 200 : 404;
        $response = (count($response)>0) ? $response : 'Bad Request';
        return [$responseCode, $response];
    }

    public function sortBy($cityName = 'all', $name, $order = 'asc')
    {
        $response = array();
        $query = $this->entityManager->getRepository('App\Entity\Districts')
                ->createQueryBuilder('d')
                ->select('d')
                ->innerJoin('d.city', 'c');
        if($cityName!='all') {
            $query = $query->where('c.name = :city')
                    ->setParameter('city', $cityName);
        }
        $orderName = 'd.'.$name;

        $query = $query->orderBy($orderName, $order)
                        ->getQuery();
        try {
            $query = $query->getResult();
            if($query) {
                foreach ($query as $district) {
                    $response[] = array(
                        'id' => $district->getId(),
                        'name' => $district->getName(), 
                        'population' => $district->getPopulation(),
                        'area' => $district->getArea(),
                        'city' => $district->getCity()->getName()
                    );
                }
            } 
            $responseCode = (count($response)>0) ? 200 : 404;
        } catch (\Throwable $th) {
            $responseCode = 400;
            $response = ['Bad request'];
        }                      
        return [$responseCode, $response];
    }

    public function add($name, $population, $area, $cityName)
    {
        $district = $this->entityManager->getRepository('App\Entity\Districts')->findByName($name);
        if(!$district) {
            $city = $this->entityManager->getRepository('App\Entity\City')->findOneBy(
                array('name' => $cityName)
            );

            if(!$city) {
                $city = new City();
                $city->setName($city);
                $this->entityManager->persist($city);
                $this->entityManager->flush();
                $cityId = $city->getId();
            } 

            $districts = new Districts();
            $districts->setName($name);
            $districts->setArea($area);
            $districts->setPopulation($population);
            $districts->setCity($city);
            $this->entityManager->persist($districts);
            $this->entityManager->flush();
            return [202, 'New District added'];
        } 
        return [409, 'District already exist'];
    }

    public function updateDistrict($name, $population, $area, $city, $id)
    {

    }
    public function delete($id)
    {
        $district = $this->entityManager->getRepository('App\Entity\Districts')->findOneBy(
            array('id' => $id)
        );
        if($district) {
            $this->entityManager->remove($district);
            $this->entityManager->flush();
            return [200, 'Delete'];
        }
        return [404, 'Bad Request'];

    }

    public function filtr($filtres)
    {
        $query = $this->entityManager->getRepository('App\Entity\Districts')
                ->createQueryBuilder('d')
                ->select('d')
                ->innerJoin('d.city', 'c');
        foreach ($filtres as $filtrK => $filtr) {
            $data = explode("|", $filtr);
            if(sizeof($data)==2) {
                $where = 'd.'.$filtrK.' between '.$data[0].' and '.$data[1];
                $query = $query->andWhere($where);
            } elseif ( sizeof($data) == 1) {
                $where = 'd.'.$filtrK.' > '.$data[0];
                $query = $query->andWhere($where);
            }
        }
        try {
            $query = $query->getQuery()     
                ->getResult();
            if($query) {
                foreach ($query as $district) {
                    $response[] = array(
                        'id' => $district->getId(),
                        'name' => $district->getName(), 
                        'population' => $district->getPopulation(),
                        'area' => $district->getArea(),
                        'city' => $district->getCity()->getName()
                    );
                }
            } 
            $responseCode = (count($response)>0) ? 200 : 404;
        } catch (\Throwable $th) {
            $responseCode = 400;
            $response = ['Bad request'];
        }                      
        return [$responseCode, $response];     
    }
}