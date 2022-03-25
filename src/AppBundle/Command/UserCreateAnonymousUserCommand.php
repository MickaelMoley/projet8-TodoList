<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @codeCoverageIgnore*
 */
class UserCreateAnonymousUserCommand extends Command
{
    protected static $defaultName = 'user:create-anonymous-user';
    protected static $defaultDescription = 'Add a short description for your command';

    /** @var EntityManagerInterface $this->entityManager */
    private $entityManager;
    /** @var UserPasswordHasherPassword $this->hasherPassword */
    private $hasherPassword;

    public function __construct($name = null, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userHasherPassword){

        $this->entityManager = $entityManager;
        $this->hasherPassword = $userHasherPassword;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $io->note("Création de l'utilisateur anonyme");
        //Si l'utilisateur n'existe pas alors on crée l'utilisateur anonyme
        if(!$this->checkIfAnonymousUserExist()){
            $io->note("L'utilisateur anonyme n'existe pas, il va être créer.");

            try {
                $this->createAnonymousUser();

                $io->success("L'utilisateur anonyme a été crée.");
                return Command::SUCCESS;
            } catch (\Exception $e) {
               
                $io->error("L'utilisateur anonyme n'a pas être créer.");
                return Command::FAILURE;
            }

        }
        $io->note("L'utilisateur anonyme existe.");
        return Command::SUCCESS;
        
    }

    /**
     * Fonction pour créer l'utilisateur anonyme
     */
    private function createAnonymousUser(){


        $anonymousUser = new User();
        $anonymousUser->setUsername('Anonyme');
        $anonymousUser->setEmail('anonymous@mail.anon');
        $password = $this->hasherPassword->hashPassword($anonymousUser, 'Anon_User_1234');
        $anonymousUser->setPassword($password);

        $this->entityManager->persist($anonymousUser);
        $this->entityManager->flush();

    }

    /**
     * Fonction permettant de vérifier si l'utilisateur est crée
     */
    private function checkIfAnonymousUserExist(){

        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'anonymous@mail.anon']);
    }
}
