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
use TimeTM\CoreBundle\Entity\Contact;

/**
 * Contact fixture
 *
 * @author André Friedli <a@frian.org>
 */
class LoadContactData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
    public function load(ObjectManager $manager) {

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
      			'email' => 'info@smartdistribution.com',
      			'phone' => '',
            'company' => 1
    		),
    		2 => array(
      			'lastname' => 'John',
      			'firstname' => 'Doe',
      			'email' => 'john@doe.com',
      			'phone' => ''
    		),
    	);


      // get helper for canonical name
      $helper = $this->container->get('timetm.contact.helper');

	/**
	 * Add contacts
	 */
	foreach ( $contacts as $index => $contactData ) {

        for ($i = 0; $i < 4; $i++) {

	    	// create user
	        $contact = new Contact();
	        $contact->setLastname($contactData['lastname'].$i);
	        $contact->setFirstname($contactData['firstname'].$i);
	        $contact->setEmail($i.$contactData['email']);

            $msg = $helper->setCanonicalName($contact);

	        $contact->setPhone($contactData['phone'].$i);

              if (isset($contactData['company'])) {
                  $contact->setCompany($contactData['company'].$i);
              }

              if (isset($contactData['client'])) {
                  $contact->setCompany($contactData['client'].$i);
              }

  	        // add reference for further fixtures
  	        $this->addReference('contact'.$index.$i, $contact);

    	    	$manager->persist($contact);
    	    	$manager->flush();
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
