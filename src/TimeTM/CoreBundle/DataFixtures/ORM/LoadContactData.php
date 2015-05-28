<?php
// src/TimeTM/UserBundle/DataFixtures/ORM/LoadUserData.php

namespace TimeTM\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TimeTM\ContactBundle\Entity\Contact;

class LoadContactData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    	
    	$contacts = array(
    		0 => array(
    			'lastname' => 'Bosson',
    			'firstname' => 'Thibaut',
    			'email' => 'thibaut.bosson@gmail.com',
    			'phone' => '079 582 43 29'
    		),
    		1 => array(
    			'lastname' => 'Smartdistribution',
    			'firstname' => '',
    			'email' => '',
    			'phone' => ''
    		),
    		2 => array(
    			'lastname' => 'John',
    			'firstname' => 'Doe',
    			'email' => '',
    			'phone' => ''
    		),
    	);

    	foreach ( $contacts as $index => $contactData ) {

	    	// create user
	        $contact = new Contact();
	        $contact->setLastname($contactData['lastname']);
	        $contact->setFirstname($contactData['firstname']);
	        $contact->setEmail($contactData['email']);
	        $contact->setPhone($contactData['phone']);

	        // add reference for further fixtures
	        $this->addReference('contact'.$index, $contact);
	        
	    	$manager->persist($contact);
	    	$manager->flush();
    	}

    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
    	return 2; // the order in which fixtures will be loaded
    }
}