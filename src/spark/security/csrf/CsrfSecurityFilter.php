<?php

namespace spark\security\csrf;

use spark\core\annotation\Inject;
use spark\filter\FilterChain;
use spark\filter\HttpFilter;
use spark\http\Request;
use spark\security\csrf\exception\BadCsrfException;
use spark\utils\Objects;


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