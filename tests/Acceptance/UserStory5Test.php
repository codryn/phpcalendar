<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Acceptance;

use Codryn\PHPCalendar\Calendar\Calendar;
use PHPUnit\Framework\TestCase;

/**
 * User Story 5: Extended Fantasy Calendar Profiles
 *
 * As a developer working on RPG applications,
 * I want pre-built calendar profiles for popular fantasy settings (Golarion, DSA, Eberron, Dragonlance, Greyhawk),
 * So that I can accurately represent time in these game worlds without implementing calendar rules myself.
 */
final class UserStory5Test extends TestCase
{
    public function testCreateGolarionCalendarWithAbsalomReckoning(): void
    {
        $calendar = Calendar::fromProfile('golarion');

        $this->assertSame('golarion', $calendar->getName());
        $this->assertSame('Golarion (Absalom Reckoning)', $calendar->getDisplayName());
        $this->assertSame(12, $calendar->getMonthCount());

        // Verify month structure: varying days per month
        $this->assertSame(31, $calendar->getDaysInMonth(1, 4720)); // Abadius
        $this->assertSame(28, $calendar->getDaysInMonth(2, 4720)); // Calistril
        $this->assertSame(31, $calendar->getDaysInMonth(3, 4720)); // Pharast

        // Leap year every 8 years
        $this->assertTrue($calendar->isLeapYear(4720)); // 4720 % 8 = 0
        $this->assertFalse($calendar->isLeapYear(4721));

        $epochNotation = $calendar->getEpochNotation();
        $this->assertSame('AR', $epochNotation['after']);
    }

    public function testCreateDSACalendarWithAventurianCalendar(): void
    {
        $calendar = Calendar::fromProfile('dsa');

        $this->assertSame('dsa', $calendar->getName());
        $this->assertSame('Das Schwarze Auge (Aventurian Calendar)', $calendar->getDisplayName());
        $this->assertSame(12, $calendar->getMonthCount());

        // All months have 30 days (+ 5 nameless days treated separately)
        $this->assertSame(30, $calendar->getDaysInMonth(1, 1000)); // Praios
        $this->assertSame(30, $calendar->getDaysInMonth(6, 1000)); // Hesinde
        $this->assertSame(30, $calendar->getDaysInMonth(12, 1000)); // Rahja

        // No leap years in standard calendar
        $this->assertFalse($calendar->isLeapYear(1000));
        $this->assertFalse($calendar->isLeapYear(1004));

        $epochNotation = $calendar->getEpochNotation();
        $this->assertSame('BF', $epochNotation['after']);
    }

    public function testCreateEberronCalendarWithGalifarCalendar(): void
    {
        $calendar = Calendar::fromProfile('eberron');

        $this->assertSame('eberron', $calendar->getName());
        $this->assertSame('Eberron (Galifar Calendar)', $calendar->getDisplayName());
        $this->assertSame(12, $calendar->getMonthCount());

        // All months have 28 days
        $this->assertSame(28, $calendar->getDaysInMonth(1, 998)); // Zarantyr
        $this->assertSame(28, $calendar->getDaysInMonth(7, 998)); // Lharvion
        $this->assertSame(28, $calendar->getDaysInMonth(12, 998)); // Vult

        // No leap years
        $this->assertFalse($calendar->isLeapYear(998));
        $this->assertFalse($calendar->isLeapYear(1000));

        $epochNotation = $calendar->getEpochNotation();
        $this->assertSame('YK', $epochNotation['after']);
    }

    public function testCreateDragonlanceCalendarWithKrynnCalendar(): void
    {
        $calendar = Calendar::fromProfile('dragonlance');

        $this->assertSame('dragonlance', $calendar->getName());
        $this->assertSame('Dragonlance (Krynn Calendar)', $calendar->getDisplayName());
        $this->assertSame(12, $calendar->getMonthCount());

        // Verify some month day counts
        $this->assertGreaterThan(0, $calendar->getDaysInMonth(1, 351));
        $this->assertLessThanOrEqual(31, $calendar->getDaysInMonth(1, 351));

        $epochNotation = $calendar->getEpochNotation();
        $this->assertSame('AC', $epochNotation['after']);
        $this->assertSame('PC', $epochNotation['before']);
    }

    public function testCreateGreyhawkCalendarWithCommonYear(): void
    {
        $calendar = Calendar::fromProfile('greyhawk');

        $this->assertSame('greyhawk', $calendar->getName());
        $this->assertSame('Greyhawk (Common Year)', $calendar->getDisplayName());

        // Greyhawk has 12 months + 4 festivals
        // We'll represent festivals as part of months for simplicity
        $this->assertGreaterThanOrEqual(12, $calendar->getMonthCount());

        $epochNotation = $calendar->getEpochNotation();
        $this->assertSame('CY', $epochNotation['after']);
    }

    public function testQueryProfileMetadataReturnsSourceInformation(): void
    {
        $calendar = Calendar::fromProfile('golarion');
        $metadata = $calendar->getProfile()->getMetadata();

        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('source', $metadata);
        $this->assertArrayHasKey('setting', $metadata);
        $this->assertArrayHasKey('description', $metadata);

        $this->assertNotEmpty($metadata['source']);
        $this->assertNotEmpty($metadata['setting']);
    }
}
