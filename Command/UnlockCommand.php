<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 7-1-17
 * Time: 19:30
 */

namespace CoderGeek\Bundle\MaintenanceBundle\Command;

use SIT\Tootal\ParameterToolBundle\Drivers\FileDriver;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class UnlockCommand
 *
 * @package SIT\Tootal\ParameterToolBundle\Command
 */
class UnlockCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('maintenance:unlock')
            ->setDescription('Turn off maintenance mode for the Application -- Unlock it')
//            ->addArgument()
            ->setHelp(
                <<<EOT
                    It is possible to call the unlock without a warning message with:

    <info>%command.full_name% --no-interaction</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->confirmUnlock($input, $output)) {
            return;
        }
        $container = $this->getApplication()->getKernel()->getContainer();
        $driver = new FileDriver($container);
        $driver->unlock();
        $output->writeln('<info>Unlocked!</info>');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function confirmUnlock(InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');
        if ($input->getOption('no-interaction', false)) {
            $confirmation = true;
        } else {
            // confirm
            $output->writeln(array(
                '',
                $formatter->formatBlock('You are about to unlock your server!', 'bg=green;fg=white', true),
                '',
            ));
            $confirmation = $this->askConfirmation(
                'WARNING! Are you sure you wish to continue? (y/n) ',
                $input,
                $output
            );
        }
        if (!$confirmation) {
            $output->writeln('<error>Action cancelled!</error>');
        }

        return $confirmation;
    }

    /**
     * This method ensure that we stay compatible with symfony console 2.3 by using the deprecated dialog helper
     * but use the ConfirmationQuestion when available.
     *
     * @param string          $question
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function askConfirmation($question, InputInterface $input, OutputInterface $output)
    {
        if (!$this->getHelperSet()->has('question')) {
            return $this->getHelper('dialog')->askConfirmation($output, '<question>'.$question.'</question>', 'y');
        }

        return $this->getHelper('question')
            ->ask($input, $output, new ConfirmationQuestion($question));
    }
}
