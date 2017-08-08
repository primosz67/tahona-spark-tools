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

            $formCsrfCode = CsrfCodeGenerator::getSessionCode($csrf);
            $sessionCsrf = $request->getSession()->get($this->formKey);

//            var_dump($csrf, $formCsrfCode, $sessionCsrf);

            if (Objects::isNull($csrf) || $formCsrfCode != $sessionCsrf) {
                throw new BadCsrfException();
            }
        }
    }
}