<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Locale;

/**
 * Translation provider for Dragonlance (Krynn) calendar
 */
final class DragonlanceTranslations
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
                1 => 'Winter Deep',
                2 => 'Winter Wane',
                3 => 'Spring Dawning',
                4 => 'Spring Rain',
                5 => 'Spring Bloom',
                6 => 'Summer Home',
                7 => 'Summer Run',
                8 => 'Summer End',
                9 => 'Autumn Harvest',
                10 => 'Autumn Twilight',
                11 => 'Autumn Dark',
                12 => 'Winter Come',
            ],
            Locale::DE => [
                1 => 'Tiefwinter',
                2 => 'Winterschwund',
                3 => 'Frühlingserwachen',
                4 => 'Frühlingsregen',
                5 => 'Frühlingsblüte',
                6 => 'Sommerheim',
                7 => 'Sommerlauf',
                8 => 'Sommerende',
                9 => 'Herbsternte',
                10 => 'Herbstdämmerung',
                11 => 'Herbstdunkel',
                12 => 'Winterankunft',
            ],
            Locale::FR => [
                1 => 'Hiver Profond',
                2 => 'Déclin d\'Hiver',
                3 => 'Aube du Printemps',
                4 => 'Pluie de Printemps',
                5 => 'Floraison du Printemps',
                6 => 'Foyer d\'Été',
                7 => 'Course d\'Été',
                8 => 'Fin d\'Été',
                9 => 'Moisson d\'Automne',
                10 => 'Crépuscule d\'Automne',
                11 => 'Obscurité d\'Automne',
                12 => 'Venue de l\'Hiver',
            ],
            Locale::ES => [
                1 => 'Invierno Profundo',
                2 => 'Mengua del Invierno',
                3 => 'Amanecer de Primavera',
                4 => 'Lluvia de Primavera',
                5 => 'Floración de Primavera',
                6 => 'Hogar de Verano',
                7 => 'Curso de Verano',
                8 => 'Fin de Verano',
                9 => 'Cosecha de Otoño',
                10 => 'Crepúsculo de Otoño',
                11 => 'Oscuridad de Otoño',
                12 => 'Llegada del Invierno',
            ],
            Locale::IT => [
                1 => 'Inverno Profondo',
                2 => 'Declino dell\'Inverno',
                3 => 'Alba di Primavera',
                4 => 'Pioggia di Primavera',
                5 => 'Fioritura di Primavera',
                6 => 'Casa d\'Estate',
                7 => 'Corsa d\'Estate',
                8 => 'Fine d\'Estate',
                9 => 'Raccolto d\'Autunno',
                10 => 'Crepuscolo d\'Autunno',
                11 => 'Oscurità d\'Autunno',
                12 => 'Arrivo dell\'Inverno',
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
            Locale::EN_US => ['before' => 'PC', 'after' => 'AC'],
            Locale::DE => ['before' => 'VK', 'after' => 'NK'],
            Locale::FR => ['before' => 'AC', 'after' => 'PC'],
            Locale::ES => ['before' => 'AC', 'after' => 'DC'],
            Locale::IT => ['before' => 'PC', 'after' => 'DC'],
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
            Locale::EN_US => 'Dragonlance (Krynn Calendar)',
            Locale::DE => 'Dragonlance (Krynn-Kalender)',
            Locale::FR => 'Dragonlance (Calendrier de Krynn)',
            Locale::ES => 'Dragonlance (Calendario de Krynn)',
            Locale::IT => 'Dragonlance (Calendario di Krynn)',
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
    }
}
