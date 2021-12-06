<?php

namespace App\Manager;

use App\Entity\Media;
use App\Repository\MediaRepository;

class MediaManager
{
    private MediaRepository $mediaRepository;

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function findOneByDate(string $date): ?Media
    {
        return $this->mediaRepository->findOneBy(['date' => $date]);
    }
}
