<?php

namespace App\Commands;

require __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Pepper extends Command
{
  protected static $defaultName = 'pepper';

  protected function configure()
  {
    $this->setDescription('Grabs a Git Repo and layers the directory & file changes on top of your Thiccnr project.')
         ->setHelp('Create a new basic Thiccnr project')
         ->addArgument('repoPath', InputArgument::REQUIRED, 'Path to Git Repository');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeln([
      "Adding the ingrediants..."
    ]);
  }
}
