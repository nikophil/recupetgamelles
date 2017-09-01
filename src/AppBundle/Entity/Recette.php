<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Agenda Item
 *
 * @ORM\Table(name="rg_recette")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RecetteRepository")
 */
class Recette
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
     * @Assert\NotBlank(message="Merci de renseigner un nom")
     */
    private $name;

    /**
     * @var RecetteCategory
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecetteCategory", inversedBy="recettes", cascade={"merge", "persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     * @Gedmo\SortableGroup()
     */
    private $category;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @Gedmo\SortablePosition()
     */
    private $position;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Merci d'uploader un pdf", groups={"create"})
     * @Assert\File(mimeTypes={"application/pdf"}, mimeTypesMessage="Le fichier doit être au format pdf")
     */
    private $pdf;

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
     * @return string
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param string $pdf
     * @return self
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return RecetteCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param RecetteCategory $category
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
}