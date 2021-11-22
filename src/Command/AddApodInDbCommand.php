<?php

namespace App\Command;

use App\Entity\Media;
use App\Repository\MediaRepository;
use App\Service\CallApodAPIService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddApodInDbCommand extends Command
{
    private $entityManager;
    private $mediaRepository;
    private $callApodAPIService;

    protected static $defaultName = 'app:add-apod-in-db';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(
        EntityManagerInterface $entityManager,
        MediaRepository $mediaRepository,
        CallApodAPIService $callApodAPIService
    ) {
        $this->entityManager = $entityManager;
        $this->mediaRepository = $mediaRepository;
        $this->callApodAPIService = $callApodAPIService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Get the picture of the day from APOD API and save it in database')
            ->setHelp('This command allows you to get the Astronomy Picture Of the Day (from APOD API) and save it in the Database.')
            ->addArgument('date', InputArgument::OPTIONAL, 'Date of the APOD to save in Database.', date('Y-m-d'))
            ->addOption(
                'replace',
                null,
                InputOption::VALUE_NONE,
                'With this option, if a picture already exists in the DB for the given date, it will be removed and replaced with the new one.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $date = $input->getArgument('date');

        $search = $this->mediaRepository->findOneBy(['date' => $date]);
        $media = $this->callApodAPIService->createMediaFromAPOD($date);

        if ($search && $input->getOption('replace')) {
            $this->entityManager->remove($search);
            $this->entityManager->flush();
        }
        if (!$search || $input->getOption('replace')) {
            $this->entityManager->persist($media);
        }

        $this->entityManager->flush();

        $io->success("The picture of the day $date has been added to the database");

        return Command::SUCCESS;
    }
}
