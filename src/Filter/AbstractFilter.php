<?php

namespace CCMBenchmark\Ting\ApiPlatform\Filter;

use CCMBenchmark\Ting\ApiPlatform\RepositoryProvider;
use CCMBenchmark\Ting\MetadataRepository;
use CCMBenchmark\Ting\Repository\Metadata;
use CCMBenchmark\Ting\Repository\Repository;

abstract class AbstractFilter
{
    /**
     * @var RepositoryProvider
     */
    private $repositoryProvider;
    /**
     * @var MetadataRepository
     */
    protected $metadataRepository;
    /**
     * @var array
     */
    protected $properties;

    public function __construct(
        RepositoryProvider $repositoryProvider,
        MetadataRepository $metadataRepository,
        array $properties = []
    ) {
        $this->repositoryProvider = $repositoryProvider;
        $this->metadataRepository = $metadataRepository;
        $this->properties = $properties;
    }

    protected function getTypeMariaForProperty(string $property, string $resourceClass): string
    {
        $fields = $this->getMetadataForResourceClass($resourceClass)->getFields();

        foreach ($fields as $field) {
            if ($field['fieldName'] === $property) {
                return $field['type'];
            }
        }

        return 'varchar';
    }

    protected function getTypeForProperty(string $property, string $resourceClass): string
    {
        $fields = $this->getMetadataForResourceClass($resourceClass)->getFields();

        foreach ($fields as $field) {
            if ($field['fieldName'] === $property) {
                return $field['type'];
            }
        }

        return 'varchar';
    }

    protected function getFieldNamesForResource(string $resourceClass): array
    {
        $metadata = $this->getMetadataForResourceClass($resourceClass);

        return $metadata->getFields();
    }

    private function getMetadataForResourceClass(string $resourceClass): Metadata
    {
        /** @var Repository $repository */
        $repository = $this->repositoryProvider->getRepositoryFromResource($resourceClass);

        return $repository->getMetadata();
    }
}
