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
        $download = $this->downloadResource->get();
        var_dump($download);
        return $response->withStatus(404, 'No photo found with slug ');
    }

    public function insertFromUrl(Request $request, Response $response)
    {
        $download = $this->downloadResource->getNew();

        return $response->withStatus(200, 'OK');
    }
}