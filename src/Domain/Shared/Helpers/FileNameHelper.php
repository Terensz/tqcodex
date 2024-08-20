<?php

namespace Domain\Shared\Helpers;

class FileNameHelper
{
    public const EXTENSION_JPG = 'jpg';

    public const EXTENSION_JPEG = 'jpeg';

    public const EXTENSION_GIF = 'gif';

    public const EXTENSION_PNG = 'png';

    public const EXTENSION_PDF = 'pdf';

    public const EXTENSION_PAGES = 'pages';

    public const EXTENSION_NUMBERS = 'numbers';

    public const EXTENSION_KEY = 'key';

    public const EXTENSION_XLS = 'xls';

    public const EXTENSION_XLSX = 'xlsx';

    public const EXTENSION_DOC = 'doc';

    public const EXTENSION_DOCX = 'docx';

    public const EXTENSION_PPS = 'pps';

    public const ACCEPTED_IMAGE_EXTENSIONS = [
        self::EXTENSION_JPG,
        self::EXTENSION_JPEG,
        self::EXTENSION_GIF,
        self::EXTENSION_PNG,
    ];

    public const ACCEPTED_DOCUMENT_EXTENSIONS = [
        self::EXTENSION_PDF,
        self::EXTENSION_PAGES,
        self::EXTENSION_NUMBERS,
        self::EXTENSION_KEY,
        self::EXTENSION_XLS,
        self::EXTENSION_XLSX,
        self::EXTENSION_DOC,
        self::EXTENSION_DOCX,
        self::EXTENSION_PPS,
    ];

    public const ALTER_EXTENSIONS = [
        self::EXTENSION_JPEG => self::EXTENSION_JPG,
    ];

    /**
     * @return array<mixed>
     */
    public static function getAcceptedExtensions(): array
    {
        return array_merge(self::ACCEPTED_IMAGE_EXTENSIONS, self::ACCEPTED_DOCUMENT_EXTENSIONS);
    }

    public static function extractExtension(string $fullFileName): string
    {
        $extension = pathinfo($fullFileName, PATHINFO_EXTENSION);

        return $extension;
    }

    public static function validateExtension(string $extension, bool $alterExtension = true): string
    {
        $extension = strtolower($extension);

        if (! in_array($extension, self::ACCEPTED_DOCUMENT_EXTENSIONS)) {
            throw new \Exception('Invalid extension: '.$extension);
        }

        if ($alterExtension) {
            foreach (self::ALTER_EXTENSIONS as $alterExtensionFrom => $alterExtensionTo) {
                if ($extension === $alterExtensionFrom) {
                    $extension = $alterExtensionTo;
                }
            }
        }

        return $extension;
    }

    public static function extractAndValidateExtension(string $fullFileName, bool $alterExtension = true): string
    {
        $extension = self::extractExtension($fullFileName);

        return $extension;
    }
}
