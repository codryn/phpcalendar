<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Acceptance;

use Codryn\PHPCalendar\Calendar\Calendar;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Acceptance tests for User Story 1: Create Calendar with Pre-built Profile
 *
 * Goal: Enable developers to instantiate calendars using pre-built profiles
 * (Gregorian, Faerûn) with immediate usability
 */
class UserStory1Test extends TestCase
{
    /**
     * Test: Create Gregorian calendar and verify properties
     */
    public function testCreateGregorianCalendarWithCorrectProperties(): void
    {
        $calendar = Calendar::fromProfile('gregorian');

        $this->assertSame('gregorian', $calendar->getName());
        $this->assertSame('Gregorian Calendar', $calendar->getDisplayName());

        // Verify 12 months
        $this->assertSame(12, $calendar->getMonthCount());

        $monthNames = $calendar->getMonthNames();
        $this->assertCount(12, $monthNames);
        $this->assertSame('January', $monthNames[1]);
        $this->assertSame('December', $monthNames[12]);

        // Verify leap year logic
        $this->assertTrue($calendar->isLeapYear(2024)); // Divisible by 4
        $this->assertFalse($calendar->isLeapYear(2100)); // Divisible by 100 but not 400
        $this->assertTrue($calendar->isLeapYear(2000)); // Divisible by 400
        $this->assertFalse($calendar->isLeapYear(2023)); // Not divisible by 4

        // Verify days in month
        $this->assertSame(31, $calendar->getDaysInMonth(1, 2024)); // January
        $this->assertSame(29, $calendar->getDaysInMonth(2, 2024)); // February (leap year)
        $this->assertSame(28, $calendar->getDaysInMonth(2, 2023)); // February (non-leap)

        // Verify epoch notation
        $epoch = $calendar->getEpochNotation();
        $this->assertSame('BCE', $epoch['before']);
        $this->assertSame('CE', $epoch['after']);
    }

    /**
     * Test: Create Faerûn calendar and verify Harptos calendar properties
     */
    public function testCreateFaerunCalendarWithHarptosProperties(): void
    {
        $calendar = Calendar::fromProfile('faerun');

        $this->assertSame('faerun', $calendar->getName());
        $this->assertSame('Faerûn (Harptos Calendar)', $calendar->getDisplayName());

        // Harptos has 12 months
        $this->assertSame(12, $calendar->getMonthCount());

        $monthNames = $calendar->getMonthNames();
        $this->assertSame('Hammer', $monthNames[1]);
        $this->assertSame('Alturiak', $monthNames[2]);
        $this->assertSame('Nightal', $monthNames[12]);

        // Each month has 30 days in Harptos
        for ($month = 1; $month <= 12; $month++) {
            $this->assertSame(30, $calendar->getDaysInMonth($month, 1492));
        }

        // Shieldmeet occurs every 4 years (leap year equivalent)
        $this->assertTrue($calendar->isLeapYear(1492)); // DR 1492 is leap year
        $this->assertFalse($calendar->isLeapYear(1493));

        // Verify epoch notation (Dalereckoning)
        $epoch = $calendar->getEpochNotation();
        $this->assertArrayHasKey('before', $epoch);
        $this->assertArrayHasKey('after', $epoch);
    }

    /**
     * Test: Query calendar properties (months, days per month, year structure)
     */
    public function testQueryCalendarProperties(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');

        // Test getMonthNames returns array
        $months = $gregorian->getMonthNames();
        $this->assertIsArray($months);
        $this->assertCount(12, $months);

        // Test getMonthCount
        $this->assertSame(12, $gregorian->getMonthCount());

        // Test getDaysInMonth for various months and years
        $this->assertSame(31, $gregorian->getDaysInMonth(1, 2024)); // January
        $this->assertSame(30, $gregorian->getDaysInMonth(4, 2024)); // April
        $this->assertSame(29, $gregorian->getDaysInMonth(2, 2024)); // Feb leap
        $this->assertSame(28, $gregorian->getDaysInMonth(2, 2023)); // Feb non-leap

        // Test isLeapYear
        $this->assertTrue($gregorian->isLeapYear(2024));
        $this->assertFalse($gregorian->isLeapYear(2023));

        // Test getEpochNotation structure
        $epoch = $gregorian->getEpochNotation();
        $this->assertIsArray($epoch);
        $this->assertArrayHasKey('before', $epoch);
        $this->assertArrayHasKey('after', $epoch);
        $this->assertIsString($epoch['before']);
        $this->assertIsString($epoch['after']);
    }

    /**
     * Test: Invalid profile name throws exception with available profiles list
     */
    public function testInvalidProfileNameThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown calendar profile');

        Calendar::fromProfile('nonexistent-calendar');
    }

    /**
     * Test: Exception message includes list of available profiles
     */
    public function testInvalidProfileExceptionIncludesAvailableProfiles(): void
    {
        try {
            Calendar::fromProfile('invalid');
            $this->fail('Expected exception was not thrown');
        } catch (InvalidArgumentException $e) {
            $message = $e->getMessage();
            $this->assertStringContainsString('gregorian', $message);
            $this->assertStringContainsString('faerun', $message);
        }
    }
}
