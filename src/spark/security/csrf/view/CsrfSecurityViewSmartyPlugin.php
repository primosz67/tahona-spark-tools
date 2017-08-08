<?php
namespace spark\security\csrf\view;


use spark\core\annotation\Inject;
use spark\http\RequestProvider;
use spark\security\csrf\CsrfCodeGenerator;
use spark\utils\UrlUtils;
use spark\view\smarty\SmartyPlugin;

class CsrfSecurityViewSmartyPlugin implements SmartyPlugin {

    /**
     * @Inject
     * @var RequestProvider
     */
    private $requestProvider;

    private $formKey;

    /**
     * CsrfSecurityViewSmartyPlugin constructor.
     * @param $formKey
     */
    public function __construct($formKey) {
        $this->formKey = $formKey;
    }

    public function getTag() {
        return "csrf";
    }

    public function execute($params, $smarty) {
        $request = $this->requestProvider->getRequest();
        $code = CsrfCodeGenerator::generate();

        $request->getSession()->add($this->formKey, CsrfCodeGenerator::getSessionCode($code));
        return "<input name='csrf' type='hidden' value='$code' />";
    }
}