<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AtelierCategoryRepository extends EntityRepository
{
    /**
     * @return RecetteCategory[]
     */
    public function findAll()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.ateliers', 'a')
            ->addSelect('a')
            ->where('a.active = true')
            ->orderBy('c.order')
            ->addOrderBy('a.position')
            ->getQuery()
            ->getResult();
    }
}