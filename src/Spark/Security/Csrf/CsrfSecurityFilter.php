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

    /**
     * @param Request $request
     * @param FilterChain $filterChain
     * @throws BadCsrfException
     */
    public function doFilter(Request $request, FilterChain $filterChain) {

        if ($request->isPost()) {
            $csrf = $request->getParam($this->formKey);

            /** @var CsrfHolder $csrfHolder */
            $csrfHolder = $this->getCrsfHolder($request);

            if (Objects::isNull($csrfHolder) || !$csrfHolder->isValid($csrf)) {
                throw new BadCsrfException('Bad Csrf for POST request');
            }
        }
    }

    private function getCrsfHolder(Request $request): CsrfHolder {
        return $request->getSession()->get($this->formKey);
    }
}