<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 27.11.16
 * Time: 11:50
 */

namespace spark\form\validator;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use spark\core\lang\LangMessageResource;
use spark\utils\Objects;

class DoctrineAnnotationValidator extends AnnotationValidator {

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em, LangMessageResource  $lang, AnnotationReader $annotationReader) {
        parent::__construct($lang, $annotationReader);
        $this->em = $em;
    }


    protected function getClassName($obj) {
        return $this->em->getClassMetadata(Objects::getClassName($obj))->name;
    }

}