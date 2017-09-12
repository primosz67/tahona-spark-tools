<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 21.07.14
 * Time: 21:57
 */

namespace Spark\Controller;


use Doctrine\ORM\EntityManager;
use Spark\Common\IllegalArgumentException;
use Spark\Common\IllegalStateException;
use Spark\Controller;

use Spark\Persistence\tools\EntityManagerFactory;
use Spark\Persistence\tx\TransactionAware;
use Spark\Container;
use Spark\Utils\Asserts;

class TransactionAwareController extends Controller {

    use TransactionAware;

    /**
     * @return EntityManager
     */
    protected  function getEm() {
        return $this->get("entityManager");
    }

}