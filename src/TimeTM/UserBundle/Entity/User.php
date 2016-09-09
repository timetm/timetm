<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Implementation of FOS\UserBundle\Model\User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="TimeTM\UserBundle\Entity\UserRepository")
 *
 * @author André Friedli <a@frian.org>
 */
class User extends BaseUser {

    /**
    * id
    *
    * @var integer
    *
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;


    /**
    * User theme
    *
    * @var TimeTM\CoreBundle\Entity\Theme
    *
    * @ORM\ManyToOne(targetEntity="TimeTM\CoreBundle\Entity\Theme", cascade={"persist"})
    */
    private $theme;


    /**
    * User's agendas
    *
    * @ORM\OneToMany(targetEntity="TimeTM\CoreBundle\Entity\Agenda", mappedBy="user", cascade={"persist"})
    */
    private $agendas;


    /**
    * User language
    *
    * @var TimeTM\CoreBundle\Entity\Language
    *
    * @ORM\ManyToOne(targetEntity="TimeTM\CoreBundle\Entity\Language", cascade={"persist"})
    */
    private $language;


    /**
    * stringify
    *
    * @return string
    *
    */
    public function __construct() {
        parent::__construct();
        $this->agendas = new ArrayCollection();
    }

    /**
    * Get id
    *
    * @return integer
    */
    public function getId() {
        return $this->id;
    }


    /**
     * Set theme
     *
     * @param \TimeTM\CoreBundle\Entity\Theme $theme
     *
     * @return User
     */
    public function setTheme(\TimeTM\CoreBundle\Entity\Theme $theme = null) {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \TimeTM\CoreBundle\Entity\Theme
     */
    public function getTheme() {
        return $this->theme;
    }


    public function getAgendas() {
        return $this->agendas;
    }

    public function setAgendas(ArrayCollection $agendas) {
        $this->agendas = $agendas;
        return $this;
    }

    /**
     * Add agenda
     *
     * @param \TimeTM\CoreBundle\Entity\Agenda $agenda
     *
     * @return User
     */
    public function addAgenda(\TimeTM\CoreBundle\Entity\Agenda $agenda)
    {
        $this->agendas[] = $agenda;

        return $this;
    }

    /**
     * Remove agenda
     *
     * @param \TimeTM\CoreBundle\Entity\Agenda $agenda
     */
    public function removeAgenda(\TimeTM\CoreBundle\Entity\Agenda $agenda)
    {
        $this->agendas->removeElement($agenda);
    }


    /**
     * Set language
     *
     * @param \TimeTM\CoreBundle\Entity\Language $language
     *
     * @return User
     */
    public function setLanguage(\TimeTM\CoreBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }


    /**
     * Get language
     *
     * @return \TimeTM\CoreBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
