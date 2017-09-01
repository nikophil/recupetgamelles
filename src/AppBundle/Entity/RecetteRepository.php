<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RecetteRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.category', 'c')
            ->addSelect('c')
            ->orderBy('c.order')
            ->addOrderBy('r.position')
            ->getQuery()
            ->getResult();
    }
}