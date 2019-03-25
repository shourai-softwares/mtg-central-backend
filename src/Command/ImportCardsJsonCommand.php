<?php

namespace App\Command;

use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCardsJsonCommand extends Command
{
    private $em = null;

    protected static $defaultName = 'app:import-cards-json';

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import JSON containing cards data')
            ->addArgument('location', InputArgument::REQUIRED, 'File location')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $location = $input->getArgument('location');

        $cards = \JsonMachine\JsonMachine::fromFile($location);

        foreach ($cards as $id => $card) {
            $cardObj = (new Card())
                ->setName($card['name'])
                ->setType($card['type'])
            ;
            $this->em->persist($cardObj);
        }
        $this->em->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
