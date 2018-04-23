<?php

namespace Spark\Security\Csrf;

use Spark\Core\Annotation\Inject;
use Spark\Core\Filter\FilterChain;
use Spark\Core\Filter\HttpFilter;
use Spark\Core\Routing\RequestData;
use Spark\Http\Request;
use Spark\Http\ResponseHelper;
use Spark\Http\Utils\RequestUtils;
use Spark\Security\Csrf\Exception\BadCsrfException;
use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

class CsrfSecurityFilter implements HttpFilter {

    private $formKey;
    private $excludedPaths;

    /**
     * CsrfSecurityFilter constructor.
     * @param $formKey
     */
    public function __construct(string $formKey) {
        $this->formKey = $formKey;
        $this->excludedPaths = [];
    }

    /**
     * @param Request $request
     * @param FilterChain $filterChain
     * @throws BadCsrfException
     */
    public function doFilter(Request $request, FilterChain $filterChain) {
        if ($request->isPost() && !$this->isExcluded($request)) {
            $csrf = $request->getParam($this->formKey);
            $session = $request->getSession();

            /** @var CsrfHolder $csrfHolder */
            $csrfHolder = $session
                ->get($this->formKey);

            if (Objects::isNull($csrfHolder) || !$csrfHolder->isValid($csrf)) {
                throw new BadCsrfException('Bad Csrf for POST request');
            }
        }
    }

    public function exclude(array $paths): void {
        Collections::addAll($this->excludedPaths, $paths);
    }

    private function isExcluded(Request $request): bool {
        if ($request instanceof RequestData) {
            /** @var RequestData $request */
            $requestPath = $request->getRouteDefinition()->getPath();

            return Collections::anyMatch($this->excludedPaths, function ($p) use ($requestPath) {
                return StringUtils::startsWith($requestPath, $p);
            });
        }

        return false;
    }
}