<?php

namespace App\Utils;

class ImageProcessor
{
    protected static function isValidCodeError(int $errorCode): bool
    {
        return $errorCode === UPLOAD_ERR_OK;
    }

    private static function isSizeAllowed(int $size): bool
    {
        if ($size > ImageConstants::ALLOWED_MAX_SIZE) {
            $_SESSION["error_image"] = "Image must have max size 2MB.";
            return false;
        }
        return true;
    }

    private static function isValidTypeMime(string $typeMime): bool
    {
        if (!in_array($typeMime, ImageConstants::SUPPORTED_TYPE_MIME)) {
            $_SESSION["error_image"] = "Please file must be valid image.";
            return false;
        }
        return true;
    }

    private static function isUploadSuccessly(string $tmpName): bool
    {
        if (!is_uploaded_file($tmpName)) {
            $_SESSION["error_image"] = "This image wasn't sent via HTTP.";
            return false;
        }
        return true;
    }

    protected static function generateUniqueName(string $name, ?string $path = null): string
    {
        if (strlen($name) > 90) $name = substr($name, -90, 90);
        $src = (is_null($path)) ? "" : __DIR__ . "/../../public/img/$path/";
        return $src . uniqid() . "-" . $name;
    }

    protected static function canMove(string $from, string $to): bool
    {
        if (!move_uploaded_file($from, $to)) {
            $_SESSION["error_image"] = "Can not save image.";
            return false;
        }
        return true;
    }

    protected static function  deleteLastImage(string $lastName, string $path): bool
    {
        $lastName  = basename($lastName);
        $obsoluteSrc = __DIR__ . "/../../public/img/$path/$lastName";
        if (
            $lastName !== ImageConstants::DEFAULT_COVER_FILENAME &&
            $lastName !== ImageConstants::DEFAULT_AUTHOR_IMAGE_FILENAME &&
            file_exists($obsoluteSrc)
        ) {
            unlink($obsoluteSrc);
            return true;
        }
        return false;
    }

    protected static function isValidImage(array $image)
    {
        $tmpName = $image["tmp_name"];
        $size = $image["size"];
        $type = $image["type"];
        return self::isUploadSuccessly($tmpName) &&
            self::isValidTypeMime($type) &&
            self::isSizeAllowed($size);
    }
}
