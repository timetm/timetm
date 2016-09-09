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
use TimeTM\CoreBundle\Entity\Language;

/**
 * User fixture
 *
 * @author André Friedli <a@frian.org>
 */
class LoadLanguageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

    	$languages = array(
    		0 => array(
    			'name' => 'en',
    		),
    		1 => array(
    			'name' => 'fr',
    		),
    	);

    	/**
    	 * Add users
    	 */
    	foreach ( $languages as $index => $languageData ) {

	    	// create user
	        $language = new Language();
	        $language->setName($languageData['name']);

	        // add reference for further fixtures
	        $this->addReference('language'.$index, $language);

	    	$manager->persist($language);
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
