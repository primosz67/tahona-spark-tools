<?php
/**
 *
 * 
 * Date: 05.03.17
 * Time: 22:32
 */

namespace Spark\Security\Core;


use Spark\Core\Annotation\Inject;
use Spark\Core\Routing\RequestData;
use Spark\Core\Routing\RoutingDefinition;
use Spark\Http\Request;
use Spark\Http\RequestProvider;
use Spark\Routing;
use Spark\Routing\RoutingUtils;
use Spark\Security\Core\Domain\AuthUser;
use Spark\Security\Core\Service\AuthenticationService;
use Spark\Utils\Collections;
use Spark\Utils\Objects;

class SecurityManager {
    const NAME = "securityManager";

    /**
     * @Inject
     * @var AuthenticationService
     */
    private $authenticationService;

    private $classDefinitions;
    private $methodDefinitions;

    public function __construct() {
        $this->classDefinitions = array();
        $this->methodDefinitions = array();
    }

    /**
     * Return roles for current request
     * @param RequestData $request
     * @return array|null
     */
    public function getAuthorizedRoles($request): ?array {
        $controllerName = $request->getControllerClassName();
        $methodName = $request->getMethodName();
        return $this->getRoles($controllerName, $methodName);
    }

    public function addClassRoles($className, $roles) {
        $this->classDefinitions[$className] = $roles;
    }

    public function addMethodRoles($className, $methods, $roles = array()) {
        $classRoles = Collections::getValueOrDefault($this->classDefinitions, $className, array());
        $this->methodDefinitions[$this->buildKey($className, $methods)] = Collections::merge($classRoles, $roles);
    }

    public function buildKey($className, $methodName) {
        return $className . "#" . $methodName;
    }

    public function hasAccess(Request $request) {
        $authorizedRoles = $this->getAuthorizedRoles($request);

        return $this->isNoRoleNeeded($authorizedRoles)
        || $this->isLoggedUserNeededOnly($authorizedRoles)
        || $this->hasUserAnyRoleOfAuthorizedRoles($authorizedRoles);
    }

    /**
     * @param array $authorizedRoles - array of roles on Path  (check:  Request->roles)
     * @return bool
     */
    public function hasUserAnyRole(AuthUser $authUser, $authorizedRoles = array()) {
        $userRoles = $authUser->getRoles();

        foreach ($authorizedRoles as $authorizedRole) {
            if ($authUser->hasRole($authorizedRole)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $roles
     * @return bool
     */
    private function isNoRoleNeeded($roles) {
        return Objects::isNull($roles);
    }

    /**
     * @param $authorizedRoles
     * @return bool
     */
    private function isLoggedUserNeededOnly($authorizedRoles) {
        return Collections::isEmpty($authorizedRoles) && $this->authenticationService->isLogged();
    }

    /**
     * @param $authorizedRoles
     * @return bool
     * @throws \Spark\Common\IllegalArgumentException
     */
    private function hasUserAnyRoleOfAuthorizedRoles($authorizedRoles) {
        if ($this->authenticationService->isLogged()) {
            $authUser = $this->authenticationService->getAuthUser();
            return $this->hasUserAnyRole($authUser, $authorizedRoles);
        }
        return false;
    }

    /**
     *
     * @param Request $request
     * @return array|null
     */
    private function getRolesForMethod($controllerName, $methodName): ?array {
        $key = $this->buildKey($controllerName, $methodName );
        $value = Collections::getValue($this->methodDefinitions, $key);
        return $value;
    }

    /**
     *
     * @param Request $request
     * @return array|null
     */
    private function getRolesForClass($controllerName) {
        return Collections::getValue($this->classDefinitions, $controllerName);
    }

    /**
     * @param $controllerName
     * @param $methodName
     * @return array|null
     */
    public function getRoles($controllerName, $methodName) {
        $roles = $this->getRolesForMethod($controllerName, $methodName);
        $rolesForClass = $this->getRolesForClass($controllerName);
        return Objects::isNotNull($roles) ? $roles : $rolesForClass;
    }
}