<?php
namespace App\Action;

use App\Resource\DistrictsResource;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Resource\DistricstResouorse;
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
        if(!empty($params)){
            if(isset($params['sort'])) {
                $fetch = $this->districtsResource->sortBy($args['cityName'], $params['sort'], $params['order']); 
            } else {
                $fetch = $this->districtsResource->filtr($params, $args['cityName']); 
            }

        } else {
            $fetch = $this->districtsResource->fetchByName($args['cityName']); 
        }
           
        $header = $request->getHeader('Content-Type');
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
    }

    public function deleteDistrict(Request $request, Response $response, $args)
    {
        $delete = $this->districtsResource->delete($args['districtId']); 
        $write = json_encode($delete[1]);
        $response->getBody()->write($write);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($delete[0]);
    }
}