<?php

use App\Entity\FortuneCookie;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DiscontinuedFilter extends SQLFilter {

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias) {
        // 
        if($targetEntity->getReflectionClass()->name !== FortuneCookie::class)
        {
            return '';
        }

        return sprintf('%s.discontinued = false', $targetTableAlias);
    }

}