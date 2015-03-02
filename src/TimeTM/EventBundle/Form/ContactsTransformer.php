<?php

namespace TimeTM\EventBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use TimeTM\ContactBundle\Entity\Contact;

class ContactsTransformer implements DataTransformerInterface
{

    public function transform($participantCollection)
    {
        $participants = array();
print "";
        foreach ($participantCollection as $contact)
        {
            $participants[] = $contact->getLastName();
        }

        return implode(',', $participants);
    }

    public function reverseTransform($participants)
    {
        $participantCollection = new ArrayCollection();

        foreach (explode(',', $participants) as $participant)
        {
            $contact = new Contact();
            $contact->setLastName($participant);
            $participantCollection->add($contact);
        }

        return $participantCollection;
    }
}