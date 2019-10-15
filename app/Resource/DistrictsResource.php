<?php
namespace App\Resource;

use App\Resource\AbstractResource;
use App\Entity\Districts;
use App\Entity\City;

class DistrictsResource extends AbstractResource
{
    public function fetchById($districtId)
    {
        $response = array();
        try {
            $query = $this->entityManager->getRepository('App\Entity\Districts')->findOneBy(
                array('id' => (Int)$districtId)
            );
            if($query) {
                $response[] = array(
                    'id' => $query->getId(),
                    'name' => $query->getName(), 
                    'population' => $query->getPopulation(),
                    'area' => $query->getArea(),
                    'city' => $query->getCity()->getName()
                );
            }   
            $responseCode = (count($response)>0) ? 200 : 404;
        } catch (\Throwable $th) {
            $responseCode = 400;
            $response = ['Bad request'];
        }                      
        return [$responseCode, $response];
    }

    public function fetchByName($cityName = 'all')
    {
        $response = array();
        if ($cityName === 'all') {
            $query = $this->entityManager->getRepository('App\Entity\Districts')->findAll();
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

    public function sortBy($cityName = 'all', $name, $order = 'asc', $filtres)
    {
        $response = array();
        $query = $this->entityManager->getRepository('App\Entity\Districts')
                ->createQueryBuilder('d')
                ->select('d')
                ->innerJoin('d.city', 'c');

        foreach ($filtres as $filtrK => $filtr) {
            $data = explode("|", $filtr);
           
            if(sizeof($data)==2) {
                if($data[0]!="" && $data[1]!="") {
                    $where = 'd.'.$filtrK.' between '.$data[0].' and '.$data[1];
                    $query = $query->andWhere($where);
                } elseif($data[0]=="" && $data[1]!="") {
                    var_dump($data);
                    $where = 'd.'.$filtrK.' <= '.$data[1];
                    $query = $query->andWhere($where);
                } elseif($data[1]=="" && $data[0]!="") {
                    $where = 'd.'.$filtrK.' >= '.$data[0];
                    $query = $query->andWhere($where);
                }
            } elseif ( sizeof($data) == 1) {
                if($data[0]!="") {
                    $where = 'd.'.$filtrK.' > '.$data[0];
                    $query = $query->andWhere($where);
                }
            }
        }
        if($cityName!='all') {
            $query = $query->andWhere('c.name = :city')
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
        try {
            $city = $this->entityManager->getRepository('App\Entity\City')->findOneBy(
                array('name' => $cityName)
            );
            if(!$city) {
                $city = new City();
                $city->setName($cityName);
                $this->entityManager->persist($city);
                $this->entityManager->flush();
                
            }
            $cityId = $city->getId();
            try {
                $district = $this->entityManager->getRepository('App\Entity\Districts')->findOneBy(
                    array(
                        'name' => $name,
                        'city' => $cityId
                    ));

                if(!$district) {
                    $districts = new Districts();
                    $districts->setName($name);
                    $districts->setArea($area);
                    $districts->setPopulation($population);
                    $districts->setCity($city);
                    $this->entityManager->persist($districts);
                    $this->entityManager->flush();
                    $responseCode = 202;
                    $response = ['New District added'];
                }
            } catch (\Throwable $th) {
                $responseCode = 409;
                $response = ['District with this city already exist'];
            }
        } catch (\Throwable $th) {
            $responseCode = 400;
            $response = ['Bad request'];
        }                   
        return [$responseCode, $response];
    }

    public function update($name, $population, $area, $cityName, $districtId)
    {
        $district = $this->entityManager->getRepository('App\Entity\Districts')->findOneBy(
            array('id' => $districtId,));
        
        if($district) {
            $city = $this->entityManager->getRepository('App\Entity\City')->findOneBy(
                array('name' => $cityName)
            );
            if(!$city) {
                $city = new City();
                $city->setName($cityName);
                $this->entityManager->persist($city);
                $this->entityManager->flush();
            }

            $district->setName($name);
            $district->setArea($area);
            $district->setPopulation($population);
            $district->setCity($city);
            $this->entityManager->flush();
            return [202, 'Update'];
        } 
        return [409, 'Bad Request - problem with id'];
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

    public function filtr($filtres, $cityName)
    {
        $query = $this->entityManager->getRepository('App\Entity\Districts')
                ->createQueryBuilder('d')
                ->select('d')
                ->innerJoin('d.city', 'c');

        foreach ($filtres as $filtrK => $filtr) {
            $data = explode("|", $filtr);
            if(sizeof($data)==2) {
                if($data[0]!="" && $data[1]!="") {
                    $where = 'd.'.$filtrK.' between '.$data[0].' and '.$data[1];
                    $query = $query->andWhere($where);
                } elseif($data[0]=="" && $data[1]!="") {
                    $where = 'd.'.$filtrK.' <= '.$data[1];
                    $query = $query->andWhere($where);
                } elseif($data[1]=="" && $data[0]!="") {
                    $where = 'd.'.$filtrK.' >= '.$data[0];
                    $query = $query->andWhere($where);
                }
            } elseif ( sizeof($data) == 1) {
                if($data[0]!="") {
                    $where = 'd.'.$filtrK.' > '.$data[0];
                    $query = $query->andWhere($where);
                }
            }
        }
        if($cityName!="all") {
            $query = $query->andWhere('c.name = :city')
                    ->setParameter('city', $cityName);
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