<?php

namespace TimeTM\CoreBundle\Form;

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
	            if ($firstname) {
	           		$contact->setFirstname($firstname);
	            }
	            $this->em->persist($contact);
        	}
        	
        	$participantCollection->add($contact);
        }

        return $participantCollection;
    }
}
