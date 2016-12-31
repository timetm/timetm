<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\DataFixtures\ORM\Release;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TimeTM\CoreBundle\Entity\Theme;

/**
 * User fixture
 *
 * @author André Friedli <a@frian.org>
 */
class LoadThemeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

    	$themes = array(
    		0 => array(
    			'name' => 'theme-white',
    		),
    		1 => array(
    			'name' => 'theme-black',
    		),
            2 => array(
    			'name' => 'theme-green',
    		)
    	);

    	/**
    	 * Add users
    	 */
    	foreach ( $themes as $index => $themeData ) {

	    	// create user
	        $theme = new Theme();
	        $theme->setName($themeData['name']);

	        // add reference for further fixtures
	        $this->addReference('theme'.$index, $theme);

	    	$manager->persist($theme);
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
