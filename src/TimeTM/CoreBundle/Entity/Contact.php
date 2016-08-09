<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class representing an Contact
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimeTM\CoreBundle\Entity\ContactRepository")
 *
 * @author André Friedli <a@frian.org>
 */
class Contact
{
    /**
     * id
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Last name
     *
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * First name
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * Cannonical name
     *
     * @var string
     *
     * @ORM\Column(name="canonical_name", type="string", length=255, nullable=true, unique=true)
     */
    private $canonical_name;

    /**
     * Email
     *
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * Phone
     *
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * Company
     *
     * @var boolean
     *
     * @ORM\Column(name="company", type="boolean", nullable=true)
     */
    private $company;

    /**
     * Client
     *
     * @var boolean
     *
     * @ORM\Column(name="client", type="boolean", nullable=true)
     */
    private $client;

    public function __toString() {
        return $this->getLastname() . ' ' . $this->getFirstname();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Contact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Contact
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set as company
     *
     * @param boolean $company
     * @return Contact
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get is company
     *
     * @return boolean
     */
    public function isCompany()
    {
        return $this->company;
    }

    /**
     * Set as client
     *
     * @param boolean $client
     * @return Contact
     */
    public function setClient($client)
    {
    	$this->client = $client;

    	return $this;
    }

    /**
     * Get is client
     *
     * @return boolean
     */
    public function isClient()
    {
    	return $this->client;
    }

    /**
     * Set canonical_name
     *
     * @param string $canonicalName
     * @return Contact
     */
    public function setCanonicalName($canonicalName)
    {
        $this->canonical_name = $canonicalName;

        return $this;
    }

    /**
     * Get canonical_name
     *
     * @return string
     */
    public function getCanonicalName()
    {
        return $this->canonical_name;
    }
}
