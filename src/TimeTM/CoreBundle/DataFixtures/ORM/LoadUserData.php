<?php
// src/TimeTM/UserBundle/DataFixtures/ORM/LoadUserData.php

namespace TimeTM\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TimeTM\CoreBundle\Entity\User;
use TimeTM\CoreBundle\Entity\Agenda;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    	
    	$users = array(
    		0 => array(
    			'name' => 'admin',
    			'email' => 'admin@timetm.com',
    			'pwd' => '1234'
    		),
    		1 => array(
    			'name' => 'frian',
    			'email' => 'a@frian.org',
    			'pwd' => '5678'
    		),
    	);

    	foreach ( $users as $index => $userData ) {

	    	// create user
	        $user = new User();
	        $user->setUsername($userData['name']);
	        $user->setEmail($userData['email']);
	        $user->setPlainPassword($userData['pwd']);
	        $user->setEnabled(true);

	        // add reference for further fixtures
	        $this->addReference('user'.$index, $user);
	        
	        // create user default agenda
	        $agenda = new Agenda();
	    	$agenda->setUser($user);
	    	$agenda->setName('default');
	    	$agenda->setDescription('default');

	    	$manager->persist($agenda);
	    	$manager->flush();
    	}

    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
    	return 1; // the order in which fixtures will be loaded
    }
}