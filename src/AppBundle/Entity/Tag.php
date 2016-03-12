<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
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
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var int
     *
     * @ORM\Column(name="timeOffset", type="integer")
     */
    private $timeOffset;

    /**
     * @var Video
     *
     * @ORM\ManyToOne(targetEntity="Video", inversedBy="tags")
     */
    private $video;

    private $startTime;

    /**
     * Tag constructor.
     * @param int $timeOffset
     */
    public function __construct(\DateTime $timeOffset)
    {
        $this->startTime = $timeOffset;
    }


    /**
     * Get id
     *
     * @return int
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
     * @return Tag
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
     * Set time
     *
     * @param integer $time
     *
     * @return Tag
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Set time
     *
     * @param integer $time
     *
     * @return Tag
     */
    public function setStringTime($time)
    {


        list($hh, $mm, $ss) = explode(':', $time);

        $this->time = new \DateTime();
        $this->time->setTime($hh, $mm, $ss);


        if ($this->startTime) {
            $diffInSeconds = $this->time->getTimestamp() - $this->startTime->getTimestamp();
            $this->setTimeOffset($diffInSeconds);
        }


        return $this;
    }

    /**
     * Get time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set video
     *
     * @param \AppBundle\Entity\Video $video
     *
     * @return Tag
     */
    public function setVideo(\AppBundle\Entity\Video $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \AppBundle\Entity\Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @return int
     */
    public function getTimeOffset()
    {
        return $this->timeOffset;
    }

    /**
     * @param int $timeOffset
     */
    public function setTimeOffset($timeOffset)
    {
        if ($timeOffset > 0) {
            $this->timeOffset = $timeOffset;
        } else {
            $this->timeOffset = 0;
        }
    }


}
