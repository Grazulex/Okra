<?php

namespace Okra\OkraBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
    public function findAllByLocale($locale = 'en', $category)
    {
        //Make a Select query
        $qb = $this->createQueryBuilder('a');
        $qb->select('a')
            ->where('a.idCategory = :Category')
            ->setParameter('Category', $category)
            ->orderBy('a.id');         

        // Use Translation Walker
        $query = $qb->getQuery();
        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        $query->setHint(
            \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $locale
        );
        return $query->getResult();
    }    
}
