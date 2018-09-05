<?php
/**
 * Date: 05.09.18
 * Time: 04:27
 */

namespace Spark\Persistence\Migration;


use Spark\Common\Collection\FluentIterables;
use Spark\Core\Annotation\PostConstruct;
use Spark\Core\Provider\BeanProvider;
use Spark\Utils\Predicates;

class MigrationService {


    /**
     * @var array MigrationAction
     */
    private $migrationActions;

    private $beanProvider;
    private $dataUpdater;

    public function __construct(DataUpdater $dataUpdater, BeanProvider $beanProvider) {
        $this->dataUpdater = $dataUpdater;
        $this->beanProvider = $beanProvider;
    }

    /**
     * @PostConstruct()
     */
    public function init() {
        $this->migrationActions = $this->beanProvider->getByType(MigrationAction::class);
        $this->beanProvider = null;
    }

    public function executeActions(): array {
        return FluentIterables::of($this->migrationActions)
            ->flatMap(function ($action) {
                /** @var MigrationAction $action */
                return $action->migrate($this->dataUpdater);
            })->filter(Predicates::notNull())
            ->getList();
    }

}