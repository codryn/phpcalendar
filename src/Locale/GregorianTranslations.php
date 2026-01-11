<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Locale;

/**
 * Translation provider for Gregorian calendar
 */
final class GregorianTranslations
{
    /**
     * Get month names for locale
     *
     * @param string $locale Locale code
     * @return array<int, string> Month names (1-indexed)
     */
    public static function getMonthNames(string $locale): array
    {
        $translations = [
            Locale::EN_US => [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
            ],
            Locale::DE => [
                1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April',
                5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'August',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember',
            ],
            Locale::FR => [
                1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',
            ],
            Locale::ES => [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
            ],
            Locale::IT => [
                1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo', 4 => 'Aprile',
                5 => 'Maggio', 6 => 'Giugno', 7 => 'Luglio', 8 => 'Agosto',
                9 => 'Settembre', 10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre',
            ],
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
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
            Locale::EN_US => ['before' => 'BCE', 'after' => 'CE'],
            Locale::DE => ['before' => 'v. Chr.', 'after' => 'n. Chr.'],
            Locale::FR => ['before' => 'av. J.-C.', 'after' => 'ap. J.-C.'],
            Locale::ES => ['before' => 'a.C.', 'after' => 'd.C.'],
            Locale::IT => ['before' => 'a.C.', 'after' => 'd.C.'],
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
            Locale::EN_US => 'Gregorian Calendar',
            Locale::DE => 'Gregorianischer Kalender',
            Locale::FR => 'Calendrier Grégorien',
            Locale::ES => 'Calendario Gregoriano',
            Locale::IT => 'Calendario Gregoriano',
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
    }
}
