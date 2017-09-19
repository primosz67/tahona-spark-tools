<?php
/**
 *
 *
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;

use Spark\Form\Validator\Annotation\MediaFile;
use Spark\Upload\FileSize;
use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;
use tahona\media\domain\Media;

class MediaFileAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return MediaFile::class;
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