<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 23-12-16
 * Time: 22:37
 */

namespace CoderGeek\Bundle\MaintenanceBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class LockCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('maintenance:lock')
            ->setDescription('Set the application in maintenance mode')
//            ->addArgument()
            ->setHelp(<<<EOT
    It is possible to call the lock without a warning message with:

    <info>%command.full_name% --no-interaction</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->isInteractive()) {
            if (!$this->askConfirmation('WARNING! Are you sure you wish to lock the application? (y/n)', $input, $output)) {
                $output->writeln('<error>Maintenance cancelled</error>');
                return;
            }
        }

        // lock it up

        $output->writeln('<info>Locked!</info>');
    }

    /**
     * This method ensure that we stay compatible with symfony console 2.3 by using the deprecated dialog helper
     * but use the ConfirmationQuestion when available.
     *
     * @param $question
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function askConfirmation($question, InputInterface $input, OutputInterface $output) {
        if (!$this->getHelperSet()->has('question')) {
            return $this->getHelper('dialog')
                ->askConfirmation($output, '<question>' . $question . '</question>', 'y');
        }
        return $this->getHelper('question')
            ->ask($input, $output, new \Symfony\Component\Console\Question\ConfirmationQuestion($question));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $formatter = $this->getHelperSet()->get('formatter');

        $output->writeln(array(
            '',
            $formatter->formatBlock('You are about to launch maintenance', 'bg=red;fg=white', true),
            '',
        ));
    }

    /**
     * This method ensure that we stay compatible with symfony console 2.3 by using the deprecated dialog helper
     * but use the ConfirmationQuestion when available.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param $question
     * @param $validator
     * @param int $attempts
     * @param null $default
     * @return mixed
     */
    protected function askAndValidate(InputInterface $input, OutputInterface $output, $question, $validator, $attempts = 1, $default = null) {
        if (!$this->getHelperSet()->has('question')) {
            return $this->getHelper('dialog')
                ->askAndValidate($output, $question, $validator, $attempts, $default);
        }
        $question = new \Symfony\Component\Console\Question\Question($question, $default);
        $question->setValidator($validator);
        $question->setMaxAttempts($attempts);
        return $this->getHelper('question')
            ->ask($input, $output, $question);
    }
}
