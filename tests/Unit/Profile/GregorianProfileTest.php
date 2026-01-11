<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Profile;

use Codryn\PHPCalendar\Profile\GregorianProfile;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for GregorianProfile
 */
class GregorianProfileTest extends TestCase
{
    private GregorianProfile $profile;

    protected function setUp(): void
    {
        $this->profile = new GregorianProfile();
    }

    public function testGetName(): void
    {
        $this->assertSame('gregorian', $this->profile->getName());
    }

    public function testGetDisplayName(): void
    {
        $this->assertSame('Gregorian Calendar', $this->profile->getDisplayName());
    }

    public function testGetMonthNames(): void
    {
        $months = $this->profile->getMonthNames();

        $this->assertIsArray($months);
        $this->assertCount(12, $months);
        $this->assertSame('January', $months[1]);
        $this->assertSame('February', $months[2]);
        $this->assertSame('March', $months[3]);
        $this->assertSame('April', $months[4]);
        $this->assertSame('May', $months[5]);
        $this->assertSame('June', $months[6]);
        $this->assertSame('July', $months[7]);
        $this->assertSame('August', $months[8]);
        $this->assertSame('September', $months[9]);
        $this->assertSame('October', $months[10]);
        $this->assertSame('November', $months[11]);
        $this->assertSame('December', $months[12]);
    }

    public function testGetMonthCount(): void
    {
        $this->assertSame(12, $this->profile->getMonthCount());
    }

    public function testGetDaysInMonthForRegularYear(): void
    {
        $year = 2023; // Non-leap year

        $this->assertSame(31, $this->profile->getDaysInMonth(1, $year));  // January
        $this->assertSame(28, $this->profile->getDaysInMonth(2, $year));  // February
        $this->assertSame(31, $this->profile->getDaysInMonth(3, $year));  // March
        $this->assertSame(30, $this->profile->getDaysInMonth(4, $year));  // April
        $this->assertSame(31, $this->profile->getDaysInMonth(5, $year));  // May
        $this->assertSame(30, $this->profile->getDaysInMonth(6, $year));  // June
        $this->assertSame(31, $this->profile->getDaysInMonth(7, $year));  // July
        $this->assertSame(31, $this->profile->getDaysInMonth(8, $year));  // August
        $this->assertSame(30, $this->profile->getDaysInMonth(9, $year));  // September
        $this->assertSame(31, $this->profile->getDaysInMonth(10, $year)); // October
        $this->assertSame(30, $this->profile->getDaysInMonth(11, $year)); // November
        $this->assertSame(31, $this->profile->getDaysInMonth(12, $year)); // December
    }

    public function testGetDaysInFebruaryForLeapYear(): void
    {
        $this->assertSame(29, $this->profile->getDaysInMonth(2, 2024));
        $this->assertSame(29, $this->profile->getDaysInMonth(2, 2000));
    }

    public function testIsLeapYearDivisibleBy4(): void
    {
        $this->assertTrue($this->profile->isLeapYear(2024));
        $this->assertTrue($this->profile->isLeapYear(2020));
        $this->assertTrue($this->profile->isLeapYear(2016));
    }

    public function testIsNotLeapYearNotDivisibleBy4(): void
    {
        $this->assertFalse($this->profile->isLeapYear(2023));
        $this->assertFalse($this->profile->isLeapYear(2022));
        $this->assertFalse($this->profile->isLeapYear(2021));
    }

    public function testIsNotLeapYearDivisibleBy100(): void
    {
        $this->assertFalse($this->profile->isLeapYear(2100));
        $this->assertFalse($this->profile->isLeapYear(2200));
        $this->assertFalse($this->profile->isLeapYear(1900));
    }

    public function testIsLeapYearDivisibleBy400(): void
    {
        $this->assertTrue($this->profile->isLeapYear(2000));
        $this->assertTrue($this->profile->isLeapYear(2400));
        $this->assertTrue($this->profile->isLeapYear(1600));
    }

    public function testGetEpochNotation(): void
    {
        $epoch = $this->profile->getEpochNotation();

        $this->assertIsArray($epoch);
        $this->assertArrayHasKey('before', $epoch);
        $this->assertArrayHasKey('after', $epoch);
        $this->assertSame('BCE', $epoch['before']);
        $this->assertSame('CE', $epoch['after']);
    }

    public function testGetFormatPatterns(): void
    {
        $patterns = $this->profile->getFormatPatterns();

        $this->assertIsArray($patterns);
        $this->assertNotEmpty($patterns);
    }

    public function testGetMetadata(): void
    {
        $metadata = $this->profile->getMetadata();

        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('source', $metadata);
        $this->assertArrayHasKey('setting', $metadata);
    }
}
