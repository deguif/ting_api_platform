<?php

namespace CCMBenchmark\Ting\ApiPlatform;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use CCMBenchmark\Ting\ApiPlatform\RepositoryProvider;
use CCMBenchmark\Ting\Repository\Repository;
use CCMBenchmark\TingBundle\Repository\RepositoryFactory;
use PommProject\Foundation\Pomm;

class ItemDataProvider implements ItemDataProviderInterface
{
    /**
     * @var RepositoryProvider
     */
    private $repositoryProvider;

    public function __construct(RepositoryProvider $repositoryProvider)
    {
        $this->repositoryProvider = $repositoryProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        /** @var Repository $repository */
        $repository = $this->repositoryProvider->getRepositoryFromResource($resourceClass);

        return $repository->get($id);
    }
}
