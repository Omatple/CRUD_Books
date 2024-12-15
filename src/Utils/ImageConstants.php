<?php

namespace App\Utils;

class ImageConstants
{
    public const DEFAULT_BOOK_COVER = "defaultCover.png";
    public const DEFAULT_AUTHOR_IMAGE = "default.png";
    public const MAX_FILE_SIZE = 2 * 1024 * 1024;
    public const SUPPORTED_MIME_TYPES = [
        "image/gif",
        "image/png",
        "image/jpeg",
        "image/bmp",
        "image/webp",
    ];
}
