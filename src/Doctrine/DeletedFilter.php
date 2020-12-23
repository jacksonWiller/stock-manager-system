<?php


namespace App\Doctrine;


use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Uloc\ApiBundle\Entity\FormEntity;

class DeletedFilter extends SQLFilter
{

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {

        if (!$targetEntity->getReflectionClass()->getParentClass()) {
            return '';
        }

        if ($targetEntity->getReflectionClass()->getParentClass()->name !== FormEntity::class) {
            return '';
        }
        return sprintf('%s.deleted = %s', $targetTableAlias, $this->getParameter('deleted'));
    }

}