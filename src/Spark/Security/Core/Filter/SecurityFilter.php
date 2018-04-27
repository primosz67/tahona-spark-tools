<?php
/**
 *
 * 
 * Date: 06.03.17
 * Time: 07:29
 */

namespace Spark\Security\Core\Filter;


use Spark\Core\Annotation\Inject;
use Spark\Core\Filter\FilterChain;
use Spark\Core\Filter\HttpFilter;
use Spark\Http\Request;
use Spark\Http\RequestProvider;
use Spark\Routing;
use Spark\Security\Core\SecurityManager;
use Spark\Security\Core\Service\AuthenticationService;
use Spark\Security\Exception\AccessDeniedException;

/**
 * For use use  EnableSecurity annotation
 */
class SecurityFilter implements HttpFilter {

    /**
     * @Inject()
     * @var SecurityManager
     */
    private $securityManager;

    /**
     * @param Request $request
     * @param FilterChain $filterChain
     * @throws AccessDeniedException
     */
    public function doFilter(Request $request, FilterChain $filterChain) {
        $hasUserAccess = $this->securityManager->hasAccess($request);

        if (!$hasUserAccess) {
            throw new AccessDeniedException();
        }

        $filterChain->doFilter($request);
    }
}