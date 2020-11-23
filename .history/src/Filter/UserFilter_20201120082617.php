<?php

declare(strict_types=1);

namespace App\Filter;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;

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