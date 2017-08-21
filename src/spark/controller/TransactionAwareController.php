<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 21.07.14
 * Time: 21:57
 */

namespace spark\controller;


use Doctrine\ORM\EntityManager;
use spark\common\IllegalArgumentException;
use spark\common\IllegalStateException;
use spark\Controller;

use spark\persistence\tools\EntityManagerFactory;
use spark\persistence\tx\TransactionAware;
use spark\Container;
use spark\utils\Asserts;

class TransactionAwareController extends Controller {

    use TransactionAware;

    /**
     * @return EntityManager
     */
    protected  function getEm() {
        return $this->get("entityManager");
    }

}