<?php

namespace TimeTM\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
// use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class EventNotificationsCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
		->setName('ttm:event:notifications')
		->setDescription('TimeTM command to send emails with next events')
		->setHelp("\nDummy help text for dummy test command\n")
		->addOption('force', null, InputOption::VALUE_NONE, 'Si définie, les modifications sont appliquées')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// add style
		$style = new OutputFormatterStyle('red');
		$output->getFormatter()->setStyle('warning', $style);

		// get verbosity
		$verbosity = $output->getVerbosity();

		$sendEmails = $input->getOption('force');

		// get tomorrow's date
		$tomorrow = new \DateTime('tomorrow');

		// create array with tomorrow and after tomorrow
		$days = array();

		\array_push($days, $tomorrow->format('Y-m-d'));
		\array_push($days, $tomorrow->modify('+1 day')->format('Y-m-d'));

		// get translator
		$translator = $this->getContainer()->get('translator');

		$translator->setLocale('fr');

		// get entity manager
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');

		// 
		$qb = $em->createQueryBuilder();

		// get all users
		$users = $qb
		->select('u')
		->from('TimeTMCoreBundle:User', 'u')
		->getQuery()
		->execute();

		$globalHasEvents = 0;

		foreach ($users as $user) {

			$hasEvents = 0;
			$events = array();

			if ($verbosity > 1) {
				$output->writeln('user ' . $user->getUsername());
			}

			foreach ( $days as $index=>$day ) {

				$qb = $em->createQueryBuilder();

				$results = $qb
					->select('e')
					->from('TimeTMCoreBundle:Event', 'e')
					->leftjoin('e.agenda', 'a')
					->leftjoin('a.user', 'u')
					->where('e.startdate = :day')
					->andWhere('a.user = :user')
					->setParameter('day', $day)
					->setParameter('user', $user)
					->getQuery()
					->execute();

				$nbResults = count($results);

				if ($index == 0) {
					\array_push($events, $results);
				}
				else if ($index == 1) {
					\array_push($events, $results);
				}

				if ( $nbResults > 0 ) {
					$globalHasEvents = $hasEvents = 1;
				}
			}

			if ($hasEvents) {

				if ($verbosity > 1) {
					$output->writeln('<info>  sending email to ' . $user->getUsername() . ' at ' . $user->getEmail() . ' ...</info>');
				}

				$twig = $this->getContainer()->get('templating');

				if ($sendEmails) {
					$message = \Swift_Message::newInstance()
						->setCharset('UTF-8')
						->setContentType('text/html')
						->setSubject('TimeTM notification')
						->setFrom('timetmbot@example.com')
						->setTo($user->getEmail());

					$cid = $message->embed(\Swift_Image::fromPath('web/img/logo.png'));

					$message->setBody($twig->render(
						'TimeTMCoreBundle:Default:eventNotifications.html.twig',
						array('events' => $events, 'cid' => $cid, 'days' => $days)))
					;

					$cid = $message->embed(\Swift_Image::fromPath('web/img/logo.png'));

					$mailer = $this->getContainer()->get('mailer');

					$mailer->send($message);
				}
			}
			else {
			
				if ($verbosity > 1) {
					$output->writeln('  no event');
				}
			}
		}
		

		// final output
		if ( $globalHasEvents ) {
			if ($sendEmails) {
				$output->writeln('done.');
			}
			else {
				$output->writeln('<warning>nothing done ! use --force to send emails</warning>');
			}
		}
		else {
			$output->writeln('nothing to do');
		}
	}
}
