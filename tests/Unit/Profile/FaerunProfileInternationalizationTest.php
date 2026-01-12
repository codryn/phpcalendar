<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Profile;

use Codryn\PHPCalendar\Profile\FaerunProfile;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for internationalization in FaerunProfile
 */
class FaerunProfileInternationalizationTest extends TestCase
{
    private FaerunProfile $profile;

    protected function setUp(): void
    {
        $this->profile = new FaerunProfile();
    }

    public function testDefaultLocaleIsEnglish(): void
    {
        $this->assertSame('en-us', $this->profile->getLocale());
        $this->assertSame('Faerûn (Harptos Calendar)', $this->profile->getDisplayName());
    }

    public function testGermanLocale(): void
    {
        $this->profile->setLocale('de');
        $this->assertSame('de', $this->profile->getLocale());
        $this->assertSame('Faerûn (Harptos-Kalender)', $this->profile->getDisplayName());
        $this->assertSame('DR', $this->profile->getEpochNotation()['after']);
        $this->assertSame('Vor DR', $this->profile->getEpochNotation()['before']);
    }

    public function testMonthNamesRemainUnchanged(): void
    {
        // Month names are proper nouns and should not be translated
        $this->profile->setLocale('de');
        $this->assertSame('Hammer', $this->profile->getMonthNames()[1]);
        $this->assertSame('Nightal', $this->profile->getMonthNames()[12]);

        $this->profile->setLocale('fr');
        $this->assertSame('Hammer', $this->profile->getMonthNames()[1]);
        $this->assertSame('Nightal', $this->profile->getMonthNames()[12]);
    }

    public function testNamelessDaysTranslation(): void
    {
        // English
        $this->profile->setLocale('en-us');
        $nameless = $this->profile->getNamelessDays();
        $this->assertSame('Midwinter', $nameless[0]['names'][1]);
        $this->assertSame('Greengrass', $nameless[1]['names'][1]);
        $this->assertSame('Midsummer', $nameless[2]['names'][1]);

        // German
        $this->profile->setLocale('de');
        $nameless = $this->profile->getNamelessDays();
        $this->assertSame('Mittwinter', $nameless[0]['names'][1]);
        $this->assertSame('Grüngras', $nameless[1]['names'][1]);
        $this->assertSame('Mittsommer', $nameless[2]['names'][1]);

        // French
        $this->profile->setLocale('fr');
        $nameless = $this->profile->getNamelessDays();
        $this->assertSame('Mi-Hiver', $nameless[0]['names'][1]);
        $this->assertSame('Herbeverte', $nameless[1]['names'][1]);
        $this->assertSame('Mi-Été', $nameless[2]['names'][1]);

        // Spanish
        $this->profile->setLocale('es');
        $nameless = $this->profile->getNamelessDays();
        $this->assertSame('Pleno Invierno', $nameless[0]['names'][1]);
        $this->assertSame('Hierba Verde', $nameless[1]['names'][1]);

        // Italian
        $this->profile->setLocale('it');
        $nameless = $this->profile->getNamelessDays();
        $this->assertSame('Mezzo Inverno', $nameless[0]['names'][1]);
        $this->assertSame('Erba Verde', $nameless[1]['names'][1]);
    }
}
