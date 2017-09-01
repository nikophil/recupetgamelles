<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AtelierRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->orderBy('c.order')
            ->addOrderBy('a.position')
            ->getQuery()
            ->getResult();
    }
}