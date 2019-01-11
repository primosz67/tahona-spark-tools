<?php
/**
 *
 *
 * Date: 27.11.16
 * Time: 11:50
 */

namespace Spark\Form\Validator;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Spark\Core\Lang\LangMessageResource;
use Spark\Utils\Collections;
use Spark\Utils\Functions;
use Spark\Utils\Objects;
use Spark\Utils\Reflection\AnnotationReaderProvider;

class DoctrineAnnotationValidator extends AnnotationValidator {

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em, LangMessageResource  $lang, AnnotationReaderProvider $annotationReader) {
        parent::__construct($lang, $annotationReader);
        $this->em = $em;
    }


    protected function getClassName($obj) {
        $c = Objects::getClassName($obj);
        $className = ClassUtils::getRealClass($c);
        return $className;
    }

}