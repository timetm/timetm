<?php
/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace TimeTM\AgendaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * class representing an Agenda
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimeTM\AgendaBundle\Entity\AgendaRepository")
 */
class Agenda
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
     * User owning the agenda
     * 
     * @var TimeTM\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="TimeTM\UserBundle\Entity\User", cascade={"persist"})
     */
    private $user;

    /**
     * Name of the agenda
     * 
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Description of the agenda
     * 
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

	/**
	 * stringify
	 * 
	 * @return string
	 */
    public function __toString() {
      return $this->name;
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
     * Set user
     *
     * @param integer $user
     * @return user
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Agenda
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Agenda
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}