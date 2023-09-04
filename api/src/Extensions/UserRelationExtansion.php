<?php

namespace App\Extensions;

use Doctrine\ORM\QueryBuilder;

abstract class UserRelationExtansion extends AbstractCurrentUserExtension
{
    /**
     * @param QueryBuilder $queryBuilder
     * @return void
     */
    public function buildQuery(QueryBuilder $queryBuilder)
    {
        $rootAlias = $queryBuilder->getRootAlias()[self::FIRST_ELEMENT_ARRAY];
        $queryBuilder
            ->andWhere($rootAlias . '.user =:user')
            ->setParameter('user', $this->tokenStorage->getToken()->getUser());
    }
}
