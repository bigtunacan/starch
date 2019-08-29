<?php

namespace App\Commands;

require __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CreateProject extends Command
{
  protected static $defaultName = 'create';

  protected function configure()
  {
    $this->setDescription('Creates a new Thiccnr project.')
         ->setHelp('Create a new basic Thiccnr project')
         ->addArgument('projectName', InputArgument::REQUIRED, 'Project Name');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeln([
      "Adding the ingrediants..."
    ]);

    # TODO: Add support for getting a specific version before
    # we start having real releases.
    $dirName = 'thiccnr-master';
    //$zipFileName = $dirName . '.zip';
    $zipFileName = 'master.zip';

    $client = new \GuzzleHttp\Client(['base_uri' => 'https://github.com/bigtunacan/thiccnr/archive/']);
    $response = $client->request('GET', $zipFileName);

    $filesystem = new \Symfony\Component\Filesystem\Filesystem;
    $filesystem->dumpFile($zipFileName, $response->getBody());

    $zip = new \ZipArchive;
    $res = $zip->open($zipFileName);
    $tmpPath = './thiccnrprojecttmp';
    if($res === TRUE) {
      $zip->extractTo($tmpPath);
      $zip->close();
    } else {
      echo 'An error has occurred';
    }

    $output->writeln([
      "Warming up the soup..."
    ]);
    $filesystem->remove($zipFileName);
    $filesystem->mirror($tmpPath . '/' . $dirName, $input->getArgument('projectName'));
    $filesystem->remove($tmpPath);

    // Install Composer dependencies
    // TODO: Check if this works on Windows...
    chdir($input->getArgument('projectName'));
    $this->runProcess(['composer', 'install']);
    $filesystem->copy('.env.example', '.env');

    $output->writeln([
      "Dinner is served..."
    ]);

  }

  protected function runProcess($cmd) {
    $process = new Process($cmd);
    $process->run();

    if(!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

    echo $process->getOutput();
  }
}
