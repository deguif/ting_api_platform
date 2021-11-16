<?php

namespace CCMBenchmark\Ting\ApiPlatform;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use CCMBenchmark\Ting\MetadataRepository;
use CCMBenchmark\Ting\Repository\Metadata;
use CCMBenchmark\Ting\Repository\Repository;
use CCMBenchmark\TingBundle\Repository\RepositoryFactory;

use function class_exists;

class RepositoryProvider
{
    /**
     * @var MetadataRepository
     */
    private $metadataRepository;
    /**
     * @var RepositoryFactory
     */
    private $ting;
    /**
     * @var string
     */
    private $repositoryName = '';

    public function __construct(RepositoryFactory $ting,MetadataRepository $metadataRepository)
    {
        $this->ting = $ting;
        $this->metadataRepository = $metadataRepository;
    }

    /**
     * @param string $resourceClass
     * @return Repository|null
     * @throws ResourceClassNotSupportedException
     */
    public function getRepositoryFromResource(string $resourceClass): ?Repository
    {
        if (! class_exists($resourceClass)) {
            return null;
        }

        $object = new $resourceClass();
        $this->metadataRepository->findMetadataForEntity($object,
            function (Metadata $metadata) {
                $this->repositoryName = $metadata->getRepository();
            },
            function () {
                throw new ResourceClassNotSupportedException();
            }
        );

        if (empty($this->repositoryName)) {
            return null;
        }
        $repository = $this->ting->get($this->repositoryName);

        return $repository;
    }
}
