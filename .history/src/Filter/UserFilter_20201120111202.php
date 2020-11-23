<?php



namespace App\Filter;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;

class UserFilter extends AbstractContextAwareFilter
{
    const FILTER_RESOURCE_TYPE_PROPERTY = "type";

    /**
     * @var ResourceMetadataFactoryInterface
     */
    private $resourceMetadataFactory;

    public function __construct(
        ManagerRegistry $managerRegistry,
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        ?RequestStack $requestStack = null,
        LoggerInterface $logger = null,
        array $properties = null,
        NameConverterInterface $nameConverter = null
    )
    {
        parent::__construct($managerRegistry, $requestStack, $logger, $properties, $nameConverter);

        $this->resourceMetadataFactory = $resourceMetadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            static::FILTER_RESOURCE_TYPE_PROPERTY => [
                'property' => static::FILTER_RESOURCE_TYPE_PROPERTY,
                'type' => 'string',
                'required' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null,
        array $context = []
    )
    {
        $value= ["formateur", "cm"];
        if ($property !== static::FILTER_RESOURCE_TYPE_PROPERTY) {
            return;
        }

        /** @var ObjectManager $manager */
        $manager              = $this->managerRegistry->getManagerForClass($resourceClass);
        $classMetadataFactory = $manager->getMetadataFactory();

        /** @var ClassMetadataInfo $classMetadata */
        $classMetadata = $classMetadataFactory->getMetadataFor($resourceClass);

        unset($classMetadata->discriminatorMap["apprenant"]);
        $aa = [];
// dd($classMetadata->discriminatorMap);
        $i = 0;
        foreach ($classMetadata->discriminatorMap as $discriminator => $entityClass) {
            $metadata = $this->resourceMetadataFactory->create($entityClass);
                if (in_array(strtolower($metadata->getShortName()),$value) ) {
                    $alias = $queryBuilder->getAllAliases()[0];
                    $queryBuilder->andWhere($alias . ' INSTANCE OF :instance');
                    $queryBuilder->setParameter('instance', $discriminator);   
                    dd($discriminator);
                }
            
        }
        return;
        // $queryBuilder->where('1=0');
    }
}