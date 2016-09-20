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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TimeTM\CoreBundle\Entity\Event;

class LoadEventData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
    * @var ContainerInterface
    */
    private $container;

    /**
    * {@inheritDoc}
    */
    public function setContainer(ContainerInterface $container = null)
    {
       $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

    	$events = array(
    		0 => array(
    			'title' => 'event title',
    			'place' => 'office',
    			'desc' => 'event description',
    			'startdate' => '',
    			'enddate' => '',
    			'fullday' => '0',
    			'agenda' => '',
    			'participants' => ''

    		),
    	);

        // get helper for duration
        $helper = $this->container->get('timetm.event.helper');

        for ($i = 0; $i < 5; $i++) {

            for ($j = 0; $j < 5; $j++) {

            	foreach ( $events as $index => $eventData ) {

        	    	// create event
        	        $event = new Event();
                    $event->setTitle($eventData['title']);
                    $event->setPlace($eventData['place']);
                    $event->setDescription($eventData['desc']);
                    $event->setFullday($eventData['fullday']);
                    $event->setAgenda($this->getReference('userAgenda0'));

                    // create initial date : today 10h00
                    $date = date('d-m-Y') . " " . (10 + $i) .  ":00";

                    // create start date
                    $datetime = new \DateTime($date);
                    $datetime->modify("+$j day");
                    $event->setStartdate($datetime);

                    // create endate
                    $datetime = new \DateTime($date);
                    $datetime->modify("+$j day");
                    $datetime->modify('+1 hour');
                    $event->setEnddate($datetime);

                    // set duration
                    $helper->setEventDuration($event);

        	    	$manager->persist($event);
        	    	$manager->flush();
            	}
            }
        }


    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
    	return 5; // the order in which fixtures will be loaded
    }
}
