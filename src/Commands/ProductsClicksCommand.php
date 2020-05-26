<?php
namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command {
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:check-products-clicks';

    public function __construct(EntityManagerInterface $em) {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure() {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Checar clicks de productos.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to checar clicks Xd')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $products = $this->em->getRepository(Product::class)->findAll();

        foreach ($products as $product) {
            if ($product->getClicks() < 100) {
                $product->setIsVisible(false);
                $this->em->persist($product);
            }
        }

        $this->em->flush();

        return 0;
    }
}
