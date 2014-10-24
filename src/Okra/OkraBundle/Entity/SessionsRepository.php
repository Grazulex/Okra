<?php

namespace Okra\OkraBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SessionsRepository extends EntityRepository
{
    public function getActiveSession()
    {
        $acriveSession = $this->findOneBy(array("dateStop" => null));
        if ($acriveSession) {
            return $acriveSession;
        } else {
            $acriveSession = new Sessions();
            $acriveSession->setDateStart(new \DateTime('now'));
            $this->_em->persist($acriveSession);
            $this->_em->flush();
            return $acriveSession;        
        }
    } 

    public function getDates($sessions)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('DISTINCT year(o.dateCreate) as dateYear, month(o.dateCreate) as dateMonth, day(o.dateCreate) as dateDay ')
            ->from('OkraBundle:OrdersItem', 'oi')
                ->innerJoin('oi.idItem','i')
                    ->innerJoin('i.idCategory','c')
                ->innerJoin('oi.idOrder','o')
            ->where('o.idStatus IN (:Status)')
                ->andWhere('o.idSession = :Session')
            ->setParameter('Status', '2,3')
                ->setParameter('Session', $sessions)
            ->groupBy('dateYear')
                ->addGroupBy('dateMonth')
                ->addGroupBy('dateDay')
            ->getQuery()
            ->getResult()
        ;        
    }
    
    public function getStats($sessions,$dates)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('i.id, c.name, i.name, year(o.dateCreate) as dateYear, month(o.dateCreate) as dateMonth, day(o.dateCreate) as dateDay, SUM(oi.quantity) as nbr, oi.price, SUM(oi.quantity * oi.price) as total')
            ->from('OkraBundle:OrdersItem', 'oi')
                ->innerJoin('oi.idItem','i')
                    ->innerJoin('i.idCategory','c')
                ->innerJoin('oi.idOrder','o')
            ->where('o.idStatus IN (:Status)')
                ->andWhere('o.idSession = :Session')
            ->setParameter('Status', '2,3')
                ->setParameter('Session', $sessions)
            ->orderBy('dateYear', 'DESC')  
                ->addOrderBy('dateMonth', 'DESC')
                ->addOrderBy('c.name', 'ASC')
                ->addOrderBy('i.name', 'ASC')
            ->groupBy('dateYear')
                ->addGroupBy('dateMonth')
                ->addGroupBy('i.id')    
            ->getQuery()
            ->getResult()
        ;
    }    
    
}