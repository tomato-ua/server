<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Stenography
 *
 * @ORM\Table(name="stenography")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StenographyRepository")
 */
class Stenography
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
     * @var \DateTime
     *
     * @ORM\Column(name="eventDate", type="datetime")
     */
    private $eventDate;

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

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Link", mappedBy="stenography")
     */
    private $links;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $published;

    public function __construct($published = false)
    {
        $this->setPublished($published);
        $this->videos = new ArrayCollection();
        $this->links = new ArrayCollection();
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

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return Collection
     */
    public function getLinks()
    {
        return $this->links;
    }

    public function addLink(Link $link)
    {
        if (!$this->getLinks()->contains($link)) {
            $this->getLinks()->add($link);
        }

        return $this;
    }

    public function removeLink(Link $link)
    {
        if ($this->getLinks()->contains($link)) {
            $this->getLinks()->removeElement($link);
        }

        return $this;
    }
}
