<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Locale;

/**
 * Translation provider for Eberron (Galifar) calendar
 */
final class EberronTranslations
{
    /**
     * Get month names for locale
     *
     * @param string $locale Locale code
     * @return array<int, string> Month names (1-indexed)
     */
    public static function getMonthNames(string $locale): array
    {
        // Fantasy dragonmark names remain untranslated as proper nouns
        return [
            1 => 'Zarantyr',
            2 => 'Olarune',
            3 => 'Therendor',
            4 => 'Eyre',
            5 => 'Dravago',
            6 => 'Nymm',
            7 => 'Lharvion',
            8 => 'Barrakas',
            9 => 'Rhaan',
            10 => 'Sypheros',
            11 => 'Aryth',
            12 => 'Vult',
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
            Locale::EN_US => ['before' => 'Before YK', 'after' => 'YK'],
            Locale::DE => ['before' => 'Vor YK', 'after' => 'YK'],
            Locale::FR => ['before' => 'Avant YK', 'after' => 'YK'],
            Locale::ES => ['before' => 'Antes de YK', 'after' => 'YK'],
            Locale::IT => ['before' => 'Prima di YK', 'after' => 'YK'],
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
            Locale::EN_US => 'Eberron (Galifar Calendar)',
            Locale::DE => 'Eberron (Galifar-Kalender)',
            Locale::FR => 'Eberron (Calendrier Galifar)',
            Locale::ES => 'Eberron (Calendario Galifar)',
            Locale::IT => 'Eberron (Calendario Galifar)',
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
    }
}
