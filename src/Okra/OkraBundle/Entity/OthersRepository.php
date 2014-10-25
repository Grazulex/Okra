<?php

namespace Okra\OkraBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OthersRepository extends EntityRepository
{
    public function getTotalBookTodayClose($actifSession)
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.price)')
            ->where('a.idType = :Type')
                //->andWhere('a.dateCreate = :Today')
                ->andWhere('a.idSession = :Session')
            ->setParameter('Type', 1)
            //->setParameter('Today', date("Y-m-d"))   
            ->setParameter('Session', $actifSession)    
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }    
    public function getTotalBuyingTodayClose($actifSession)
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.price)')
            ->where('a.idType = :Type')
                //->andWhere('a.dateCreate = :Today')
                ->andWhere('a.idSession = :Session')
            ->setParameter('Type', 2)
            //->setParameter('Today', date("Y-m-d"))   
            ->setParameter('Session', $actifSession)     
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    public function getTotalOthersTodayClose($actifSession)
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.price)')
            ->where('a.idType = :Type')
                //->andWhere('a.dateCreate = :Today')
                ->andWhere('a.idSession = :Session')
            ->setParameter('Type', 3)
            //->setParameter('Today', date("Y-m-d"))   
            ->setParameter('Session', $actifSession)      
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }    
    public function getTotalStartsTodayClose($actifSession)
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.price)')
            ->where('a.idType = :Type')
                //->andWhere('a.dateCreate = :Today')
                ->andWhere('a.idSession = :Session')
            ->setParameter('Type', 4)
            //->setParameter('Today', date("Y-m-d"))   
            ->setParameter('Session', $actifSession)      
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }     
    
    public function createNewOther($actifSession = null)
    {
        $newBook = new Others();
        $newBook->setDateCreate(new \DateTime('now')); 
        if ($actifSession) {
            $newBook->setIdSession($actifSession);
        }
        return $newBook;
    }
}