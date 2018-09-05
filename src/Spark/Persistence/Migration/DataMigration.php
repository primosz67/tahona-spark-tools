<?php
/**
 * Date: 04.09.18
 * Time: 06:14
 */

namespace Spark\Persistence\Migration;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * @MappedSuperclass()
 */
class DataMigration {

    public const D_NAME = 'name';
    public const D_VERSION = 'version';
    public const D_ID = 'id';

    /**
     * @var int
     *
     * @Column(name="id", type="bigint", nullable=false)
     * @Id()
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(name="name", type="string")
     * @var string
     */
    private $name;

    /**
     * @Column(name="version", type="integer")
     * @var int
     */
    private $version;

    /**
     * @Column(name="query", type="blob")
     * @var string
     */
    private $query;

    /**
     * @Column(name="execution_date", type="datetime")
     * @var \DateTime
     */
    private $executionDate;

    public function setName(string $name) {
        $this->name = $name;
    }

    public function setVersion(int $version) {
        $this->version = $version;
    }

    public function setQuery(string $query) {
        $this->query = $query;
    }

    public function setExecutionDate(\DateTime $executionDate) {
        $this->executionDate = $executionDate;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getVersion(): int {
        return $this->version;
    }

    public function getQuery(): string {
        return $this->query;
    }

    public function getExecutionDate(): \DateTime {
        return $this->executionDate;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
}

