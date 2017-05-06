<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace spark\form\validator\annotation\validator;

use spark\form\validator\annotation\MediaFile;
use spark\upload\FileObjectFactory;
use spark\upload\FileSize;
use spark\utils\Collections;
use spark\utils\Objects;
use spark\utils\StringUtils;

class MediaFileAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return "spark\\form\\validator\\annotation\\MediaFile";
    }

    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value)
        || !Objects::isArray($value)
        || Collections::isEmpty($value)
        || $this->validateFile($annotation, $value);
    }

    public function validateFile($annotation, $value = array()) {
        $file = FileObjectFactory::create($value);
        /** @var MediaFile $annotation */
        return StringUtils::startsWith($file->getContentType(), $annotation->contentType)
        && $file->getSize() < $annotation->maxSize;
    }

    /**
     * @param MediaFile $annotation
     * @return array
     */
    public function getAnnotationValues($annotation) {
        return array(FileSize::getSizeAsKB($annotation->maxSize), $annotation->contentType);
    }
}