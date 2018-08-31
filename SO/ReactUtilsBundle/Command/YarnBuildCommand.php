<?php

namespace SO\ReactUtilsBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class YarnBuildCommand extends Command
{
    private $fileLocator;

    private $yarnBinPath;

    public function __construct(FileLocator $fileLocator, $yarnBinPath = "")
    {
        $this->fileLocator = $fileLocator;
        $this->yarnBinPath = $yarnBinPath;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('soreact_utils:yarn:build')
            ->setDescription('Install React dependencies with utils configured (npm or yarn if configured) for a bundle passed in option')
            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('bundle', InputArgument::REQUIRED),
                    new InputArgument('build-name', InputArgument::REQUIRED),
                    new InputOption('timeout', 't', InputOption::VALUE_OPTIONAL),
                    new InputOption('reactFolderPath', 'p', InputOption::VALUE_OPTIONAL),
                ))
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeout = $input->getOption('timeout');
        if (empty($timeout)) {
            $timeout = 300;
        }

        $reactFolderPath = $input->getOption('reactFolderPath');
        if (empty($reactFolderPath)) {
            $reactFolderPath = 'ReactComponent';
        }

        try{
            $bundlePath = $this->fileLocator->locate($input->getArgument('bundle').'/'.$reactFolderPath);
        }catch(\Exception $e){
            $output->writeln('Error Bundle path not found with ['.$input->getArgument('bundle').'/'.$reactFolderPath.'] path');
            return;
        }
        if(empty($this->yarnBinPath)){
            $output->writeln('Error yarn_bin_path is empty.');
            return;
        }

        $output->writeln('Start Yarn Build ['.$input->getArgument('build-name').'] in ['.$bundlePath.'] folder');
        $output->writeln('');

        $process = new Process('(cd \''.$bundlePath.'\' && \''.$this->yarnBinPath.'\' '.$input->getArgument('build-name').')');
        $process->setTimeout($timeout);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo $buffer;
            }
        });

        $output->writeln('');
        $output->writeln('Yarn Build Successful');
    }
}
