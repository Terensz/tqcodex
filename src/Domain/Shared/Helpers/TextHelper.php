<?php

namespace Domain\Shared\Helpers;

class TextHelper
{
    public const FORMAT_HTML = 'HTML';

    public const FORMAT_MARKDOWN = 'Markdown';

    public static function convert(string $inputText, string $inputFormat, string $outputFormat): string
    {
        if ($inputFormat === self::FORMAT_MARKDOWN) {
            return StringHelper::convertMarkdownToHtml($inputText);
        }

        return $inputText;
    }
}
