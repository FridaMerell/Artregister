<?php

namespace App\Command;

use App\Service\FromCsv;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import')]
class Import extends Command
{
    function __construct(private FromCsv $csv, $name = null)
    {
        parent::__construct($name);
    }

    function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED);
    }

    function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');
        $this->csv->setFile($file);
        $this->csv->handleFile();

        return Command::FAILURE;
    }
}