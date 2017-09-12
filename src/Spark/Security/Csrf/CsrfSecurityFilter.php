<?php

namespace Spark\Security\Csrf;

use Spark\Core\Annotation\Inject;
use Spark\Filter\FilterChain;
use Spark\Filter\HttpFilter;
use Spark\Http\Request;
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

    public function doFilter(Request $request, FilterChain $filterChain) {

        if ($request->isPost()) {
            $csrf = $request->getParam($this->formKey);

            /** @var CsrfHolder $csrfHolder */
            $csrfHolder = $request->getSession()->get($this->formKey);

            if (Objects::isNull($csrfHolder) || !$csrfHolder->isValid($csrf)) {
                throw new BadCsrfException();
            }
        }
    }
}