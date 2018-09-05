<?php
/**
 * Date: 04.09.18
 * Time: 06:35
 */

namespace Spark\Persistence\Migration;


interface MigrationObjectFactory {

    public function createVersionObject() : DataMigration;

}