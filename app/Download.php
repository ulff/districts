<?php
namespace App;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Parser;

class Download
{
    private $httpClient;
    private $citiesUrls;
    public function __construct(ClientInterface $httpClient, $citiesUrls)
    {
        $this->httpClient = $httpClient;
        $this->citiesUrls = $citiesUrls;
    }

    public function fetchAll()
    {
        foreach ($this->citiesUrls as $city) {
            if($city['name'] == "Gdańsk") {
                if(!empty($city['url'])) {
                    //echo $value['url'];
                    $response = $this->httpClient->get($city['url'])->getBody()->getContents();
                    $response = explode("|", strip_tags(str_replace('</div><div>', '|', $response)));
                    //var_dump($response);
                }
            } else {
                if(!empty($city['url'])) {
                    //echo $value['url'];
                
                    $response = $this->httpClient->get($city['url'])->getBody();

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

                    $districtName = explode(" ",$districtName);
                    unset($districtName[0]);
                    //unset($districtName[1]);
                    
                    $data = [
                        $parser->changeHectaresToKm(floatval(str_replace(",", ".",$districtData[1]))), 
                        floatval(str_replace(",", ".",$districtData[2])),
                        $districtName[1]
                    ];

                   print_r($data);
                }
            }
        }
       
        return $response;
    }
}