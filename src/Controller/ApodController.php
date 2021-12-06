<?php

namespace App\Controller;

use App\Manager\MediaManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApodController extends AbstractController
{
    protected $mediaManager;

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    /**
     * @Route("/", name="apod")
     * @IsGranted("ROLE_USER", message="You have to be authenticated to see this page")
     */
    public function show(): Response
    {
        $picture = $this->mediaManager->findLastImageMedia();

        return $this->render('apod/index.html.twig', [
            'picture' => $picture
        ]);
    }
}
