<?php

namespace spark\persistence\tools;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\ClassLoader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Objects;
use spark\utils\StringUtils;

class EntityManagerBuilder {

    public static function build($param) {
        $entityPackages = $param["entityPackages"];
        $proxyPath = $param["proxyPath"];

        Asserts::checkState(Objects::isArray($entityPackages),"entityPackages must be an Array");
        Asserts::notNull($proxyPath, "proxyPath cannot null");

        foreach ($entityPackages as $package) {
            $classLoader = new ClassLoader($package);
            $classLoader->register();
        }

        $driver = new AnnotationDriver(new AnnotationReader());
        $driver->addPaths($entityPackages);

//        AnnotationRegistry::registerLoader('class_exists');

        $config = new Configuration();
        $config->setMetadataDriverImpl($driver);

        if (extension_loaded('apc')) {
            $modeNameSpace = $param["mode"];

            $config->setAutoGenerateProxyClasses(true);
            $config->setMetadataCacheImpl(self::createCache($modeNameSpace));
            $config->setResultCacheImpl(self::createCache($modeNameSpace));
            $config->setQueryCacheImpl(self::createCache($modeNameSpace));

        } else {
            $config->setAutoGenerateProxyClasses(true);
            $config->setMetadataCacheImpl(new ArrayCache());
        }

        $config->setProxyDir("../src".$proxyPath);
        $class = StringUtils::replace($proxyPath, "/", "\\");
        $namespace = StringUtils::substr(1, strlen($class), $class);
        $config->setProxyNamespace($namespace);

        $connectionParams = array(
            'driver' => 'pdo_mysql',
            'host' => $param["host"],
            'port' => '3306',
            'user' => $param["user"],
            'password' => $param["password"],
            'dbname' => $param["database"],
            'charset' => 'utf8',
        );

        return EntityManager::create($connectionParams, $config);
    }

    /**
     * @return ApcCache
     */
    private static function createCache($modeNameSpace) {
        $apcCache = new ApcCache();
        $apcCache->setNamespace($modeNameSpace);
        return $apcCache;
    }

}
