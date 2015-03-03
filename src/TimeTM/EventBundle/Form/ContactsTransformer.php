<?php

namespace TimeTM\EventBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use TimeTM\ContactBundle\Entity\Contact;

class ContactsTransformer implements DataTransformerInterface
{
	/**
	 * @var EntityManager
	 */
	private $em;
	
	public function __construct($em) {
		$this->em = $em;
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

    public function transform($participantCollection)
    {
        $participants = array();

        foreach ($participantCollection as $contact)
        {
            $participants[] = $contact->getLastName();
        }

        return implode(',', $participants);
    }

    public function reverseTransform($participants)
    {
        $participantCollection = new ArrayCollection();

        foreach ($this->stringToArray($participants) as $participant)
        {
        	$exists = $this->em->getRepository('TimeTM\ContactBundle\Entity\Contact')
        	->findOneBy(array('lastname' => $participant));
        	
        	
        	if (null === $exists) {
	            $contact = new Contact();
	            $contact->setLastName($participant);
	            $participantCollection->add($contact);
        	}
        }

        return $participantCollection;
    }
}