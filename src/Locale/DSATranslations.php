<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Locale;

/**
 * Translation provider for Das Schwarze Auge (Aventurian) calendar
 */
final class DSATranslations
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
            1 => 'Praios',
            2 => 'Rondra',
            3 => 'Efferd',
            4 => 'Travia',
            5 => 'Boron',
            6 => 'Hesinde',
            7 => 'Firun',
            8 => 'Tsa',
            9 => 'Phex',
            10 => 'Peraine',
            11 => 'Ingerimm',
            12 => 'Rahja',
        ];
    }

    /**
     * Get nameless day names for locale
     *
     * @param string $locale Locale code
     * @return array<int, array{position: int, names: array<int, string>, leap: bool}>
     */
    public static function getNamelessDays(string $locale): array
    {
        $translations = [
            Locale::EN_US => [
                [
                    'position' => 12,
                    'names' => [
                        1 => 'First Nameless Day',
                        2 => 'Second Nameless Day',
                        3 => 'Third Nameless Day',
                        4 => 'Fourth Nameless Day',
                        5 => 'Fifth Nameless Day',
                    ],
                    'leap' => false,
                ],
            ],
            Locale::DE => [
                [
                    'position' => 12,
                    'names' => [
                        1 => 'Erster Namenloser Tag',
                        2 => 'Zweiter Namenloser Tag',
                        3 => 'Dritter Namenloser Tag',
                        4 => 'Vierter Namenloser Tag',
                        5 => 'Fünfter Namenloser Tag',
                    ],
                    'leap' => false,
                ],
            ],
            Locale::FR => [
                [
                    'position' => 12,
                    'names' => [
                        1 => 'Premier Jour Sans Nom',
                        2 => 'Deuxième Jour Sans Nom',
                        3 => 'Troisième Jour Sans Nom',
                        4 => 'Quatrième Jour Sans Nom',
                        5 => 'Cinquième Jour Sans Nom',
                    ],
                    'leap' => false,
                ],
            ],
            Locale::ES => [
                [
                    'position' => 12,
                    'names' => [
                        1 => 'Primer Día Sin Nombre',
                        2 => 'Segundo Día Sin Nombre',
                        3 => 'Tercer Día Sin Nombre',
                        4 => 'Cuarto Día Sin Nombre',
                        5 => 'Quinto Día Sin Nombre',
                    ],
                    'leap' => false,
                ],
            ],
            Locale::IT => [
                [
                    'position' => 12,
                    'names' => [
                        1 => 'Primo Giorno Senza Nome',
                        2 => 'Secondo Giorno Senza Nome',
                        3 => 'Terzo Giorno Senza Nome',
                        4 => 'Quarto Giorno Senza Nome',
                        5 => 'Quinto Giorno Senza Nome',
                    ],
                    'leap' => false,
                ],
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
            Locale::EN_US => ['before' => 'Before BF', 'after' => 'BF'],
            Locale::DE => ['before' => 'Vor BF', 'after' => 'BF'],
            Locale::FR => ['before' => 'Avant BF', 'after' => 'BF'],
            Locale::ES => ['before' => 'Antes de BF', 'after' => 'BF'],
            Locale::IT => ['before' => 'Prima di BF', 'after' => 'BF'],
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
            Locale::EN_US => 'Das Schwarze Auge (Aventurian Calendar)',
            Locale::DE => 'Das Schwarze Auge (Aventurischer Kalender)',
            Locale::FR => 'Das Schwarze Auge (Calendrier Aventurien)',
            Locale::ES => 'Das Schwarze Auge (Calendario Aventuriano)',
            Locale::IT => 'Das Schwarze Auge (Calendario Aventuriano)',
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
    }
}
