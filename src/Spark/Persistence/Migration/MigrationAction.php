<?php
/**
 * Date: 04.09.18
 * Time: 19:02
 */

namespace Spark\Persistence\Migration;


interface MigrationAction {

    /**
     * @param DataUpdater $updater
     * @return array of Migration Objects
     */
    public function migrate(DataUpdater $updater) : array ;

}