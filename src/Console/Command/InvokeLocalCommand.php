<?php

namespace Shift\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Shift\Common\Loader\ShiftLoader;
use Shift\Common\Model\Input;
use Shift\Common\Model\Context;
use Shift\Common\Invoker\LocalInvoker;
use RuntimeException;

class InvokeLocalCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('invoke:local')
            ->setDescription('Invoke a local function')
            ->addArgument(
                'functionName',
                InputArgument::REQUIRED,
                'Function name to invoke'
            )
            ->addOption(
                'filename',
                '-f',
                InputOption::VALUE_REQUIRED,
                null
            )
            ->addOption(
                'username',
                '-u',
                InputOption::VALUE_REQUIRED,
                null
            )
            ->addOption(
                'input',
                'i',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                null
            )
            ->addOption(
                'config',
                'c',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                null
            )

        ;
    }

    protected function assign($assignments)
    {
        $res = [];
        foreach ($assignments as $assignment) {
            $part = explode('=', $assignment);
            if (count($part)!=2) {
                throw new RuntimeException("Assignment should be passed as `key=value`: " . $assignment);
            }
            $res[$part[0]] = $part[1];
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceFilename = $input->getOption('filename');
        if (!$serviceFilename) {
            $serviceFilename = 'service.json';
        }
        $username = $input->getOption('username');

        $loader = new ShiftLoader();
        $serviceDefinition = $loader->loadServiceDefinitionFile($serviceFilename);

        // === Construct input object ===
        $inputArray = $this->assign($input->getOption('input'));

        // === Construct config object ===
        $configArray = $this->assign($input->getOption('config'));

        // === Construct context object ===
        $contextArray = [];
        $contextArray['config'] = $configArray;
        $contextArray['username'] = $username;

        // === Invoke ===
        $invoker = new LocalInvoker($serviceDefinition, $contextArray);
        $outputArray = $invoker->invoke($input->getArgument('functionName'), $inputArray);

        $output->writeLn(json_encode($outputArray, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    }
}
