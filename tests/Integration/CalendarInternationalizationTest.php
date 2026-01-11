<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Integration;

use Codryn\PHPCalendar\Calendar\Calendar;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for Calendar internationalization
 */
class CalendarInternationalizationTest extends TestCase
{
    public function testCalendarFromProfileWithLocale(): void
    {
        $calendar = Calendar::fromProfile('gregorian', 'de');
        $this->assertSame('Gregorianischer Kalender', $calendar->getDisplayName());
    }

    public function testCalendarFromProfileWithoutLocale(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $this->assertSame('Gregorian Calendar', $calendar->getDisplayName());
    }

    public function testCalendarWithUnsupportedLocale(): void
    {
        $calendar = Calendar::fromProfile('gregorian', 'unsupported');
        $this->assertSame('Gregorian Calendar', $calendar->getDisplayName());
    }

    public function testAllProfilesWithDifferentLocales(): void
    {
        $profiles = ['gregorian', 'faerun', 'dsa', 'dragonlance', 'eberron', 'golarion', 'greyhawk'];
        $locales = ['en-us', 'de', 'fr', 'es', 'it'];

        foreach ($profiles as $profileName) {
            foreach ($locales as $locale) {
                $calendar = Calendar::fromProfile($profileName, $locale);
                $displayName = $calendar->getDisplayName();

                $this->assertNotEmpty($displayName, "Profile {$profileName} with locale {$locale} should have display name");
                $this->assertIsString($displayName, "Profile {$profileName} with locale {$locale} display name should be string");
            }
        }
    }

    public function testGregorianCalendarLocaleSpecificTranslations(): void
    {
        // Test German
        $calendar = Calendar::fromProfile('gregorian', 'de');
        $this->assertSame('Gregorianischer Kalender', $calendar->getDisplayName());

        // Test French
        $calendar = Calendar::fromProfile('gregorian', 'fr');
        $this->assertSame('Calendrier Grégorien', $calendar->getDisplayName());

        // Test Spanish
        $calendar = Calendar::fromProfile('gregorian', 'es');
        $this->assertSame('Calendario Gregoriano', $calendar->getDisplayName());

        // Test Italian
        $calendar = Calendar::fromProfile('gregorian', 'it');
        $this->assertSame('Calendario Gregoriano', $calendar->getDisplayName());
    }

    public function testFaerunCalendarLocaleSpecificTranslations(): void
    {
        // Test German
        $calendar = Calendar::fromProfile('faerun', 'de');
        $this->assertSame('Faerûn (Harptos-Kalender)', $calendar->getDisplayName());

        // Test French
        $calendar = Calendar::fromProfile('faerun', 'fr');
        $this->assertSame('Faerûn (Calendrier Harptos)', $calendar->getDisplayName());

        // Test Spanish
        $calendar = Calendar::fromProfile('faerun', 'es');
        $this->assertSame('Faerûn (Calendario Harptos)', $calendar->getDisplayName());

        // Test Italian
        $calendar = Calendar::fromProfile('faerun', 'it');
        $this->assertSame('Faerûn (Calendario Harptos)', $calendar->getDisplayName());
    }
}
