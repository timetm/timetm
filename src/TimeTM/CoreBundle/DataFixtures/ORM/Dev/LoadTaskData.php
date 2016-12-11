<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\DataFixtures\ORM\dev;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TimeTM\CoreBundle\Entity\Task;

/**
 * User fixture
 *
 * @author André Friedli <a@frian.org>
 */
class LoadTaskData extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {

    	/**
    	 * Add users
    	 */
    	for ($i = 0; $i < 100; $i++) {

	    	// create user
	        $task = new Task();
            $task->setTitle('Task title ' . $i);

            // create initial date : today
            $date = date('d-m-Y');

            // create due date
            $datetime = new \DateTime($date);
            $datetime->modify("+$i day");

            // print $datetime->format('d-m-Y H:i') . "\n";

            $task->setDuedate($datetime);

            if ( $i % 3 == 0 ) {
                $task->setUserassigned($this->getReference('user0'));
            }
            elseif ($i % 3 == 1) {
                $task->setUserassigned($this->getReference('user1'));
            }

	        // add reference for further fixtures
	        $this->addReference('task'.$i, $task);

	    	$manager->persist($task);
	    	$manager->flush();
    	}
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder() {
    	return 6; // the order in which fixtures will be loaded
    }
}
