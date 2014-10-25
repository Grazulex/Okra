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
    
    public function getAllOpen()
    {
        return $this->createQueryBuilder('o')
            ->select(array('o.id, o.idTable, o.idOrderManual, u.username, o.dateCreate, o.total, timediff(CURRENT_TIMESTAMP(), o.dateCreate) as diffhour, datediff(CURRENT_TIMESTAMP(), o.dateCreate) as diffday'))    
                ->innerJoin('o.idUser','u')
            ->where('o.idStatus = :Status')
            ->setParameter('Status', 1)
            ->orderBy('o.idTable', 'ASC')  
                ->addOrderBy('o.idOrderManual', 'ASC')                
            ->getQuery()
            ->getResult();        
    }
    
    public function createNewOrder($session_id)
    {
        $newOrder = new Orders();
        $newOrder->setIdStatus(1);
        $newOrder->setDateCreate(new \DateTime('now'));
        $newOrder->setTotal(0);
        $newOrder->setIdSession($session_id);  
        return $newOrder;
    }
}