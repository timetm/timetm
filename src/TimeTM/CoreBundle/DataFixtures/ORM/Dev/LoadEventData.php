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
use TimeTM\CoreBundle\Entity\Event;

// class LoadEventtData extends AbstractFixture implements OrderedFixtureInterface
// {
//     /**
//      * {@inheritDoc}
//      */
//     public function load(ObjectManager $manager)
//     {

//     	$events = array(
//     		0 => array(
//     			'title' => 'admin',
//     			'place' => 'admin@timetm.com',
//     			'desc' => '1234',
//     			'startdate' => '',
//     			'enddate' => '',
//     			'fullday' => ',',
//     			'agenda' => '',
//     			'participants' => ''

//     		),
//     		1 => array(
//     			'name' => 'frian',
//     			'email' => 'a@frian.org',
//     			'pwd' => '5678'
//     		),
//     	);

//     	foreach ( $events as $index => $eventData ) {

// 	    	// create user
// 	        $user = new User();
// 	        $user->setUsername($userData['name']);
// 	        $user->setEmail($userData['email']);
// 	        $user->setPlainPassword($userData['pwd']);
// 	        $user->setEnabled(true);

// 	        // add reference for further fixtures
// 	        $this->addReference('user'.$index, $user);

// 	        // create user default agenda
// 	        $agenda = new Agenda();
// 	    	$agenda->setUser($user);
// 	    	$agenda->setName('default');
// 	    	$agenda->setDescription('default');

// 	    	$manager->persist($agenda);
// 	    	$manager->flush();
//     	}

//     }

//     /**
//      * {@inheritDoc}
//      */
//     public function getOrder()
//     {
//     	return 3; // the order in which fixtures will be loaded
//     }
// }
