<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agenda Item
 *
 * @ORM\Table(name="rg_bool")
 * @ORM\Entity()
 */
class Boolean
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
     *
     * @ORM\Column(type="string")
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $bool;

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
     * @return string
     */
    public function getBool()
    {
        return $this->bool;
    }

    /**
     * @param string $bool
     * @return self
     */
    public function setBool($bool)
    {
        $this->bool = $bool;
        return $this;
    }
}