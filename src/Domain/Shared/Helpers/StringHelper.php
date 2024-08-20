<?php

namespace Domain\Shared\Helpers;

use Exception;
use Illuminate\Support\Str;

class StringHelper
{
    public const FIRST_OFFSET = 'first';

    public const LAST_OFFSET = 'last';

    public const BEFORE_LAST_OFFSET = 'last-1';

    /**
     * Shortens a string by keeping the first two and last two characters,
     * replacing the middle with '...'.
     *
     * @param  string  $string  The input string to shorten.
     * @param  int  $shortenFromLength  The length at which the string should be shortened. Default is 5.
     * @return string The shortened string.
     */
    public static function shorten($string, $shortenFromLength = 10)
    {
        // If the string is already short enough, return it as is
        if (strlen($string) <= $shortenFromLength) {
            return $string;
        }

        // Get the first two and last two characters of the string
        $start = substr($string, 0, 2);
        $end = substr($string, -2);

        // Combine them with '...' in the middle
        return $start.'...'.$end;
    }

    /**
     * Replace placeholders in a string with corresponding values from an array.
     *
     * @param  string  $text  The text containing placeholders.
     * @param  array  $keyValueArray  An associative array of keys and values to replace in the text.
     * @param  string  $leftWrapper  The left wrapper of the placeholder.
     * @param  string  $rightWrapper  The right wrapper of the placeholder.
     * @return string The text with replaced placeholders.
     */
    public static function replacePlaceholders($text, array $keyValueArray, $leftWrapper = '[[', $rightWrapper = ']]', $shortenFromLength = null): string
    {
        foreach ($keyValueArray as $key => $value) {
            $placeholder = $leftWrapper.$key.$rightWrapper;

            if ($shortenFromLength && strlen(strip_tags($value)) >= $shortenFromLength) {
                $value = self::shorten(strip_tags($value), $shortenFromLength);
            }

            $text = str_replace($placeholder, $value, $text);
        }

        return $text;
    }

    /**
     * @todo: a HTML-editor nem tudja kezelni a p-ket, pedig a Str::markdown() azokat rak az új sorok esetén.
     * Így viszont nem annyira szép, hogy str_replacelem. István tud rá megoldást.
     */
    public static function convertMarkdownToHtml($markdown)
    {
        $result = Str::markdown($markdown);
        $result = str_replace('<p>', '<div>', $result);
        $result = str_replace('</p>', '</div>', $result);

        return $result;
    }

    /**
     * Return a localized string based on the boolean value.
     */
    public static function getLocalizedBoolean(bool $value): string
    {
        return $value ? __('shared.True') : __('shared.False');
    }

    public static function wrapString(string $string, string $wrapper): string
    {
        return $wrapper.$string.$wrapper;
    }

    public static function cutLongString(?string $string, int $maxCharNum = 50, string $end = '...'): string|null|bool
    {
        if (! $string) {
            return $string;
        }
        $stringWithoutTags = strip_tags($string);

        return (mb_strlen($stringWithoutTags) > $maxCharNum) ? mb_substr($stringWithoutTags, 0, $maxCharNum).$end : $stringWithoutTags;
    }

    /**
     * @return array{result: bool, base: string|null, index: int|string|null}
     */
    public static function extractNumberFromEnd(string $string): array
    {
        // Searcing numbers on the end
        preg_match('/^(?P<base>.+?)(?P<index>\d+)$/', $string, $matches);

        // If numbers found, we just return, also with the base and the index parts.
        if (! empty($matches['index'])) {
            return [
                'result' => true,
                'base' => $matches['base'],
                'index' => $matches['index'],
            ];
        }

        // If no numbers on the end, we just return with a false result.
        return [
            'result' => false,
            'base' => null,
            'index' => null,
        ];
    }

    /**
     * @param  non-empty-string  $separator
     */
    public static function explodeAndRemoveElement(string $string, string $separator, int|string $offsetToRemove): string
    {
        $offset = $offsetToRemove;

        $pos = strpos($string, $separator);
        if ($pos === false) {
            return $string;
        }

        $temp = explode($separator, $string);
        if ($offset == 'first') {
            $offsetToRemove = 0;
        }

        if ($offset == 'last') {
            $offsetToRemove = count($temp) - 1;
        }

        if ($offset == 'last-1') {
            if (count($temp) > 1) {
                $offsetToRemove = count($temp) - 2;
            } else {
                throw new Exception('Too low offset to remove element before last.');
            }
        }

        $new = [];
        for ($i = 0; $i < count($temp); $i++) {
            if ($offsetToRemove != $i) {
                $new[] = $temp[$i];
            }
        }

        return implode($separator, $new);
    }

    public static function explodeAndGetElement(string $string, string $separator, string|int $offset): ?string
    {
        if (! $string || ! $separator) {
            return null;
        }

        $temp = explode($separator, $string);
        if ($offset == self::FIRST_OFFSET) {
            return $temp[0];
        }

        if ($offset == self::LAST_OFFSET) {
            return $temp[(count($temp) - 1)];
        }

        if ($offset == self::BEFORE_LAST_OFFSET) {
            if (count($temp) > 1) {
                return $temp[(count($temp) - 2)];
            }

            throw new \Exception('Too low offset to get element before last.');
        }

        for ($i = 0; $i < count($temp); $i++) {
            if ((int) $offset == $i) {
                return (string) $temp[$i];
            }
        }

        return null;
    }

    public static function generateRandomString($length = 30, $useUppercase = true, $recreateIfRandomStringEquals = null): string
    {
        // Defining participating characters
        $characters = $useUppercase ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : '0123456789abcdefghijklmnopqrstuvwxyz';

        // Initializing result.
        $randomString = '';

        // Adding random characzerst to the string, until the lenght is the given.
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        if ($recreateIfRandomStringEquals && $recreateIfRandomStringEquals == $randomString) {
            return self::generateRandomString($length, $useUppercase, $recreateIfRandomStringEquals);
        }

        return $randomString;
    }

    public static function replaceFromPosition(string|int $fullText, string|int $replaceWith, int $pos, int $replaceLength): string
    {
        $firstPart = mb_substr($fullText, 0, $pos);
        $lastPart = mb_substr($fullText, ($pos + $replaceLength));

        return $firstPart.$replaceWith.$lastPart;
    }

    public static function alterToString(string|int|float|bool|null|object|array $input): string
    {
        if (is_null($input)) {
            return 'null';
        }

        if (is_bool($input)) {
            return $input ? 'true' : 'false';
        }

        if (is_object($input)) {
            return get_class($input);
        }

        if (is_float($input)) {
            return strval($input);
        }

        if (is_array($input)) {
            return 'Array';
        }

        return strval($input);
    }
}
