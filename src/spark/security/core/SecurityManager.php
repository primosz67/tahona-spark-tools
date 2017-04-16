<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 05.03.17
 * Time: 22:32
 */

namespace spark\security\core;


use spark\core\annotation\Inject;
use spark\core\routing\RoutingDefinition;
use spark\http\RequestProvider;
use spark\Routing;
use spark\routing\RoutingUtils;
use spark\utils\Collections;

class SecurityManager {
    const NAME = "securityManager";

    /**
     * @Inject
     * @var Routing
     */
    private $routing;

    /**
     * @Inject
     * @var RequestProvider
     */
    private $requestProvider;


    private $definitions;

    public function addAll($routing = array()) {

        /** @var RoutingDefinition $routingDefinition */
        foreach ($routing as $routingDefinition) {
            $this->definitions[$routingDefinition->getPath()] = $routingDefinition->getRoles();
        }
    }

    /**
     * Return roles for current request
     * @return array
     */
    public function getRoles() {
        $request1 = $this->routing->getCurrentDefinition();
        return Collections::getValue($this->definitions, $request1->getPath());
    }

    public function addRoles($roles, $paths) {
        foreach ($paths as $path) {
            $this->definitions[$path] = $roles;
        }
    }
}