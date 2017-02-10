<?php

namespace Snapcart\APIBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SnapcartUserCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('snapcart:user:create')
            ->setDescription('Create a user')
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'Username')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getOption('username');
        $password = $input->getOption('password');

        if ($username != '' && $password != '') {
            $this->getContainer()->get('snapcart.user_service')->saveUser($username, $password);
        }

        $output->writeln('Command result.');
    }

}
