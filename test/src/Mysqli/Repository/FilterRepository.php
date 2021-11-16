<?php

namespace App\Mysqli\Repository;

use App\Mysqli\Entity\MysqliFilter;
use CCMBenchmark\Ting\Repository\Metadata;
use CCMBenchmark\Ting\Repository\MetadataInitializer;
use CCMBenchmark\Ting\Repository\Repository;
use CCMBenchmark\Ting\Serializer\SerializerFactoryInterface;

class FilterRepository extends Repository implements MetadataInitializer
{

    public static function initMetadata(SerializerFactoryInterface $serializerFactory, array $options = [])
    {
        $metadata = new Metadata($serializerFactory);

        $metadata->setEntity(MysqliFilter::class);
        $metadata->setConnectionName($options['connection']);
        $metadata->setDatabase($options['database']);
        $metadata->setTable('filter');
        $metadata->setRepository(self::class);
        $metadata->addField(
            [
                'primary'    => true,
                'fieldName'  => 'name',
                'columnName' => 'name',
                'type'       => 'string'
            ]
        )->addField(
            [
                'fieldName'  => 'value',
                'columnName' => 'value',
                'type'       => 'string'
            ]
        )->addField(
            [
                'fieldName'  => 'valuePartial',
                'columnName' => 'value_partial',
                'type'       => 'string'
            ]
        )->addField(
            [
                'fieldName'  => 'valueStart',
                'columnName' => 'value_start',
                'type'       => 'string'
            ]
        )->addField(
            [
                'fieldName'  => 'valueEnd',
                'columnName' => 'value_end',
                'type'       => 'string'
            ]
        )->addField(
            [
                'fieldName'  => 'valueWordStart',
                'columnName' => 'value_word_start',
                'type'       => 'string'
            ]
        )->addField(
            [
                'fieldName'  => 'valueIpartial',
                'columnName' => 'value_ipartial',
                'type'       => 'string'
            ]
        );

        return $metadata;
    }
}
