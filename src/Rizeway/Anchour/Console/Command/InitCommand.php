<?php
namespace Rizeway\Anchour\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    public function __construct($name = null)
    {
        parent::__construct('init');

        $this
            ->setDescription('Create a default .anchour file')
            ->addOption('force', 'f', InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (file_exists('.anchour') && !$input->getOption('force'))
        {
            throw new \RuntimeException('File .anchour already exists. To replace it, use the --force/-f option');
        }

        $file = new \SplFileObject('.anchour', 'w+');
        $file->fwrite($this->getTemplate());
    }

    protected function getTemplate() {
        return <<<YAML
anchour:
    # Here you can define your connections
    connections:
        MySSH:
            type: "ssh"
            options:
                username: %username%
                password: %password%
                host: %host%

    # Here you can define your commands
    commands:

        # Default deploy command using rsync
        deploy:
            description: "Deploy your project using rsync"        

            # Here you can define your command steps
            steps:
                -
                    type: "echo"
                    options:
                        message: "<info>Starting Rsync</info>"

                -
                    type: "rsync"
                    options:
                        key_file: %rsync_key_file%
                    connections:
                        connection: MySSH

                -
                    type: "echo"
                    options:
                        message: "<info>Rsync Done</info>"

        # Default save revision command
        save:
            description: "save a revision of your remote directory"
            steps:
                -
                    type: "ssh"
                    options:
                        commands:
                            - mkdir -p .anchour/revisions/%revision_name%
                            - rsync --progress -a --exclude '.anchour/' ./ .anchour/revisions/%revision_name%
                    connections:
                        connection: MySSH
                        

        # Default rollback revision command
        rollback:
            description: "Rollback you project to a saved revision"
            steps:
                -
                    type: "ssh"
                    options:
                        commands:
                            - cp -aR .anchour/revisions/%revision_name% ./
                    connections:
                        connection: MySSH

        # Default command to list your saved revisions
        revisions:
            description: "List your saved revisions"
            steps:
                -
                    type: "ssh"
                    options:
                        commands:
                            - ls .anchour/revisions
                    connections:
                        connection: MySSH

YAML;
    }
}
