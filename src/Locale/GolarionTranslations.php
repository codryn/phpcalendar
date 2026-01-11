<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Locale;

/**
 * Translation provider for Golarion (Absalom Reckoning) calendar
 */
final class GolarionTranslations
{
    /**
     * Get month names for locale
     *
     * @param string $locale Locale code
     * @return array<int, string> Month names (1-indexed)
     */
    public static function getMonthNames(string $locale): array
    {
        // Fantasy deity names remain untranslated as proper nouns
        return [
            1 => 'Abadius',
            2 => 'Calistril',
            3 => 'Pharast',
            4 => 'Gozran',
            5 => 'Desnus',
            6 => 'Sarenith',
            7 => 'Erastus',
            8 => 'Arodus',
            9 => 'Rova',
            10 => 'Lamashan',
            11 => 'Neth',
            12 => 'Kuthona',
        ];
    }

    /**
     * Get epoch notation for locale
     *
     * @param string $locale Locale code
     * @return array{before: string, after: string} Epoch notation
     */
    public static function getEpochNotation(string $locale): array
    {
        $translations = [
            Locale::EN_US => ['before' => 'Before AR', 'after' => 'AR'],
            Locale::DE => ['before' => 'Vor AR', 'after' => 'AR'],
            Locale::FR => ['before' => 'Avant AR', 'after' => 'AR'],
            Locale::ES => ['before' => 'Antes de AR', 'after' => 'AR'],
            Locale::IT => ['before' => 'Prima di AR', 'after' => 'AR'],
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
    }

    /**
     * Get display name for locale
     *
     * @param string $locale Locale code
     * @return string Display name
     */
    public static function getDisplayName(string $locale): string
    {
        $translations = [
            Locale::EN_US => 'Golarion (Absalom Reckoning)',
            Locale::DE => 'Golarion (Absalom-Zeitrechnung)',
            Locale::FR => 'Golarion (Comput d\'Absalom)',
            Locale::ES => 'Golarion (Cómputo de Absalom)',
            Locale::IT => 'Golarion (Computo di Absalom)',
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
    }
}
