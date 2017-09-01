<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Agenda Item
 *
 * @ORM\Table(name="rg_recette_category")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RecetteCategoryRepository")
 */
class RecetteCategory
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
     * @ORM\Column(type="string")
     */
    private $identifier;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Recette", mappedBy="category", orphanRemoval=true, cascade={"all"})
     */
    private $recettes;

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
        $this->recettes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
    public function getRecettes()
    {
        return $this->recettes;
    }

    /**
     * @param ArrayCollection $recettes
     * @return self
     */
    public function setRecettes($recettes)
    {
        $this->recettes = $recettes;
        return $this;
    }

    /**
     * @param Recette $recette
     * @return self
     */
    public function addRecette(Recette $recette)
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Recette $recette
     * @return self
     */
    public function removeRecette(Recette $recette)
    {
        if ($this->recettes->contains($recette)) {
            $this->recettes->removeElement($recette);
            $recette->setCategory(null);
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
}