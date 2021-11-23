<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER", message="You have to be authenticated to see this page")
     */
    public function show(): Response
    {
        $picture = $this->mediaRepository->findOneBy(['media_type' => 'image'], ['date' => 'DESC']);

        return $this->render('apod/index.html.twig', [
            'picture' => $picture
        ]);
    }
}
