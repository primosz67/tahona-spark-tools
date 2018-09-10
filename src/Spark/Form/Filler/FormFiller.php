<?php

namespace Spark\Form\Filler;

use ReflectionClass;
use Spark\Common\Collection\FluentIterables;
use Spark\Common\IllegalStateException;
use Spark\Core\Annotation\Inject;
use Spark\Core\Filler\Filler;
use Spark\Core\Filler\MultiFiller;
use Spark\Form\DataBinder;
use Spark\Form\Errors;
use Spark\Form\Validator\AnnotationValidator;
use Spark\Http\RequestProvider;
use Spark\Utils\Collections;
use Spark\Utils\Objects;

class FormFiller implements MultiFiller {

    /**
     * @Inject
     * @var RequestProvider
     */
    private $requestProvider;


    /**
     * @Inject
     * @var AnnotationValidator
     */
    private $annotationValidator;


    public function getValue($name, $type): array {
        if (Objects::isNotNull($type)) {
            $cls = new ReflectionClass($type);

            $cons = $cls->getConstructor();
            if ($this->hasNoParameterConstructor($cons)) {
                $form = new $type();

                $request = $this->requestProvider->getRequest();
                $binder = new DataBinder($request->getAllParams());
                $binder->setValidators($this->annotationValidator);

                $binder->bind($form);
                return [$form, $binder];
            }
        }
        return [null];
    }

    private function hasNoParameterConstructor(?\ReflectionMethod $cons): bool {
        return Objects::isNull($cons)
            || $cons->getNumberOfParameters() === 0;
    }

    public function filter(array $parameters): array {
        $results = [];
        $lastResult = [null];
        foreach ($parameters as $name => $type) {
            if ($type === Errors::class) {
                if (!Collections::hasKey($lastResult,1)) {
                    throw new IllegalStateException('Errors object should be added after the validated dto');
                }
                $results[$name] = $lastResult[1];
            } else {
                $lastResult = $this->getValue($name, $type);
                $results[$name] = $lastResult[0];
            }

        }
        return $results;
    }

    /**
     * @return int
     */
    public function getOrder() {
        return 100;
    }
}