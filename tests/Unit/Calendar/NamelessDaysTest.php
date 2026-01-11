<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Calendar;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\TimePoint;
use Codryn\PHPCalendar\Calendar\TimeSpan;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for nameless days functionality
 */
class NamelessDaysTest extends TestCase
{
    public function testDSAProfileHasNamelessDays(): void
    {
        $calendar = Calendar::fromProfile('dsa');
        $profile = $calendar->getProfile();
        $namelessDays = $profile->getNamelessDays();

        $this->assertIsArray($namelessDays);
        $this->assertNotEmpty($namelessDays);

        // DSA has 5 nameless days after month 12
        $this->assertCount(1, $namelessDays);
        $this->assertSame(12, $namelessDays[0]['position']);
        $this->assertCount(5, $namelessDays[0]['names']);
        $this->assertFalse($namelessDays[0]['leap']);
    }

    public function testFaerunProfileHasFestivalDays(): void
    {
        $calendar = Calendar::fromProfile('faerun');
        $profile = $calendar->getProfile();
        $namelessDays = $profile->getNamelessDays();

        $this->assertIsArray($namelessDays);
        $this->assertNotEmpty($namelessDays);

        // Faerun has 5 festival days at various positions
        $this->assertCount(5, $namelessDays);

        // Check that Midsummer has leap day support
        $midsummerGroup = null;
        foreach ($namelessDays as $group) {
            if ($group['position'] === 7) { // After Flamerule
                $midsummerGroup = $group;
                break;
            }
        }

        $this->assertNotNull($midsummerGroup);
        $this->assertTrue($midsummerGroup['leap']);
    }

    public function testDSAYearHas365Days(): void
    {
        $calendar = Calendar::fromProfile('dsa');

        $startDate = new TimePoint($calendar, 1000, 1, 1);
        $endDate = new TimePoint($calendar, 1001, 1, 1);

        $span = $calendar->diff($startDate, $endDate);

        // DSA year has 360 days in months + 5 nameless days = 365 total
        $this->assertSame(365, $span->getTotalDays());
    }

    public function testFaerunLeapYearHas366Days(): void
    {
        $calendar = Calendar::fromProfile('faerun');

        // 1492 is a leap year (divisible by 4)
        $startDate = new TimePoint($calendar, 1492, 1, 1);
        $endDate = new TimePoint($calendar, 1493, 1, 1);

        $span = $calendar->diff($startDate, $endDate);

        // Faerun leap year has 360 days in months + 5 festivals + 1 Shieldmeet = 366 total
        $this->assertSame(366, $span->getTotalDays());
    }

    public function testFaerunNonLeapYearHas365Days(): void
    {
        $calendar = Calendar::fromProfile('faerun');

        // 1493 is not a leap year
        $startDate = new TimePoint($calendar, 1493, 1, 1);
        $endDate = new TimePoint($calendar, 1494, 1, 1);

        $span = $calendar->diff($startDate, $endDate);

        // Faerun non-leap year has 360 days in months + 5 festivals = 365 total
        $this->assertSame(365, $span->getTotalDays());
    }

    public function testAddingDaysAccountsForNamelessDays(): void
    {
        $calendar = Calendar::fromProfile('dsa');

        // Start at day 25 of month 12
        $startDate = new TimePoint($calendar, 1000, 12, 25);

        // Add 11 days (5 days to end of month 12 + 5 nameless days + 1 day into year 1001 = day 1 of month 1, year 1001)
        $endDate = $startDate->add(TimeSpan::fromSeconds(11 * 86400));

        // Should have moved to year 1001, month 1, day 1
        $this->assertSame(1001, $endDate->getYear());
        $this->assertSame(1, $endDate->getMonth());
        $this->assertSame(1, $endDate->getDay());

        // The total calculation should account for nameless days
        $span = $calendar->diff($startDate, $endDate);
        $this->assertSame(11, $span->getTotalDays());
    }

    public function testDateArithmeticAcrossMultipleYearsWithNamelessDays(): void
    {
        $calendar = Calendar::fromProfile('dsa');

        $startDate = new TimePoint($calendar, 1000, 1, 1);
        $endDate = new TimePoint($calendar, 1005, 1, 1);

        $span = $calendar->diff($startDate, $endDate);

        // 5 years × 365 days/year = 1825 days
        $this->assertSame(1825, $span->getTotalDays());
    }

    public function testGregorianCalendarHasNoNamelessDays(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $profile = $calendar->getProfile();
        $namelessDays = $profile->getNamelessDays();

        $this->assertIsArray($namelessDays);
        $this->assertEmpty($namelessDays);
    }
}
