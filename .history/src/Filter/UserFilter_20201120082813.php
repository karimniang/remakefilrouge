<?php

declare(strict_types=1);

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UserFilter implements AbstractFilter
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