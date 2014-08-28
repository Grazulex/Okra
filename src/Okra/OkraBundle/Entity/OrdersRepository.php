<?php

namespace Okra\OkraBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OrdersRepository extends EntityRepository
{
    public function getNbClose($actifSession)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.idStatus = :Status')
                ->andWhere('a.idSession = :Session')
            ->setParameter('Status', 2)
            ->setParameter('Session', $actifSession)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    
    public function getTotalClose($actifSession)
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.total)')
            ->where('a.idStatus = :Status')
                ->andWhere('a.idSession = :Session')
            ->setParameter('Status', 2)
            ->setParameter('Session', $actifSession)    
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }    
}