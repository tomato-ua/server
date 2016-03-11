<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stenography
 *
 * @ORM\Table(name="stenography")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StenographyRepository")
 */
class Stenography
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eventDate", type="datetime")
     */
    private $eventDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;


    /**
     * @var string
     *
     * @ORM\Column(name="uniqueId", type="string", length=255, unique=true)
     */
    private $uniqueId;


    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="video", cascade={"remove","persist"})
     */
    private $tags;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Video", mappedBy="stenography")
     */
    private $videos;

    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Set url
     *
     * @param string $url
     *
     * @return Stenography
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $eventDate
     *
     * @return Stenography
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * @return \DateTime
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param \DateTime $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * @param string $uniqueId
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
    }

    /**
     * Add tag
     *
     * @param Tag $tag
     *
     * @return Stenography
     */
    public function addTag(Tag $tag)
    {
        if (!$this->getTags()->contains($tag)) {
            $this->getTags()->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag)
    {
        if ($this->getTags()->contains($tag)) {
            $this->getTags()->removeElement($tag);
        }

        return $this;
    }

    /**
     * Get tags
     *
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    public function addVideo(Video $video)
    {
        if (!$this->getVideos()->contains($video)) {
            $this->getVideos()->add($video);
        }

        return $this;
    }

    public function removeVideo(Video $video)
    {
        if ($this->getVideos()->contains($video)) {
            $this->getVideos()->removeElement($video);
        }

        return $this;
    }
}
