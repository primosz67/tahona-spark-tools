<?php

namespace Spark\Security\Csrf;

use Spark\Core\Annotation\Inject;
use Spark\Core\Filter\FilterChain;
use Spark\Core\Filter\HttpFilter;
use Spark\Http\Request;
use Spark\Http\ResponseHelper;
use Spark\Http\Utils\RequestUtils;
use Spark\Security\Csrf\Exception\BadCsrfException;
use Spark\Utils\Objects;

class CsrfSecurityFilter implements HttpFilter {

    private $formKey;

    /**
     * CsrfSecurityFilter constructor.
     * @param $formKey
     */
    public function __construct(string $formKey) {
        $this->formKey = $formKey;
    }

    /**
     * @param Request $request
     * @param FilterChain $filterChain
     * @throws BadCsrfException
     */
    public function doFilter(Request $request, FilterChain $filterChain) {

        if ($request->isPost()) {
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
}