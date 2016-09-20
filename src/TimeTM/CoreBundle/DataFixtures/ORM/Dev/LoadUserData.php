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
use TimeTM\UserBundle\Entity\User;
use TimeTM\CoreBundle\Entity\Agenda;

/**
 * User fixture
 *
 * @author André Friedli <a@frian.org>
 */
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
    			'email' => 'andre@at-info.ch',
    			'pwd' => '1234'
    		),
    		1 => array(
    			'name' => 'frian',
    			'email' => 'a@frian.org',
    			'pwd' => '5678'
    		),
    	);

    	/**
    	 * Add users
    	 */
    	foreach ( $users as $index => $userData ) {

	    	// create user
	        $user = new User();
	        $user->setUsername($userData['name']);
	        $user->setEmail($userData['email']);
	        $user->setPlainPassword($userData['pwd']);
	        $user->setEnabled(true);
            $user->setTheme($this->getReference('theme1'));
            $user->setLanguage($this->getReference('language0'));

	        // add reference for further fixtures
	        $this->addReference('user'.$index, $user);

	        // create user default agenda
	        $agenda = new Agenda();
	    	$agenda->setUser($user);
	    	$agenda->setName('default');
            $agenda->setDefault(1);
	    	$agenda->setDescription('default');

            $this->addReference('userAgenda'.$index, $agenda);

            // create user private agenda
	        $privateAgenda = new Agenda();
	    	$privateAgenda->setUser($user);
	    	$privateAgenda->setName('private');
            $privateAgenda->setDefault(0);
	    	$privateAgenda->setDescription('private');

            $this->addReference('userPrivateAgenda'.$index, $privateAgenda);

	    	$manager->persist($agenda);
            $manager->persist($privateAgenda);
	    	$manager->flush();
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
