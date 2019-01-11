<?php
/**
 *
 *
 * Date: 21.07.14
 * Time: 21:57
 */

namespace Spark\Controller;


use Doctrine\ORM\EntityManager;
use Spark\Controller;
use Spark\Core\Annotation\Inject;
use Spark\Core\Provider\BeanProvider;
use Spark\Persistence\tx\TransactionAware;

class TransactionAwareController extends Controller {

    use TransactionAware;

    private $entityManager;

    /**
     * @Inject
     * @var BeanProvider
     */
    private $beanProvider;

    public function initBeans() {
        $this->entityManager = $this->beanProvider->getBean('entityManager');
        $this->beanProvider = null;
    }
    /**
     * @return EntityManager
     */
    protected  function getEm() {
        return $this->entityManager;
    }

}