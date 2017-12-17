<?php

namespace Shift\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Shift\Common\Loader\ShiftLoader;
use RuntimeException;

class ServiceCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('service')
            ->setDescription('Display service definition')
            ->addOption(
                'config',
                '-c',
                InputOption::VALUE_REQUIRED,
                null
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceFilename = $input->getOption('config');

        $loader = new ShiftLoader();
        $serviceDefinition = $loader->loadServiceDefinitionFile($serviceFilename);
        print_r($serviceDefinition);
    }
}
