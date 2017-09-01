<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Agenda Item
 *
 * @ORM\Table(name="rg_atelier_category")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AtelierCategoryRepository")
 */
class AtelierCategory
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Merci de remplir le nom de la catégorie")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $subTitle;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $identifier;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Atelier", mappedBy="category", orphanRemoval=true, cascade={"all"})
     */
    private $ateliers;

    /**
     * @var integer
     * @ORM\Column(name="ze_order", type="integer")
     * @Gedmo\SortablePosition()
     */
    private $order;

    /**
     * @var string
     * @Gedmo\SortableGroup()
     * @ORM\Column(type="string")
     */
    private $position;

    public function __construct()
    {
        $this->ateliers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAteliers()
    {
        return $this->ateliers;
    }

    /**
     * @param ArrayCollection $ateliers
     * @return self
     */
    public function setAteliers($ateliers)
    {
        $this->ateliers = $ateliers;
        return $this;
    }

    /**
     * @param Atelier $atelier
     * @return self
     */
    public function addAtelier(Atelier $atelier)
    {
        if (!$this->ateliers->contains($atelier)) {
            $this->ateliers->add($atelier);
            $atelier->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Atelier $atelier
     * @return self
     */
    public function removeAtelier(Atelier $atelier)
    {
        if ($this->ateliers->contains($atelier)) {
            $this->ateliers->removeElement($atelier);
            $atelier->setCategory(null);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return self
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     * @return $this
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;
        return $this;
    }

    public function getFullName()
    {
        if (!$this->subTitle) {
            return $this->name;
        }

        return $this->name . ' ' . $this->subTitle;
    }
}