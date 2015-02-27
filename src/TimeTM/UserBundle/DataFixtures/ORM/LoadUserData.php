<?php
// src/Acme/HelloBundle/DataFixtures/ORM/LoadUserData.php

namespace TimeTM\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TimeTM\UserBundle\Entity\User;
use TimeTM\AgendaBundle\Entity\Agenda;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('a@frian.org');
        $user->setPlainPassword('1234');
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        $agenda = new Agenda();
    	$agenda->setUser($user);
    	$agenda->setName('default');
    	$agenda->setDescription('default');

    	$manager->persist($agenda);
    	$manager->flush();

    }
}