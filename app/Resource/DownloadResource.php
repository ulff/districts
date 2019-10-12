<?php

namespace App\Resource;

use App\Resource\AbstractResource;
use App\Parser;
use App\Entity\Districts;
use App\Entity\City;


class DownloadResource extends AbstractResource
{
    public function fetchAllFromUrl() 
    {
        foreach (CITIES as $city) {
            $countDistrict = 1;
            
            while ($countDistrict!=-1 && $countDistrict < 10) {
                $newUrl = $city['url'].$countDistrict;
                //echo $newUrl.'<br/>';
                try {
                    $response = $this->httpClient->get($newUrl);
                    $responseCode = $response->getStatusCode();
                    $responseBody = $response->getBody()->getContents();
                } catch (\Throwable $th) {
                    $responseCode = 404;
                    $countDistrict = -1;
                    $responseBody = "";
                    continue;
                }
                
                if($responseCode<400 && strlen(trim(strip_tags($responseBody))) > 0) {
                    $countDistrict++;
                    
                    echo $countDistrict.'<br/>';
                    if($city['name'] == "Gdańsk") {
                        if(!empty($city['url'])) {
                            $response = explode("|", strip_tags(str_replace('</div><div>', '|', $responseBody)));
                            $districtName = $response[0];

                            $districtArea = explode(":", str_replace(",",".",$response[1]));
                            $districtArea = floatval($districtArea[1]);
                            $districtPopulation = explode(":", str_replace(",",".",$response[2]));
                            $districtPopulation = floatval($districtPopulation[1]);
                                
                            $data = [
                                trim($districtName),
                                $districtArea, 
                                $districtPopulation
                            ];  
                        }
                    } else {
                        if(!empty($city['url'])) {
                            $response = $response->getBody();
                            $dom_document = new \DOMDocument();
                            @$dom_document->loadHTML($response);
                            $dom_xpath = new \DOMXpath($dom_document);
                            $district = $dom_xpath->query("//h3");
                            $elements = $dom_xpath->query("//td");

                            if (!is_null($district)) {
                                foreach ($district as $name) {
                                    $nodes = $name->childNodes;
                                    foreach ($nodes as $node) {
                                    $districtName = $node->nodeValue;
                                    }
                                }
                            }

                            if (!is_null($elements)) {
                                foreach ($elements as $element) {
                                    $nodes = $element->childNodes;
                                    foreach ($nodes as $key => $node) {
                                        if($key==2) {
                                            $districtData =  $node->nodeValue;
                                        }
                                    }
                                    break;
                                }
                            }
                            $districtData = explode(":",$districtData);
                            $parser = new Parser();
                            $districtName = str_replace("&nbsp;", " ", htmlentities($districtName));
                            $districtName = explode(" ",$districtName);
                            unset($districtName[0]);
                            unset($districtName[1]);
                            $districtName = implode(" ", $districtName);
                            
                            $data = [
                                trim(html_entity_decode($districtName)),
                                $parser->changeHectaresToKm(floatval(str_replace(",", ".",$districtData[1]))), 
                                floatval(str_replace(",", ".",$districtData[2]))
                            ];
                        }
                    }
                    
                    $cities = $this->entityManager->getRepository('App\Entity\City')->findOneBy(
                        array('name' => $city['name'])
                    );
                    if(!$cities) {
                        $cities = new City();
                        $cities->setName($city['name']);
                        $this->entityManager->persist($cities);
                        $this->entityManager->flush();
                    }

                    $districts = $this->entityManager->getRepository('App\Entity\Districts')->findOneBy(
                        array('name' => $data[0])
                    );

                    if(!$districts) {
                        $districts = new Districts();
                        $districts->setName($data[0]);
                        $districts->setArea($data[1]);
                        $districts->setPopulation($data[2]);
                        $districts->setCity($cities);
                        $this->entityManager->persist($districts);
                        $this->entityManager->flush();
                    } else {
                        $districts->setName($data[0]);
                        $districts->setArea($data[1]);
                        $districts->setPopulation($data[2]);
                        $districts->setCity($cities);
                        $this->entityManager->flush();
                    }
                } else {
                    break;
                }
            }
        }
        return $response;
    }
}