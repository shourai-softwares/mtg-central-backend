<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateCommand extends Command
{
    private $em = null;
    private $encoder = null;
    protected static $defaultName = 'app:user:create';

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a user')
            ->addOption('super', 's', InputOption::VALUE_NONE, 'Creates a super user')
            ->addOption('admin', 'a', InputOption::VALUE_NONE, 'Creates an admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Creating a new User');

        $user = new User();

        if ($input->getOption('super')) {
            $io->text('The user will be a super user!');
            $user->setRoles([
                'ROLE_USER',
                'ROLE_ADMIN',
                'ROLE_SUPER_USER',
            ]);
        } elseif ($input->getOption('admin')) {
            $io->text('The user will be an admin!');
            $user->setRoles([
                'ROLE_USER',
                'ROLE_ADMIN',
            ]);
        }

        $email = $io->ask("What's the email?");
        $user->setEmail($email);

        $password = $io->askHidden("What's the password?");
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        $io->success('User saved');
    }
}
