<?php
namespace Spark\Security\Csrf\View;


use Spark\Core\Annotation\Inject;
use Spark\Http\RequestProvider;
use Spark\Http\Session;
use Spark\Security\Csrf\CsrfHolder;
use Spark\Utils\Collections;
use Spark\Utils\UrlUtils;
use Spark\View\Smarty\SmartyPlugin;

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

    public function getTag(): string {
        return 'csrf';
    }

    public function execute(array $params, $smarty): string {
        $request = $this->requestProvider->getRequest();
        $session = $request->getSession();

        $csrfHolder = $this->getOrCreateCsrfHolder($session);
        $code = $csrfHolder->getCode($this->getUrl($params));

        $key = $this->formKey;
        $session->add($key, $csrfHolder);

        return "<input name='$key' type='hidden' value='$code' />";
    }

    private function getUrl($params) {
        if (Collections::hasKey($params, 'path')) {
            return UrlUtils::getSite() . $params['path'];
        }
        return UrlUtils::getCurrentUrl();
    }

    /**
     * @param $session
     * @return CsrfHolder
     */
    private function getOrCreateCsrfHolder(Session $session): CsrfHolder {
        if ($session->has($this->formKey)) {
            return $session->get($this->formKey);
        }
        return new CsrfHolder();
    }

}