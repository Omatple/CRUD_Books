<?php

namespace App\utils;

class ImageConstants
{
    public const DEFAULT_COVER_FILENAME = "defaultCover.png";
    public const ALLOWED_MAX_SIZE = 2 * 1024 * 1024;
    public const SUPPORTED_TYPE_MIME = ["image/gif", "image/png", "image/jpeg", "image/bmp", "image/webp"];
}
