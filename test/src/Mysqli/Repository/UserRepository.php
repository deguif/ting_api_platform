<?php

namespace App\Mysqli\Repository;

use App\Mysqli\Entity\MysqliUser;
use CCMBenchmark\Ting\Repository\Metadata;
use CCMBenchmark\Ting\Repository\MetadataInitializer;
use CCMBenchmark\Ting\Repository\Repository;
use CCMBenchmark\Ting\Serializer\SerializerFactoryInterface;

class UserRepository extends Repository implements MetadataInitializer
{

    public static function initMetadata(SerializerFactoryInterface $serializerFactory, array $options = [])
    {
        $metadata = new Metadata($serializerFactory);

        $metadata->setEntity(MysqliUser::class);
        $metadata->setConnectionName($options['connection']);
        $metadata->setDatabase($options['database']);
        $metadata->setTable('user');
        $metadata->setRepository(self::class);
        $metadata->setRepository(self::class);
        $metadata->addField(
            [
                'primary'    => true,
                'fieldName'  => 'firstname',
                'columnName' => 'firstname',
                'type'       => 'string'
            ]
        )->addField(
            [
                'fieldName'  => 'lastname',
                'columnName' => 'lastname',
                'type'       => 'string'
            ]
        );

        return $metadata;
    }
}
