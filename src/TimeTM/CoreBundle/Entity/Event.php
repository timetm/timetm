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

namespace TimeTM\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimeTM\CoreBundle\Entity\EventRepository")
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
     * starttime
     *
     * @var \DateTime
     *
     * @ORM\Column(name="starttime", type="datetime")
     */
    private $starttime;
    

    /**
     * enddate
     * 
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime")
     */
    private $enddate;

    /**
     * endtime
     *
     * @var \DateTime
     *
     * @ORM\Column(name="endtime", type="datetime")
     */
    private $endtime;
    



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
     * @param \TimeTM\AgendaBundle\Entity\Agenda $agenda
     * @return Event
     */
    public function setAgenda(\TimeTM\AgendaBundle\Entity\Agenda $agenda = null)
    {
        $this->agenda = $agenda;

        return $this;
    }

    /**
     * Get agenda
     *
     * @return \TimeTM\AgendaBundle\Entity\Agenda 
     */
    public function getAgenda()
    {
        return $this->agenda;
    }

    /**
     * Add participants
     *
     * @param \TimeTM\ContactBundle\Entity\Contact $participants
     * @return Event
     */
    public function addParticipant(\TimeTM\ContactBundle\Entity\Contact $participants = null)
    {
        $this->participants[] = $participants;

        return $this;
    }

    /**
     * Remove participants
     *
     * @param \TimeTM\ContactBundle\Entity\Contact $participants
     */
    public function removeParticipant(\TimeTM\ContactBundle\Entity\Contact $participants)
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
     * Set starttime
     *
     * @param \DateTime $starttime
     * @return Event
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;

        return $this;
    }

    /**
     * Get starttime
     *
     * @return \DateTime 
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set endtime
     *
     * @param \DateTime $endtime
     * @return Event
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * Get endtime
     *
     * @return \DateTime 
     */
    public function getEndtime()
    {
        return $this->endtime;
    }
}
