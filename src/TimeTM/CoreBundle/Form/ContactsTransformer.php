<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use TimeTM\CoreBundle\Entity\Contact;

/**
 * Contacts transformer
 *
 * @author André Friedli <a@frian.org>
 */
class ContactsTransformer implements DataTransformerInterface
{
	/**
	 * EntityManager
	 *
	 * @var EntityManager
	 */
	private $em;

	/**
	 * Constructor
	 *
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em, $eventHelper) {
		$this->em = $em;
        $this->eventHelper = $eventHelper;
	}


	/**
	 * Convert string of tags to array.
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	private function stringToArray($string)
	{
		$participants = explode(',', $string);

		// strip whitespaces from beginning and end of a tag text
		foreach ($participants as &$text) {
			$text = trim($text);
		}

		// removes duplicates
		return array_unique($participants);
	}

	/**
	 * Transform collection of contacts to string
	 *
	 * @param ArrayCollection $participantCollection
	 *
	 * @see \Symfony\Component\Form\DataTransformerInterface::transform()
	 */
    public function transform($participantCollection)
    {
        $participants = array();

        foreach ($participantCollection as $contact)
        {
            $participants[] = $contact->getLastName().' '.$contact->getFirstName();
        }

        return implode(',', $participants);
    }

    /**
     * Transform string of contacts to collection of contacts
     *
     * @param string $participants
     *
     * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
     */
    public function reverseTransform($participants)
    {
        $participantCollection = new ArrayCollection();

        if ($participants === null) {
        	return $participantCollection;
        }

        foreach ($this->stringToArray($participants) as $participant)
        {
        	$lastname;
        	$firstname;
        	$matches = array();

        	if ( \preg_match('/(\w+)\s+(\w+)/', $participant, $matches) ) {
        		// if yes set first word as firstname and second word as lastname
        		$lastname = $matches[2];
        		$firstname = $matches[1];
        	}
        	else {
        		$lastname = $participant;
        	}

        	$contact = $this->em->getRepository('TimeTM\CoreBundle\Entity\Contact')
        		->findOneBy(array('lastname' => $lastname));

        	if (null === $contact) {
	            $contact = new Contact();
	            $matches = array();

	            $contact->setLastname($lastname);
	            if (isset($firstname)) {
	           		$contact->setFirstname($firstname);
	            }

                list( $canonicalName, $msg) = $this->eventHelper->getCanonicalName($contact);

                $contact->setCanonicalName($canonicalName);

	            $this->em->persist($contact);
        	}

        	$participantCollection->add($contact);
        }

        return $participantCollection;
    }
}
