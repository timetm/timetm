<?php 

namespace TimeTM\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
// use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class TestCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
		->setName('ttm:test:dummy')
		->setDescription('TimeTM dummy test command')
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
// 		$verbosity = $output->getVerbosity();

		// get entity manager
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');

		/**
		 * define query
		 * 
		 */
// 		$qb = $em->createQueryBuilder();
// 		$qb
// 			->select('')
// 			->from('', '')
// 		;

		// create and execute query
// 		$orders = $qb->getQuery()->getResult();

		// get num results
		$nbResults = 0 ; // count($orders);

		if ( $nbResults ) {
			$output->writeln('<info>found ' . $nbResults . ' orders to move ...</info>');
		}



		// if --force persist
		if ($input->getOption('force')) {
			$em->flush();
		}

		
		// 
		if ( $nbResults ) {
			if ($input->getOption('force')) {
				$output->writeln('done.');
			}
			else {
				$output->writeln('<warning>nothing done ! use --force to apply changes</warning>');
			}
		}
		else {
			$output->writeln('nothing to do');
		}
	}
}
