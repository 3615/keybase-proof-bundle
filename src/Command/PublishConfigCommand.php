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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;
use 💻3615\Keybase\ProofBundle\Exception\MissingOrInvalidInputsException;
use 💻3615\Keybase\ProofBundle\Validator\RemoteConfigValidation;

class PublishConfigCommand extends Command
{
    private const KEYBASE_CONVERSATION = 'mlsteele';
    private const KEYBASE_CONVERSATION_DRYRUN = 'KEYBASE_USERNAME_PLACEHOLDER';
    private const MESSAGE_FIRST_VERSION = <<<MSG
Hi Miles,

I just integrate Keybase proof on my website :the_horns:
Here is my config:
```
{keybase_config}
```
You can also found it here: {keybase_config_url}

I hope everything is well configured :innocent:

Thank you
MSG;
    private const MESSAGE_NEXT_VERSIONS = <<<MSG
Hey Miles, me again

I just upgraded my Keybase proof configuration :nerd_face:
Here is my new config:
```
{keybase_config}
```
You can also found it here: {keybase_config_url}

Thank you
MSG;

    protected static $defaultName = 'keybase:proof:publish-config';
    private $remoteConfigValidation;
    private $router;
    private $serializer;
    private $keybaseProofConfig;
    private $keybaseUsername;

    public function __construct(
        RemoteConfigValidation $remoteConfigValidation,
        RouterInterface $router,
        SerializerInterface $serializer,
        array $keybaseProofConfig,
        ?string $keybaseUsername
    ) {
        $this->remoteConfigValidation = $remoteConfigValidation;
        $this->router = $router;
        $this->serializer = $serializer;
        $this->keybaseProofConfig = $keybaseProofConfig;
        $this->keybaseUsername = $keybaseUsername;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Publish your Keybase proof configuration by sending it to @'.self::KEYBASE_CONVERSATION.' <miles@keyba.se>')
            ->addOption('no-validation', 'f', InputOption::VALUE_NONE, 'Skip validation process')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Send the configuration to yourself')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $run = true;
        $conversation = self::KEYBASE_CONVERSATION;

        if ($input->getOption('dry-run')) {
            $conversation = $this->keybaseUsername;
            if (!$this->keybaseUsername) {
                $io->warning('`keybase_proof.my_username` is not configured. Replaced by `'.self::KEYBASE_CONVERSATION_DRYRUN.'`');
                $run = false;
                $conversation = self::KEYBASE_CONVERSATION_DRYRUN;
            }
        }

        if (!$input->getOption('no-validation')) {
            try {
                ($this->remoteConfigValidation)();
                $io->writeln('Configuration valid: <info>OK</info>');
            } catch (MissingOrInvalidInputsException $e) {
                $io->error('Command aborted : invalid configuration');
                $output->write($e->getMessage());

                return 1;
            }
        }

        $message = 1 === $this->keybaseProofConfig['version']
            ? self::MESSAGE_FIRST_VERSION
            : self::MESSAGE_NEXT_VERSIONS;
        $message = \str_replace(
            [
                '{keybase_config}',
                '{keybase_config_url}',
            ],
            [
                $this->serializer->serialize($this->keybaseProofConfig, 'json', ['json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE]),
                $this->router->generate('keybase-proof-config', [], UrlGeneratorInterface::ABSOLUTE_URL).'?pretty',
            ],
            $message
        );
        $message = \preg_replace("`\n[^\n]+: https?://localhost/[^\n]+\n`", "\n", $message);

        $keybaseChatCmd = ['keybase', 'chat', 'send', $conversation, $message];

        if ($run && \class_exists(Process::class)) {
            if (!$input->getOption('dry-run')) {
                $io->writeln('Send the configuration to <options=bold>keybase.io/'.$conversation.'</>: <comment>on hold</comment>');
                $question = new ConfirmationQuestion(
                    'Continue with this action ? (y/n) ',
                    true
                );

                if (!$this->getHelper('question')->ask($input, $output, $question)) {
                    $io->writeln('Configuration published: <comment>KO</comment>');

                    return 2;
                }
            }

            ($p = new Process($keybaseChatCmd))->run();
            $io->writeln('Configuration published: <info>OK</info>');

            return;
        }

        $this->writeCmd($keybaseChatCmd, $io);
    }

    private function writeCmd(array $cmd, SymfonyStyle $io): void
    {
        $cmd = \implode(' ', \array_map('escapeshellarg', $cmd));

        $io->writeln('<info>Command to run:</info>');
        $io->writeln($cmd);
    }
}
