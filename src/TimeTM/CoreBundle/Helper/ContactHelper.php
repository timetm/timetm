<?php
/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */


namespace TimeTM\CoreBundle\Helper;

/**
 * class representing a weekly calendar
 */
class ContactHelper {

    /**
	 * Entity Manager
	 *
	 * @var EntityManager $em
	 */
	protected $em;

	/**
	 * Constructor
	 *
	 * @param EntityManager $em
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * create the canonical user name
	 *
	 * @param TimeTM\CoreBundle\Entity\Contact
	 *
	 * @return     array     ($canonicalName, $msg)
	 */
	public function setCanonicalName(\TimeTM\CoreBundle\Entity\Contact $contact) {

		$msg = '';

        // get contacts
        $contacts = $this->em->getRepository('TimeTMCoreBundle:Contact');


		// create canonical_name
		$canonicalName = $contact->getLastname();
        if ($contact->getEmail()) {
            $canonicalName .= '_' . $contact->getEmail();
        }
        if ($contact->getFirstname()) {
            $canonicalName .= '_' . $contact->getFirstname();
        }

        $exist = $contacts->findOneBy(array('canonical_name' => $canonicalName));

        if ($exist) {
            $msg = 'contact.error.name_exist';
            if ($contact->getFirstname()) {
    			// $canonicalName .= '_' . $contact->getFirstname();
                $exist = $contacts->findOneBy(array('canonical_name' => $canonicalName));
                if ($exist) {
                    $msg = 'contact.error.fullname_exist';
                    if ($contact->getEmail()) {
            			// $canonicalName .= '_' . $contact->getEmail();
                        $exist = $contacts->findOneBy(array('canonical_name' => $canonicalName));
                        if ($exist) {
                            $msg = 'contact.error.account_exist';
                        }

            		}
                }
            }
            elseif ($contact->getEmail()) {
                // $canonicalName .= '_' . $contact->getEmail();
                $exist = $contacts->findOneBy(array('canonical_name' => $canonicalName));
                if ($exist) {
                    $msg = 'nom déjà existant, veuillez ajouter un prénom';
                }
            }
        }

        $canonicalName = mb_strtolower($canonicalName);

        $contact->setCanonicalName($canonicalName);

		return $msg;
	}


	/**
	 * parse the name field
	 *
	 * @param      TimeTM\CoreBundle\Entity\Contact
	 *
	 * @return     entity     TimeTM\CoreBundle\Entity\Contact
	 */
	public function parseNameField(\TimeTM\CoreBundle\Entity\Contact $contact) {

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
