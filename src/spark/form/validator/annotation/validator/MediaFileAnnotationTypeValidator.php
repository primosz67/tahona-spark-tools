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
use tahona\media\domain\Media;

class MediaFileAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return "spark\\form\\validator\\annotation\\MediaFile";
    }

    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value)
        || Objects::isNull($value)
        || $this->validateFile($annotation, $value);
    }

    public function validateFile($annotation, $media) {
        /** @var MediaFile $annotation */
        $contentTypes = $annotation->contentType;
        /** @var Media $media */

        return $this->isAnyContentType($contentTypes, $media->getMediaType())
        && FileSize::getSizeAsKB($media->getFileSize()) < $annotation->maxSize;
    }

    /**
     * @param MediaFile $annotation
     * @return array
     */
    public function getAnnotationValues($annotation) {
        return Collections::builder()
            ->add(FileSize::getSizeAsKB($annotation->maxSize))
            ->addAll($annotation->contentType)
            ->getList();
    }

    /**
     * @param $contentTypes
     * @param $contentType
     * @return bool
     */
    public function isAnyContentType($contentTypes, $contentType) {
        return Collections::builder($contentTypes)
            ->anyMatch(function ($x) use ($contentType) {
                return StringUtils::startsWith($contentType, $x);
            });
    }
}