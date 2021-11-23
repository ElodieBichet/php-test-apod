<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApodController extends AbstractController
{
    protected $mediaRepository;

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * @Route("/", name="apod")
     */
    public function show(): Response
    {
        $picture = $this->mediaRepository->findOneBy(['media_type' => 'image'], ['date' => 'DESC']);

        return $this->render('apod/index.html.twig', [
            'picture' => $picture
        ]);
    }
}
