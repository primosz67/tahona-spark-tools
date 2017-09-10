<?php
namespace spark\form\filler;

use spark\core\annotation\Inject;
use spark\core\filler\Filler;
use spark\form\DataBinder;
use spark\http\RequestProvider;
use spark\http\utils\RequestUtils;
use spark\utils\Objects;

class FormFiller implements Filler {

    /**
     * @Inject
     * @var RequestProvider
     */
    private $requestProvider;

    public function getValue($name, $type) {
        if (Objects::isNotNull($type)) {
            $cls = new \ReflectionClass($type);

            $cons = $cls->getConstructor();
            if($this->hasNoParameterContructor($cons) ){
                $form = new $type();

                $request = $this->requestProvider->getRequest();
                $binder = new DataBinder($request->getAllParams());

                $binder->bind($form);
                return $form;
            }
        }
        return null;
    }

    /**
     * @param $cons
     * @param $cls
     * @return bool
     */
    private function hasNoParameterContructor($cons) {
        return Objects::isNull($cons)
        || $cons->getNumberOfParameters() === 0;
    }

    public function getOrder() {
        return 200;
    }
}