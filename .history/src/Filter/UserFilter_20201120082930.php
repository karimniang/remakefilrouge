<?php

declare(strict_types=1);

namespace App\Filter;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\AbstractFilter;

class UserFilter extends AbstractFilter
{
    const PARAMETER_DISCRIMINATOR = 'type';

   // ...

    /**
     * @inheritdoc
     */
    public function apply(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return;
        }

        /** @var ClassMetadata $metadata */
        $metadata = $this->managerRegistry->getManager()->getClassMetadata($resourceClass);
        $discriminatorMap = $metadata->discriminatorMap;

        foreach ($this->extractProperties($request, $resourceClass) as $property => $value) {
            if ($property != self::PARAMETER_DISCRIMINATOR 
                || empty($value) 
                || !is_string($value)
                || !isset($discriminatorMap[$value])
            ) {
                continue;
            }

            $queryBuilder->andWhere(
                $queryBuilder->expr()->isInstanceOf('o', $discriminatorMap[$value])
            );
        }
    }

   // ...
}