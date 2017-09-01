<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RecetteCategoryRepository extends EntityRepository
{
    /**
     * @return RecetteCategory[]
     */
    public function findAll()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.recettes', 'r')
            ->addSelect('r')
            ->where('r.active = true')
            ->orderBy('c.order')
            ->addOrderBy('r.position')
            ->getQuery()
            ->getResult();
    }
}