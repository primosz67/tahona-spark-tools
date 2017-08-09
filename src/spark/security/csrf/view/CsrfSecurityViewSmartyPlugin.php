<?php
namespace spark\security\csrf\view;


use spark\core\annotation\Inject;
use spark\http\RequestProvider;
use spark\http\Session;
use spark\security\csrf\CsrfHolder;
use spark\security\csrf\CsrfCodeGenerator;
use spark\tools\url\Url;
use spark\utils\Collections;
use spark\utils\StringUtils;
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
        $session = $request->getSession();

        $csrfHolder = $this->getOrCreateCsrfHolder($session);
        $code = $csrfHolder->getCode($this->getUrl($params));

        $key = $this->formKey;
        $session->add($key, $csrfHolder);


        return "<input name='$key' type='hidden' value='$code' />";
    }

    private function getUrl($params) {

        if (Collections::hasKey($params, "path")) {
            return UrlUtils::getSite() . $params["path"];
        }
        return UrlUtils::getCurrentUrl();
    }

    /**
     * @param $session
     * @return CsrfHolder
     */
    private function getOrCreateCsrfHolder(Session $session) {
        if ($session->has($this->formKey)) {
            return $session->get($this->formKey);
        }
        return new CsrfHolder();
    }

}