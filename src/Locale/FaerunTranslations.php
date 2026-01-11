<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Locale;

/**
 * Translation provider for Faerûn (Harptos) calendar
 */
final class FaerunTranslations
{
    /**
     * Get month names for locale
     *
     * @param string $locale Locale code
     * @return array<int, string> Month names (1-indexed)
     */
    public static function getMonthNames(string $locale): array
    {
        // Fantasy names remain untranslated as proper nouns
        return [
            1 => 'Hammer',
            2 => 'Alturiak',
            3 => 'Ches',
            4 => 'Tarsakh',
            5 => 'Mirtul',
            6 => 'Kythorn',
            7 => 'Flamerule',
            8 => 'Eleasis',
            9 => 'Eleint',
            10 => 'Marpenoth',
            11 => 'Uktar',
            12 => 'Nightal',
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
                    'position' => 1,
                    'names' => [1 => 'Midwinter'],
                    'leap' => false,
                ],
                [
                    'position' => 4,
                    'names' => [1 => 'Greengrass'],
                    'leap' => false,
                ],
                [
                    'position' => 7,
                    'names' => [1 => 'Midsummer'],
                    'leap' => true,
                ],
                [
                    'position' => 9,
                    'names' => [1 => 'Highharvestide'],
                    'leap' => false,
                ],
                [
                    'position' => 11,
                    'names' => [1 => 'Feast of the Moon'],
                    'leap' => false,
                ],
            ],
            Locale::DE => [
                [
                    'position' => 1,
                    'names' => [1 => 'Mittwinter'],
                    'leap' => false,
                ],
                [
                    'position' => 4,
                    'names' => [1 => 'Grüngras'],
                    'leap' => false,
                ],
                [
                    'position' => 7,
                    'names' => [1 => 'Mittsommer'],
                    'leap' => true,
                ],
                [
                    'position' => 9,
                    'names' => [1 => 'Hocherntefest'],
                    'leap' => false,
                ],
                [
                    'position' => 11,
                    'names' => [1 => 'Fest des Mondes'],
                    'leap' => false,
                ],
            ],
            Locale::FR => [
                [
                    'position' => 1,
                    'names' => [1 => 'Mi-Hiver'],
                    'leap' => false,
                ],
                [
                    'position' => 4,
                    'names' => [1 => 'Herbeverte'],
                    'leap' => false,
                ],
                [
                    'position' => 7,
                    'names' => [1 => 'Mi-Été'],
                    'leap' => true,
                ],
                [
                    'position' => 9,
                    'names' => [1 => 'Haute Moisson'],
                    'leap' => false,
                ],
                [
                    'position' => 11,
                    'names' => [1 => 'Fête de la Lune'],
                    'leap' => false,
                ],
            ],
            Locale::ES => [
                [
                    'position' => 1,
                    'names' => [1 => 'Pleno Invierno'],
                    'leap' => false,
                ],
                [
                    'position' => 4,
                    'names' => [1 => 'Hierba Verde'],
                    'leap' => false,
                ],
                [
                    'position' => 7,
                    'names' => [1 => 'Pleno Verano'],
                    'leap' => true,
                ],
                [
                    'position' => 9,
                    'names' => [1 => 'Alta Cosecha'],
                    'leap' => false,
                ],
                [
                    'position' => 11,
                    'names' => [1 => 'Fiesta de la Luna'],
                    'leap' => false,
                ],
            ],
            Locale::IT => [
                [
                    'position' => 1,
                    'names' => [1 => 'Mezzo Inverno'],
                    'leap' => false,
                ],
                [
                    'position' => 4,
                    'names' => [1 => 'Erba Verde'],
                    'leap' => false,
                ],
                [
                    'position' => 7,
                    'names' => [1 => 'Mezza Estate'],
                    'leap' => true,
                ],
                [
                    'position' => 9,
                    'names' => [1 => 'Alto Raccolto'],
                    'leap' => false,
                ],
                [
                    'position' => 11,
                    'names' => [1 => 'Festa della Luna'],
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
            Locale::EN_US => ['before' => 'Before DR', 'after' => 'DR'],
            Locale::DE => ['before' => 'Vor DR', 'after' => 'DR'],
            Locale::FR => ['before' => 'Avant DR', 'after' => 'DR'],
            Locale::ES => ['before' => 'Antes de DR', 'after' => 'DR'],
            Locale::IT => ['before' => 'Prima di DR', 'after' => 'DR'],
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
            Locale::EN_US => 'Faerûn (Harptos Calendar)',
            Locale::DE => 'Faerûn (Harptos-Kalender)',
            Locale::FR => 'Faerûn (Calendrier Harptos)',
            Locale::ES => 'Faerûn (Calendario Harptos)',
            Locale::IT => 'Faerûn (Calendario Harptos)',
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
    }
}
