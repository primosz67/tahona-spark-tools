<?php

namespace Spark\Persistence\tx;
use Spark\Common\IllegalStateException;

/**
 *
 * 
 * Date: 25.08.15
 * Time: 20:19
 */

trait TransactionAware {

    protected function commitTransaction() {
        $em = $this->getEm();
        $connection = $em->getConnection();

        try {
            if ($connection->isTransactionActive()) {
                $em->flush();
                $connection->commit();
            } else {
                $em->flush();
            }
            $this->beginTransaction();
        } catch (\Exception $e) {
            if ($em->isOpen()) {
                $connection->rollback();
            }
            throw new IllegalStateException($e->getMessage(), $e);
        }
    }

    abstract protected function getEm();

    protected function beginTransaction() {
        $em = $this->getEm();
        $em->getConnection()->beginTransaction();
    }
} 