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

        $entityPackages = $dataConfig->getEntityPackages();

        $proxyPath = $this->config->getProperty("app.path") . "/src/proxy";

        Asserts::checkState(Objects::isArray($entityPackages), "entityPackages must be an Array");
        Asserts::notNull($proxyPath, "proxyPath cannot null");

        $driver = new AnnotationDriver(new AnnotationReader());
        $driver->addPaths($entityPackages);

        $config = new Configuration();
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

        } else {
            $config->setAutoGenerateProxyClasses(true);
            $config->setMetadataCacheImpl(new ArrayCache());
            $config->setQueryCacheImpl(new ArrayCache());
        }

        $dir = $proxyPath;
        if (!FileUtils::isDir($dir)) {
            mkdir($dir);
        }

        $config->setProxyDir($dir);
        $class = StringUtils::replace($proxyPath, "/", "\\");
        $namespace = StringUtils::substring($class, 1, strlen($class));

        $config->setProxyNamespace("proxy");

        $entityNamespaces = Collections::builder($entityPackages)
            ->map(StringFunctions::replace("/", "\\"))
            ->get();

        $config->setEntityNamespaces($entityNamespaces);

        $connectionParams = array(
            'driver' => $dataConfig->getDriver(),
            'host' => $dataConfig->getHost(),
            'port' => $dataConfig->getPort(),
            'user' => $dataConfig->getUsername(),
            'password' => $dataConfig->getPassword(),
            'dbname' => $dataConfig->getDbname(),
            'charset' => $dataConfig->getCharset(),
        );

        return EntityManager::create($connectionParams, $config);
    }

    private static function createCache($namespace) {
        $apcCache = new ApcuCache();
        $apcCache->setNamespace($namespace . "_u_" . uniqid("s"));
        return $apcCache;
    }

}
