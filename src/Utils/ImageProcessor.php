<?php

namespace App\Utils;

class ImageProcessor
{
    protected static function isValidErrorCode(int $errorCode): bool
    {
        return $errorCode === UPLOAD_ERR_OK;
    }

    private static function isSizeAllowed(int $size): bool
    {
        if ($size > ImageConstants::MAX_FILE_SIZE) {
            $_SESSION["error_image"] = "The image size must not exceed 2MB.";
            return false;
        }
        return true;
    }

    private static function isValidMimeType(string $mimeType): bool
    {
        if (!in_array($mimeType, ImageConstants::SUPPORTED_MIME_TYPES, true)) {
            $_SESSION["error_image"] = "The file must be a valid image format.";
            return false;
        }
        return true;
    }

    private static function isUploadedSuccessfully(string $tmpName): bool
    {
        if (!is_uploaded_file($tmpName)) {
            $_SESSION["error_image"] = "The image was not uploaded via HTTP.";
            return false;
        }
        return true;
    }

    protected static function generateUniqueFileName(string $name, ?string $path = null): string
    {
        if (strlen($name) > 90) {
            $name = substr($name, -90);
        }
        $basePath = $path ? __DIR__ . "/../../public/$path/img/" : "";
        return $basePath . uniqid() . "-" . $name;
    }

    protected static function moveFile(string $from, string $to): bool
    {
        if (!move_uploaded_file($from, $to)) {
            $_SESSION["error_image"] = "Unable to save the image.";
            return false;
        }
        return true;
    }

    protected static function deletePreviousImage(string $imageName, string $path): bool
    {
        $imageName = basename($imageName);
        $absolutePath = __DIR__ . "/../../public/$path/img/$imageName";

        if (
            $imageName !== ImageConstants::DEFAULT_BOOK_COVER &&
            $imageName !== ImageConstants::DEFAULT_AUTHOR_IMAGE &&
            file_exists($absolutePath)
        ) {
            unlink($absolutePath);
            return true;
        }
        return false;
    }

    protected static function isValidImage(array $image): bool
    {
        $tmpName = $image["tmp_name"] ?? "";
        $size = $image["size"] ?? 0;
        $mimeType = $image["type"] ?? "";

        return self::isUploadedSuccessfully($tmpName) &&
            self::isValidMimeType($mimeType) &&
            self::isSizeAllowed($size);
    }
}
