<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Locale;

/**
 * Translation provider for Greyhawk (Common Year) calendar
 */
final class GreyhawkTranslations
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
                1 => 'Needfest',
                2 => 'Fireseek',
                3 => 'Readying',
                4 => 'Coldeven',
                5 => 'Growfest',
                6 => 'Planting',
                7 => 'Flocktime',
                8 => 'Wealsun',
                9 => 'Richfest',
                10 => 'Reaping',
                11 => 'Goodmonth',
                12 => 'Harvester',
                13 => 'Brewfest',
                14 => 'Patchwall',
                15 => 'Ready\'reat',
                16 => 'Sunsebb',
            ],
            Locale::DE => [
                1 => 'Notfest',
                2 => 'Feuersuche',
                3 => 'Bereitung',
                4 => 'Kalteben',
                5 => 'Wachstumsfest',
                6 => 'Pflanzung',
                7 => 'Herdenzeit',
                8 => 'Wohlsonne',
                9 => 'Reichtumsfest',
                10 => 'Ernte',
                11 => 'Gutermond',
                12 => 'Ernter',
                13 => 'Braufest',
                14 => 'Flickmauer',
                15 => 'Bereitschaft',
                16 => 'Sonnenebb',
            ],
            Locale::FR => [
                1 => 'Fête du Besoin',
                2 => 'Quête du Feu',
                3 => 'Préparation',
                4 => 'Froid Égal',
                5 => 'Fête de Croissance',
                6 => 'Plantation',
                7 => 'Temps du Troupeau',
                8 => 'Beau Soleil',
                9 => 'Fête Riche',
                10 => 'Moisson',
                11 => 'Bon Mois',
                12 => 'Moissonneur',
                13 => 'Fête de Brassage',
                14 => 'Mur de Pièces',
                15 => 'Prêt à Tout',
                16 => 'Déclin du Soleil',
            ],
            Locale::ES => [
                1 => 'Fiesta de la Necesidad',
                2 => 'Búsqueda del Fuego',
                3 => 'Preparación',
                4 => 'Frío Parejo',
                5 => 'Fiesta del Crecimiento',
                6 => 'Plantación',
                7 => 'Tiempo de Rebaño',
                8 => 'Sol de Riqueza',
                9 => 'Fiesta Rica',
                10 => 'Siega',
                11 => 'Buen Mes',
                12 => 'Cosechador',
                13 => 'Fiesta de Cerveza',
                14 => 'Muro de Parches',
                15 => 'Listo para Todo',
                16 => 'Ocaso del Sol',
            ],
            Locale::IT => [
                1 => 'Festa del Bisogno',
                2 => 'Ricerca del Fuoco',
                3 => 'Preparazione',
                4 => 'Freddo Uniforme',
                5 => 'Festa della Crescita',
                6 => 'Piantagione',
                7 => 'Tempo del Gregge',
                8 => 'Sole di Ricchezza',
                9 => 'Festa Ricca',
                10 => 'Mietitura',
                11 => 'Buon Mese',
                12 => 'Mietitore',
                13 => 'Festa della Birra',
                14 => 'Muro di Toppe',
                15 => 'Pronto per Tutto',
                16 => 'Tramonto del Sole',
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
            Locale::EN_US => ['before' => 'Before CY', 'after' => 'CY'],
            Locale::DE => ['before' => 'Vor GJ', 'after' => 'GJ'],
            Locale::FR => ['before' => 'Avant AC', 'after' => 'AC'],
            Locale::ES => ['before' => 'Antes de AC', 'after' => 'AC'],
            Locale::IT => ['before' => 'Prima di AC', 'after' => 'AC'],
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
            Locale::EN_US => 'Greyhawk (Common Year)',
            Locale::DE => 'Greyhawk (Gemeinjahr)',
            Locale::FR => 'Greyhawk (Année Commune)',
            Locale::ES => 'Greyhawk (Año Común)',
            Locale::IT => 'Greyhawk (Anno Comune)',
        ];

        return $translations[$locale] ?? $translations[Locale::DEFAULT_LOCALE];
    }
}
