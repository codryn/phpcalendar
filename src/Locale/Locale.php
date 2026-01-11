<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Locale;

/**
 * Supported locales for calendar internationalization
 */
final class Locale
{
    public const EN_US = 'en-us';
    public const DE = 'de';
    public const FR = 'fr';
    public const ES = 'es';
    public const IT = 'it';

    public const DEFAULT_LOCALE = self::EN_US;

    /**
     * @var array<string>
     */
    public const SUPPORTED_LOCALES = [
        self::EN_US,
        self::DE,
        self::FR,
        self::ES,
        self::IT,
    ];

    /**
     * Check if locale is supported
     *
     * @param string $locale Locale to check
     * @return bool True if supported
     */
    public static function isSupported(string $locale): bool
    {
        return \in_array($locale, self::SUPPORTED_LOCALES, true);
    }

    /**
     * Normalize locale to supported locale or default
     *
     * @param string|null $locale Locale to normalize
     * @return string Normalized locale
     */
    public static function normalize(?string $locale): string
    {
        if ($locale === null) {
            return self::DEFAULT_LOCALE;
        }

        $locale = \strtolower($locale);

        return self::isSupported($locale) ? $locale : self::DEFAULT_LOCALE;
    }
}
