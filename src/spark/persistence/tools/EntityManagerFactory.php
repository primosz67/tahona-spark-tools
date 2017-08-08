<?php

namespace spark\persistence\tools;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\ClassLoader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use spark\Config;
use spark\core\annotation\handler\EnableApcuAnnotationHandler;
use spark\persistence\annotation\handler\EnableDataRepositoryAnnotationHandler;
use spark\security\utils\PassUtils;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\FileUtils;
use spark\utils\Objects;
use spark\utils\StringFunctions;
use spark\utils\StringUtils;


class EntityManagerFactory {
    const SPARK_APCU_CACHE_DB_CONFIG_ID = "spark.apcu.cache.db.id";

    /**
     * @var Config
     */
    private $config;

    /**
     * EntityManagerFactory constructor.
     * @param $config
     */
    public function __construct($config) {
        $this->config = $config;
    }

    public function createEntityManager(DataSource $dataConfig) {
        $entityPackages = $this->getEntityPackages($dataConfig);
        $proxyPath = $this->getProxyPath();

        $config = $this->buildDoctrineConfiguration($entityPackages, $proxyPath);
        $connectionParams = $this->convertToConnectionConfigArray($dataConfig);

        return EntityManager::create($connectionParams, $config);
    }

    private static function createCache($namespace) {
        $apcCache = new ApcuCache();
        $apcCache->setNamespace($namespace . "_u_" . uniqid("s"));
        return $apcCache;
    }

    /**
     * @param DataSource $dataConfig
     * @return array
     */
    private function convertToConnectionConfigArray(DataSource $dataConfig) {
        $connectionParams = array(
            'driver' => $dataConfig->getDriver(),
            'host' => $dataConfig->getHost(),
            'port' => $dataConfig->getPort(),
            'user' => $dataConfig->getUsername(),
            'password' => $dataConfig->getPassword(),
            'dbname' => $dataConfig->getDbname(),
            'charset' => $dataConfig->getCharset(),
        );
        return $connectionParams;
    }

    /**
     * @param $entityPackages
     * @return Configuration
     */
    private function createConfig($entityPackages) {
        $driver = new AnnotationDriver(new AnnotationReader());
        $driver->addPaths($entityPackages);

        $config = new Configuration();
        $config->setProxyNamespace("proxy");
        $config->setMetadataDriverImpl($driver);

        $apcuEnabled = $this->config->getProperty(EnableApcuAnnotationHandler::APCU_CACHE_ENABLED);

        if ($apcuEnabled) {
            $code = $this->config->getProperty(self::SPARK_APCU_CACHE_DB_CONFIG_ID);

            if (Objects::isNull($code)) {
                $config->setAutoGenerateProxyClasses(true);
                $code = PassUtils::genCode();
            }

            $config->setMetadataCacheImpl(self::createCache($code));
            $config->setResultCacheImpl(self::createCache($code));
            $config->setQueryCacheImpl(self::createCache($code));

            $this->config->set(self::SPARK_APCU_CACHE_DB_CONFIG_ID, $code);
            return $config;

        } else {
            $config->setAutoGenerateProxyClasses(true);
            $config->setMetadataCacheImpl(new ArrayCache());
            $config->setQueryCacheImpl(new ArrayCache());
            return $config;
        }
    }

    /**
     * @param $proxyPath
     * @return mixed
     */
    private function createProxyDir($proxyPath) {
        $dir = $proxyPath;
        if (!FileUtils::isDir($dir)) {
            mkdir($dir);
            return $dir;
        }
        return $dir;
    }

    /**
     * @param $entityPackages
     * @return array
     */
    private function convertPathToNamespaces($entityPackages) {
        $entityNamespaces = Collections::builder($entityPackages)
            ->map(StringFunctions::replace("/", "\\"))
            ->get();
        return $entityNamespaces;
    }

    /**
     * @param DataSource $dataConfig
     * @return array
     * @throws \spark\common\IllegalStateException
     */
    private function getEntityPackages(DataSource $dataConfig) {
        $entityPackages = $dataConfig->getEntityPackages();
        Asserts::checkState(Objects::isArray($entityPackages), "entityPackages must be an Array");
        return $entityPackages;
    }

    /**
     * @return string
     * @throws \spark\common\IllegalArgumentException
     */
    private function getProxyPath() {
        $proxyPath = $this->config->getProperty("app.path") . "/src/proxy";
        Asserts::notNull($proxyPath, "proxyPath cannot null");
        return $proxyPath;
    }

    /**
     * @param $entityPackages
     * @param $proxyPath
     * @return Configuration
     */
    private function buildDoctrineConfiguration($entityPackages, $proxyPath) {
        $config = $this->createConfig($entityPackages);
        $config->setProxyDir($this->createProxyDir($proxyPath));
        $config->setEntityNamespaces($this->convertPathToNamespaces($entityPackages));
        return $config;
    }

}
