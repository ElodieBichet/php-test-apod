<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Service\CallApodAPIService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MediaFixtures extends Fixture
{
    protected CallApodAPIService $callApodAPIService;

    public function __construct(CallApodAPIService $callApodAPIService)
    {
        $this->callApodAPIService = $callApodAPIService;
    }

    public function load(ObjectManager $manager)
    {
        // Get APOD of the 5 last days
        for ($d = 5; $d >= 0; $d--) {
            $date = date('Y-m-d', strtotime("- $d day"));
            /** @var Media */
            $media = $this->callApodAPIService->createMediaFromAPOD($date);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
