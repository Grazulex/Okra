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
}