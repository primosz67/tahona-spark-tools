<?php
/**
 * Date: 04.09.18
 * Time: 06:18
 */

namespace Spark\Persistence\Migration;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Spark\Utils\DateUtils;
use Spark\Utils\Objects;

class DataUpdater {


    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var MigrationObjectFactory
     */
    private $factory;

    public function __construct(EntityManager $entityManager, MigrationObjectFactory $factory) {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    public function update($title, $query, $version = 0): ?DataMigration {

        $migration = $this->factory->createVersionObject();

        $count = $this->entityManager->getRepository(Objects::getClassName($migration))
            ->count([
                DataMigration::D_NAME => $title,
                DataMigration::D_VERSION => $version,
            ]);

        if ($count === 0) {
            $migration->setName($title);
            $migration->setQuery($query);
            $migration->setExecutionDate(DateUtils::now());
            $migration->setVersion($version);

            $this->entityManager->transactional(function ($em) use ($query, $migration) {
                /** @var EntityManager $em */
                $em->getConnection()->executeUpdate($query);
                $em->persist($migration);
            });
            return $migration;
        }
        return null;
    }

}