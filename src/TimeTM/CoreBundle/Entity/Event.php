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

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class representing an Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimeTM\CoreBundle\Entity\EventRepository")
 * 
 * @author André Friedli <a@frian.org>
 */
class Event
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
     * Agenda containing the event
     * 
     * @var TimeTM\AgendaBundle\Entity\Agenda
     *
     * @ORM\ManyToOne(targetEntity="TimeTM\CoreBundle\Entity\Agenda", cascade={"persist"})
     */
    private $agenda;

    /**
     * title
     * 
     * @var string
     * 
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * place
     * 
     * @var string
     * 
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="place", type="string", length=255)
     */
    private $place;

    /**
     * description
     * 
     * @var text
     * 
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * startdate
     * 
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startdate;

    /**
     * enddate
     * 
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime")
     */
    private $enddate;

    /**
     * duration
     *
     * @var string
     *
     * @ORM\Column(name="duration", type="string")
     */
    private $duration;


    /**
     * fullday
     * 
     * @var boolean
     *
     * @ORM\Column(name="fullday", type="boolean")
     */
    private $fullday;


    /**
     * participants at the event
     *
     *
     * @ORM\ManyToMany(targetEntity="TimeTM\CoreBundle\Entity\Contact", cascade={"persist"})
     */
    private $participants;

    /**
     * client concerned by the event
     * 
     *
     * @ORM\ManyToOne(targetEntity="TimeTM\CoreBundle\Entity\Contact", cascade={"persist"})
     */
    private $client;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set place
     *
     * @param string $place
     * @return Event
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string 
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
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

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     * @return Event
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime 
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return Event
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime 
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set fullday
     *
     * @param string $fullday
     * @return Event
     */
    public function setFullday($fullday)
    {
        $this->fullday = $fullday;

        return $this;
    }

    /**
     * Get fullday
     *
     * @return string 
     */
    public function getFullday()
    {
        return $this->fullday;
    }

    /**
     * Set agenda
     *
     * @param \TimeTM\CoreBundle\Entity\Agenda $agenda
     * @return Event
     */
    public function setAgenda(\TimeTM\CoreBundle\Entity\Agenda $agenda = null)
    {
        $this->agenda = $agenda;

        return $this;
    }

    /**
     * Get agenda
     *
     * @return \TimeTM\CoreBundle\Entity\Agenda 
     */
    public function getAgenda()
    {
        return $this->agenda;
    }

    /**
     * Add participants
     *
     * @param \TimeTM\CoreBundle\Entity\Contact $participants
     * @return Event
     */
    public function addParticipant(\TimeTM\CoreBundle\Entity\Contact $participants = null)
    {
        $this->participants[] = $participants;

        return $this;
    }

    /**
     * Remove participants
     *
     * @param \TimeTM\CoreBundle\Entity\Contact $participants
     */
    public function removeParticipant(\TimeTM\CoreBundle\Entity\Contact $participants)
    {
        $this->participants->removeElement($participants);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipants()
    {
        return $this->participants;
    }


    /**
     * Set client
     *
     * @param \TimeTM\CoreBundle\Entity\Contact $client
     * @return Event
     */
    public function setClient(\TimeTM\CoreBundle\Entity\Contact $client = null)
    {
    	$this->client = $client;
    
    	return $this;
    }
    
    /**
     * Get client
     *
     * @return \TimeTM\CoreBundle\Entity\Contact
     */
    public function getClient()
    {
    	return $this->client;
    }

    /**
     * Set duration
     *
     * @param string
     * @return Event
     */
    public function setDuration($duration)
    {
    	$this->duration = $duration;
    
    	return $this;
    }
    
    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
    	return $this->duration;
    }
    
}
