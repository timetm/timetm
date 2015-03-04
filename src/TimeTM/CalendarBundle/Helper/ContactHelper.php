<?php
/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */


namespace TimeTM\CalendarBundle\Helper;

/**
 * class representing a weekly calendar
 */
class ContactHelper {


	/**
	 * create the canonical user name
	 * 
	 * @param TimeTM\ContactBundle\Entity\Contact
	 * 
	 * @return     array     ($canonicalName, $msg)
	 */
	public function getCanonicalName($contact) {

		$msg = 'nom déjà existant, veuillez ajouter une addresse email ou un prénom';

		// create canonical_name
		$canonicalName = $contact->getLastname();
		if ($contact->getFirstname()) {
			$canonicalName .= '_' . $contact->getFirstname();
			$msg = 'nom déjà existant, veuillez ajouter une addresse email';
			if ($contact->getEmail()) {
				$canonicalName .= '_' . $contact->getEmail();
				$msg = 'le compte existe déjà';
			}
		}

		if ($contact->getEmail()) {
			$canonicalName .= '_' . $contact->getEmail();
			$msg = 'nom déjà existant, veuillez ajouter un prénom';
		}

		return array($canonicalName, $msg);
	}


	/**
	 * parse the name field
	 *
	 * @param      TimeTM\ContactBundle\Entity\Contact
	 *
	 * @return     entity     TimeTM\ContactBundle\Entity\Contact
	 */
	public function parseNameField($contact) {

		if ( ! $contact->getFirstname() ) {
			// if not get lastname
			$lastname = $contact->getLastname();
			// check if lastname has 2 words
			$matches = array();
			if ( \preg_match('/(\p{L}+)\s+(\p{L}+)/u', $lastname, $matches) ) {
				// if yes set first word as firstname and second word as lastname
				$contact->setLastname($matches[2]);
				$contact->setFirstname($matches[1]);
			}
		}

		return  $contact;
	}
}