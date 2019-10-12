<?php
namespace App\Action;

use App\Resource\DownloadResource;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DownloadAction
{
    private $downloadResource;

    public function __construct(DownloadResource $downloadResource)
    {
        $this->downloadResource = $downloadResource;
    }

    public function insertAll(Request $request, Response $response)
    {
        //$photos = $this->entityManager->getRepository('App\Entity\District')->findAll();
        $photos = $this->downloadResource->get();
        var_dump($photos);
        return $response->withStatus(404, 'No photo found with slug ');
    }

    // public function fetch($request, $response, $args)
    // {
    //     $photos = $this->em->getRepository('App\Entity\Photo')->findAll();
    //     $photos = array_map(
    //         function ($photo) {
    //             return $photo->getArrayCopy();
    //         },
    //         $photos
    //     );
    //     return $response->withJSON($photos);
    // }

    // public function fetchOne($request, $response, $args)
    // {
    //     $photo = $this->em->getRepository('App\Entity\Photo')->findBy(array('slug' => $args['slug']));
    //     if ($photo) {
    //         return $response->withJSON($photo->getArrayCopy());
    //     }
    //     return $response->withStatus(404, 'No photo found with slug '.$args['slug']);
    // }
}