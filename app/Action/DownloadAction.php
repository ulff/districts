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

    public function insertFromUrl(Request $request, Response $response)
    {
        $download = $this->downloadResource->getNew();
        $response->getBody()->write(json_encode('Download'));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
    }
}