<?php

/*
 * This file is part of the 3615/keybase-proof-bundle project.
 *
 * (c) Romain Gautier <gh@romain.sh>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace 💻3615\Keybase\ProofBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use 💻3615\Keybase\ProofBundle\Exception\MissingOrInvalidInputsException;
use 💻3615\Keybase\ProofBundle\Validator\RemoteConfigValidation;

class ValidateConfigCommand extends Command
{
    protected static $defaultName = 'keybase:proof:validate-config';
    private $remoteConfigValidation;

    public function __construct(RemoteConfigValidation $remoteConfigValidation)
    {
        $this->remoteConfigValidation = $remoteConfigValidation;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Validate your Keybase proof integration')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            ($this->remoteConfigValidation)();
            $io->success('Keybase proof configuration valid.');
        } catch (MissingOrInvalidInputsException $e) {
            $io->error('Invalid Keybase proof configuration.');
            $output->write($e->getMessage());
        }
    }
}
