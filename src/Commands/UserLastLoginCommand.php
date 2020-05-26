<?php
namespace App\Command;

use DateTimeZone;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserLastLoginCommand extends Command {
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:check-last-login';

    public function __construct(EntityManagerInterface $em) {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure() {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Checar ultimo login del usuario')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to checar logins Xd')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $users = $this->em->getRepository(User::class)->findAll();
        $todayDate = new \DateTime('now', new DateTimeZone('UTC')); $todayDate->setTime(0,0);

        foreach ($users as $user) {
            $lastLogin = $user->getLastLogin();
            $months = ($lastLogin->diff($todayDate))->months;

            if ($months >= 6) {
                $user->setIsActive(false);
                $this->em->persist($user);
            }
        }

        $this->em->flush();

        return 0;
    }
}
