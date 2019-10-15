<?php
namespace App\Action;

use App\Resource\DistrictsResource;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class DistrictsAction
{
    private $districtsResource;
    private $twig;

    public function __construct(DistrictsResource $districtsResource, Twig $view)
    {
        $this->districtsResource = $districtsResource;
        $this->view = $view;
    }

    public function fetchDistrict(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();
        if(!empty($params)) {
            if(isset($params['sort'])) {
                $filters = $params;
                unset($filters['sort']);
                unset($filters['order']);
                $fetch = $this->districtsResource->sortBy($args['cityName'], $params['sort'], $params['order'], $filters); 
            } else {
                $fetch = $this->districtsResource->filtr($params, $args['cityName']); 
            }
        } else {
            $cityName = (isset($args['cityName'])) ?? 'all';
            $fetch = $this->districtsResource->fetchByName($cityName); 
        }
           
        $header = $request->getHeader('Accept');
        if($header && $header[0] == 'application/json') {
            $write = json_encode($fetch[1]);
            $response->getBody()->write($write);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($fetch[0]);
        } else {
            return $this->view->render($response, 'districts.html.twig',[
                'districts' => $fetch[1],
            ]);
        } 
    }

    public function addDistrict(Request $request, Response $response, $args)
    {
        $header = $request->getHeader('Accept');
        if($header && $header[0] == 'application/json') {
            $requestBody = json_decode($request->getBody());
            if(!is_null($requestBody) && sizeof($requestBody->districts) == 1) {
                foreach ($requestBody->districts as $arg) {
                    $add = $this->districtsResource->add($arg->name, $arg->population, $arg->area, $arg->city);
                }
            } else {
                $add = array(404, 'Bad Request');
            }
    
            $write = json_encode($add[1]);

            $response->getBody()->write($write);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($add[0]);
        } else {
            $data = $request->getParsedBody();
            if(!empty($data) && is_numeric($data['population']) && is_numeric($data['area']) && !empty($data['city']) && !empty($data['name'])) {
                $add = $this->districtsResource->add($data['name'], $data['population'], $data['area'], $data['city']);
                if($add[0]<400) {
                    return $response->withStatus(302)->withHeader('Location', '/districts');
                } else {
                    return $this->view->render($response, 'edit.html.twig',[
                        'name'          => $data['name'],
                        'city'          => $data['city'],
                        'population'    => $data['population'],
                        'area'          => $data['area'],
                        'districtId'    => '',
                        'message'       => 'Wystąpił błąd'
                    ]);   
                }
            } else {
                if((!is_numeric($data['population']) || !is_numeric($data['area'])) && !empty($data)) {
                    return $this->view->render($response, 'edit.html.twig',[
                        'name'          => $data['name'],
                        'city'          => $data['city'],
                        'population'    => $data['population'],
                        'area'          => $data['area'],
                        'districtId'    => '',
                        'message'       => 'Błąd. Uzupełnij pola.'
                    ]);  
                } else {
                    return $this->view->render($response, 'edit.html.twig',[
                        'name'          => '',
                        'city'          => '',
                        'population'    => '',
                        'area'          => '',
                        'districtId'    => '',
                    ]);  
                }
            }
        }
    }

    public function deleteDistrict(Request $request, Response $response, $args)
    {
        $delete = $this->districtsResource->delete($args['districtId']); 

        $header = $request->getHeader('Accept');
        if($header && $header[0] == 'application/json') {
            $write = json_encode($delete[1]);
            $response->getBody()->write($write);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($delete[0]);
        } else {
            if($delete[0]<400) {
                return $response->withStatus(302)->withHeader('Location', '/districts', ['message' => 'Usunięty']);
            }
        }
    }

    public function editDistrict(Request $request, Response $response, $args)
    {
        $header = $request->getHeader('Accept');
        $fetch = $this->districtsResource->fetchById($args['districtId']);
        if($header && $header[0] == 'application/json') {
            $write = json_encode($fetch[1]);

            $response->getBody()->write($write);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($fetch[0]);
        } else {
            
            return $this->view->render($response, 'edit.html.twig',[
                'name'          => $fetch[1][0]['name'],
                'city'          => $fetch[1][0]['city'],
                'population'    => $fetch[1][0]['population'],
                'area'          => $fetch[1][0]['area'],
                'districtId'    => $args['districtId'],
            ]);  
        }

    }

    public function updateDistrict(Request $request, Response $response, $args)
    {
        $header = $request->getHeader('Accept');
        if($header && $header[0] == 'application/json') {
            $requestBody = json_decode($request->getBody());
            if(!is_null($requestBody) && sizeof($requestBody->districts) == 1) {
                foreach ($requestBody->districts as $arg) {
                    $add = $this->districtsResource->update($arg->name, $arg->population, $arg->area, $arg->city);
                }
            } else {
                $add = array(404, 'Bad Request');
            }
            $write = json_encode($add[1]);
            $response->getBody()->write($write);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($add[0]);
        } else {
            $data = $request->getParsedBody();
            if(isset($args['districtId']) && sizeof($data)>1) {
                if(is_numeric($data['population']) && is_numeric($data['area']) && !empty($data['city']) && !empty($data['name'])) {
                    $fetch = $this->districtsResource->update($data['name'], $data['population'], $data['area'], $data['city'], $args['districtId']);
                    if($fetch[0]==202) {
                        return $response->withStatus(302)->withHeader('Location', '/districts');
                     } else {
                        return $this->view->render($response, 'edit.html.twig',[
                            'name'          => $data['name'],
                            'city'          => $data['city'],
                            'population'    => $data['population'],
                            'area'          => $data['area'],
                            'districtId'    => $args['districtId'],
                            'message'       => 'Wystąpił błąd'
                        ]);  
                     }
                } else {
                    return $this->view->render($response, 'edit.html.twig',[
                        'name'          => $data['name'],
                        'city'          => $data['city'],
                        'population'    => $data['population'],
                        'area'          => $data['area'],
                        'districtId'    => $args['districtId'],
                        'message'       => 'Uzupełnij prawidłowo pola'
                    ]);  
                }
            } else {
               return $response->withStatus(302)->withHeader('Location', '/districts');
            }
        }
    }
}