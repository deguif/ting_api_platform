<?php

namespace CCMBenchmark\Ting\ApiPlatform;

use ApiPlatform\Core\Api\FilterLocatorTrait;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use Aura\SqlQuery\Common\SelectInterface;
use CCMBenchmark\Ting\ApiPlatform\Filter\FilterInterface;
use CCMBenchmark\Ting\MetadataRepository;
use CCMBenchmark\Ting\Repository\HydratorSingleObject;
use CCMBenchmark\Ting\Repository\Repository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_column;
use function dump;
use function implode;
use function is_array;
use function is_string;
use function sprintf;
use function str_replace;

class CollectionDataProvider implements CollectionDataProviderInterface
{
    use FilterLocatorTrait;

    const PAGE_PARAMETER_NAME_DEFAULT = 'page';

    /**
     * @var RepositoryProvider
     */
    private $repositoryProvider;
    /**
     * @var MetadataRepository
     */
    private $metadataRepository;
    /**
     * @var RequestStack
     */
    private $requestStack;
    private $pagination;
    private $order;

    public function __construct(
        RepositoryProvider $repositoryProvider,
        ContainerInterface $filterLocator,
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        RequestStack $requestStack,
        array $pagination,
        array $order
    ) {
        $this->repositoryProvider = $repositoryProvider;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->requestStack = $requestStack;
        $this->pagination = $pagination;
        $this->order = $order;
        $this->setFilterLocator($filterLocator);
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return;
        }

        $repository = $this->repositoryProvider->getRepositoryFromResource($resourceClass);

        if ($repository === null) {
            return [];
        }

        $fields = array_column($repository->getMetadata()->getFields(), 'columnName');

        /** @var SelectInterface $builder */
        $builder = $repository->getQueryBuilder(Repository::QUERY_SELECT);
        $builder->cols($fields);
        $builder->from($repository->getMetadata()->getTable());
        $this->getWhere($request, $repository, $builder);
        $this->getCurrentPage($request, $builder);
        $this->getItemsPerPage($request, $builder);
        $this->getOrder($request, $builder);
        $query = $repository->getQuery($builder->getStatement());
        return $query->query($repository->getCollection(new HydratorSingleObject()));
    }

    private function getCurrentPage(Request $request, SelectInterface $queryBuilder)
    {
        $page = $request->query->getInt(
            $this->pagination['page_parameter_name'] ?? static::PAGE_PARAMETER_NAME_DEFAULT,
            1
        );
        if ($page < 2) {
            return;
        }

        $page -= 1;
        $itemsPerPage = $request->query->getInt(
            $this->pagination['items_per_page_parameter_name'],
            $this->pagination['items_per_page']
        );

        $queryBuilder->offset($page * $itemsPerPage);
    }

    private function getItemsPerPage(Request $request, SelectInterface $queryBuilder)
    {
        $queryBuilder->limit($request->query->get(
            $this->pagination['items_per_page_parameter_name'],
            $this->pagination['items_per_page']
        ));
    }

    /**
     * @param Request $request
     * @return array|string
     */
    private function getOrder(Request $request, SelectInterface $queryBuilder)
    {
        $properties = $request->query->get($this->order['order_parameter_name']);
        if ($properties === null) {
            return;
        }

        $suffix = [];
        foreach ($properties as $property => $order) {
            $suffix[] = "$property $order";
        }
        if ($suffix !== []) {
            $queryBuilder->orderBy($suffix);
        }
    }

    private function getWhere(Request $request, Repository $repository, SelectInterface $queryBuilder)
    {
        $properties = $repository->getMetadata()->getFields();

        $where = '';
        foreach ($properties as $property) {
            if ($request->query->has($property['fieldName'])) {
                $value = $request->query->get($property['fieldName']);
                $where = $this->getClauseFilter($repository->getMetadata()->getEntity(), 'get', $property['columnName'], $value);
            }
        }
        if ($where != '') {
            $where = str_replace('<<', "'", $where);
            $where = str_replace('>>', "'", $where);

            $queryBuilder->where($where);
        }
    }

    private function getClauseFilter(string $resourceClass, string $operationName, string $property, $value): string
    {
        $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);
        $resourceFilters = $resourceMetadata->getCollectionOperationAttribute($operationName, 'filters', [], true);

        $where = '';
        if (empty($resourceFilters)) {
            return $where;
        }

        foreach ($resourceFilters as $filterName) {
            $filter = $this->getFilter($filterName);
            if ($filter instanceof FilterInterface) {
                $where = $filter->addClause($property, $value, $resourceClass);
            } else {
                if (is_array($value)) {
                    $where = sprintf('%s in (%s)', $property, '<<' . implode('>>,<<', $value).'>>');
                } else {
                    $where = "$property = " . $this->cast($value);
                }
            }
        }



        return $where;
    }

    private function cast($value)
    {
        if (is_string($value)) {
            return "<<$value>>";
        }

        return $value;
    }
}
