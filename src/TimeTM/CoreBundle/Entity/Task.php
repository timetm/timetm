<?php

namespace TimeTM\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="TimeTM\CoreBundle\Entity\TaskRepository")
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duedate", type="datetime")
     */
    private $duedate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="donedate", type="datetime", nullable=true)
     */
    private $donedate;

    /**
     * User assigned to the task
     *
     * @var TimeTM\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(
     *      targetEntity="TimeTM\UserBundle\Entity\User",
     *      cascade={"persist"}
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $userassigned;

    /**
    * User marking the task as done
    *
    * @var TimeTM\UserBundle\Entity\User
    *
    * @ORM\ManyToOne(
    *      targetEntity="TimeTM\UserBundle\Entity\User",
    *      cascade={"persist"}
    * )
    * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $doneby;

    /**
     * @var int
     *
     * @ORM\Column(name="repetition", type="integer", nullable=true)
     */
    private $repetition;


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
     *
     * @return Task
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
     * Set duedate
     *
     * @param \DateTime $duedate
     *
     * @return Task
     */
    public function setDuedate($duedate)
    {
        $this->duedate = $duedate;

        return $this;
    }

    /**
     * Get duedate
     *
     * @return \DateTime
     */
    public function getDuedate()
    {
        return $this->duedate;
    }

    /**
     * Set donedate
     *
     * @param \DateTime $donedate
     *
     * @return Task
     */
    public function setDonedate($donedate)
    {
        $this->donedate = $donedate;

        return $this;
    }

    /**
     * Get donedate
     *
     * @return \DateTime
     */
    public function getDonedate()
    {
        return $this->donedate;
    }

    /**
     * Set userassigned
     *
     * @param integer $userassigned
     *
     * @return Task
     */
    public function setUserassigned($userassigned)
    {
        $this->userassigned = $userassigned;

        return $this;
    }

    /**
     * Get userassigned
     *
     * @return integer
     */
    public function getUserassigned()
    {
        return $this->userassigned;
    }

    /**
     * Set doneby
     *
     * @param integer $doneby
     *
     * @return Task
     */
    public function setDoneby($doneby)
    {
        $this->doneby = $doneby;

        return $this;
    }

    /**
     * Get doneby
     *
     * @return integer
     */
    public function getDoneby()
    {
        return $this->doneby;
    }

    /**
     * Set repetition
     *
     * @param integer $repetition
     *
     * @return Task
     */
    public function setRepetition($repetition)
    {
        $this->repetition = $repetition;

        return $this;
    }

    /**
     * Get repetition
     *
     * @return integer
     */
    public function getRepetition()
    {
        return $this->repetition;
    }
}
