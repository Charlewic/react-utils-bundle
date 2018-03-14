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

class NpmRunCommand extends Command
{
    private $fileLocator;

    private $npmBinPath;

    public function __construct(FileLocator $fileLocator, $npmBinPath = "")
    {
        $this->fileLocator = $fileLocator;
        $this->npmBinPath = $npmBinPath;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('soreact_utils:npm:run')
            ->setDescription('Run npm command for bundle in option & command in option')
            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('bundle', InputArgument::REQUIRED),
                    new InputArgument('script', InputArgument::REQUIRED),
                    new InputOption('reactFolderPath', 'p', InputOption::VALUE_OPTIONAL),
                ))
            );;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
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
        if(empty($this->npmBinPath)){
            $output->writeln('Error npm_bin_path is empty.');
            return;
        }

        $output->writeln('npm run command in ['.$bundlePath.'] folder of ['.$input->getArgument('script').'] script.');
        $output->writeln('');
        $process = new Process('(cd \''.$bundlePath.'\' && \''.$this->npmBinPath.'\' run '.$input->getArgument('script').')');
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo $buffer;
            }
        });
    }
}
