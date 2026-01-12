<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Profile;

use Codryn\PHPCalendar\Profile\GregorianProfile;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for internationalization in GregorianProfile
 */
class GregorianProfileInternationalizationTest extends TestCase
{
    private GregorianProfile $profile;

    protected function setUp(): void
    {
        $this->profile = new GregorianProfile();
    }

    public function testDefaultLocaleIsEnglish(): void
    {
        $this->assertSame('en-us', $this->profile->getLocale());
        $this->assertSame('Gregorian Calendar', $this->profile->getDisplayName());
        $this->assertSame('January', $this->profile->getMonthNames()[1]);
        $this->assertSame('CE', $this->profile->getEpochNotation()['after']);
    }

    public function testGermanLocale(): void
    {
        $this->profile->setLocale('de');
        $this->assertSame('de', $this->profile->getLocale());
        $this->assertSame('Gregorianischer Kalender', $this->profile->getDisplayName());
        $this->assertSame('Januar', $this->profile->getMonthNames()[1]);
        $this->assertSame('Dezember', $this->profile->getMonthNames()[12]);
        $this->assertSame('n. Chr.', $this->profile->getEpochNotation()['after']);
        $this->assertSame('v. Chr.', $this->profile->getEpochNotation()['before']);
    }

    public function testFrenchLocale(): void
    {
        $this->profile->setLocale('fr');
        $this->assertSame('fr', $this->profile->getLocale());
        $this->assertSame('Calendrier Grégorien', $this->profile->getDisplayName());
        $this->assertSame('Janvier', $this->profile->getMonthNames()[1]);
        $this->assertSame('Décembre', $this->profile->getMonthNames()[12]);
        $this->assertSame('ap. J.-C.', $this->profile->getEpochNotation()['after']);
    }

    public function testSpanishLocale(): void
    {
        $this->profile->setLocale('es');
        $this->assertSame('es', $this->profile->getLocale());
        $this->assertSame('Calendario Gregoriano', $this->profile->getDisplayName());
        $this->assertSame('Enero', $this->profile->getMonthNames()[1]);
        $this->assertSame('Diciembre', $this->profile->getMonthNames()[12]);
        $this->assertSame('d.C.', $this->profile->getEpochNotation()['after']);
    }

    public function testItalianLocale(): void
    {
        $this->profile->setLocale('it');
        $this->assertSame('it', $this->profile->getLocale());
        $this->assertSame('Calendario Gregoriano', $this->profile->getDisplayName());
        $this->assertSame('Gennaio', $this->profile->getMonthNames()[1]);
        $this->assertSame('Dicembre', $this->profile->getMonthNames()[12]);
        $this->assertSame('d.C.', $this->profile->getEpochNotation()['after']);
    }

    public function testUnsupportedLocaleDefaultsToEnglish(): void
    {
        $this->profile->setLocale('unsupported');
        $this->assertSame('en-us', $this->profile->getLocale());
        $this->assertSame('Gregorian Calendar', $this->profile->getDisplayName());
        $this->assertSame('January', $this->profile->getMonthNames()[1]);
    }

    public function testCaseInsensitiveLocale(): void
    {
        $this->profile->setLocale('DE');
        $this->assertSame('de', $this->profile->getLocale());
        $this->assertSame('Gregorianischer Kalender', $this->profile->getDisplayName());
    }

    public function testLocaleChange(): void
    {
        // Start with German
        $this->profile->setLocale('de');
        $this->assertSame('Januar', $this->profile->getMonthNames()[1]);

        // Change to French
        $this->profile->setLocale('fr');
        $this->assertSame('Janvier', $this->profile->getMonthNames()[1]);

        // Change to English
        $this->profile->setLocale('en-us');
        $this->assertSame('January', $this->profile->getMonthNames()[1]);
    }
}
