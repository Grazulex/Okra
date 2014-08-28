<?php

namespace Okra\OkraBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findAllByLocale($locale = 'en')
    {
        //Make a Select query
        $qb = $this->createQueryBuilder('a');
        $qb->select('a');
        //->orderBy(...) customize it        

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