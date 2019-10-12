<?php

namespace App\Resource;

use App\Resource\AbstractResource;

class DownloadResource extends AbstractResource
{
    /**
     * @param string|null $slug
     *
     * @return array
     */
    public function get($slug = null)
    {
        if ($slug === null) {
            $photos = $this->entityManager->getRepository('App\Entity\Districts')->findAll();
            $photos = array_map(
                function ($photo) {
                    return $photo->getArrayCopy();
                },
                $photos
            );

            return $photos;
        } else {
            $photo = $this->entityManager->getRepository('App\Entity\Districts')->findOneBy(
                array('slug' => $slug)
            );
            if ($photo) {
                return $photo->getArrayCopy();
            }
        }

        return false;
    }
}