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

namespace TimeTM\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimeTM\EventBundle\Entity\EventRepository")
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
     * @ORM\ManyToOne(targetEntity="TimeTM\AgendaBundle\Entity\Agenda", cascade={"persist"})
     */
    private $agenda;

    /**
     * title
     * 
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * place
     * 
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=255)
     */
    private $place;

    /**
     * description
     * 
     * @var text
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
     * fullday
     * 
     * @var string
     *
     * @ORM\Column(name="fullday", type="string", nullable=true)
     */
    private $fullday;

    /**
     * participants
     * 
     * TODO link to futur contact entity
     * 
     * @var string
     *
     * @ORM\Column(name="participants", type="string", nullable=true )
     */
    private $participants;


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
     * Set agenda
     *
     * @param integer $agenda
     * @return Agenda
     */
    public function setAgenda($agenda)
    {
      $this->agenda = $agenda;
    
      return $this;
    }
    
    /**
     * Get agenda
     *
     * @return integer
     */
    public function getAgenda()
    {
      return $this->agenda;
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
     * Set date
     *
     * @param \DateTime $date
     * @return Event
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Event
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set duration
     *
     * @param string $duration
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

    /**
     * Set participants
     *
     * @param array $participants
     * @return Event
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;

        return $this;
    }

    /**
     * Get participants
     *
     * @return array 
     */
    public function getParticipants()
    {
        return $this->participants;
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
}
