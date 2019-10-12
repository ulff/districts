<?php
namespace App\Resource;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\ClientInterface;

abstract class AbstractResource
{

    protected $entityManager = null;
    protected $httpClient = null;

    public function __construct(EntityManager $entityManager, ClientInterface $httpClient)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }
}