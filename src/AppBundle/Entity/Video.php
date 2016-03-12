<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VideoRepository")
 */
class Video
{
    use TimestampableEntity;
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
     * @ORM\Column(name="youtubeId", type="string", length=11, unique=true)
     */
    private $youtubeId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=191)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="startTime", type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="video", cascade={"remove","persist"})
     */
    private $tags;

    /**
     * @var Stenography
     * @ORM\ManyToOne(targetEntity="Stenography", inversedBy="videos")
     */
    private $stenography;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tagged", type="boolean", options={"default": false})
     */
    private $tagged;

    public function __construct($tagged = false)
    {
        $this->tags = new ArrayCollection();
        $this->setTagged($tagged);
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
     * Set youtubeId
     *
     * @param string $youtubeId
     *
     * @return Video
     */
    public function setYoutubeId($youtubeId)
    {
        $this->youtubeId = $youtubeId;
        return $this;
    }

    /**
     * Get youtubeId
     *
     * @return string
     */
    public function getYoutubeId()
    {
        return $this->youtubeId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Video
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
     * Add tag
     *
     * @param Tag $tag
     *
     * @return Video
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set stenography
     *
     * @param Stenography $stenography
     *
     * @return Video
     */
    public function setStenography(Stenography $stenography = null)
    {
        $this->stenography = $stenography;

        return $this;
    }

    /**
     * Get stenography
     *
     * @return Stenography
     */
    public function getStenography()
    {
        return $this->stenography;
    }

    /**
     * Set tagged
     *
     * @param boolean $tagged
     *
     * @return Video
     */
    public function setTagged($tagged)
    {
        $this->tagged = $tagged;

        return $this;
    }

    /**
     * Get tagged
     *
     * @return boolean
     */
    public function getTagged()
    {
        return $this->tagged;
    }

    /**
     * @return string
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param string $startTime
     */
    public function setStartTime(\DateTime $startTime)
    {
        $this->startTime = $startTime;
    }


}
