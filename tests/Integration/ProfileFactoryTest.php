<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Integration;

use Codryn\PHPCalendar\Calendar\Calendar;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for Calendar::fromProfile() factory method
 */
class ProfileFactoryTest extends TestCase
{
    public function testCreateGregorianCalendarFromProfile(): void
    {
        $calendar = Calendar::fromProfile('gregorian');

        $this->assertInstanceOf(Calendar::class, $calendar);
        $this->assertSame('gregorian', $calendar->getName());
        $this->assertSame('Gregorian Calendar', $calendar->getDisplayName());
    }

    public function testCreateFaerunCalendarFromProfile(): void
    {
        $calendar = Calendar::fromProfile('faerun');

        $this->assertInstanceOf(Calendar::class, $calendar);
        $this->assertSame('faerun', $calendar->getName());
        $this->assertSame('Faerûn (Harptos Calendar)', $calendar->getDisplayName());
    }

    public function testMultipleCalendarsCanCoexist(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');
        $faerun = Calendar::fromProfile('faerun');

        $this->assertSame('gregorian', $gregorian->getName());
        $this->assertSame('faerun', $faerun->getName());

        // Verify they use different profiles
        $this->assertNotSame(
            $gregorian->getMonthNames()[1],
            $faerun->getMonthNames()[1],
        );
    }

    public function testCalendarReturnsCorrectMonthCount(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');
        $faerun = Calendar::fromProfile('faerun');

        $this->assertSame(12, $gregorian->getMonthCount());
        $this->assertSame(12, $faerun->getMonthCount());
    }

    public function testCalendarReturnsCorrectLeapYearLogic(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');

        $this->assertTrue($gregorian->isLeapYear(2024));
        $this->assertFalse($gregorian->isLeapYear(2100));
        $this->assertTrue($gregorian->isLeapYear(2000));
    }

    public function testCalendarReturnsCorrectDaysInMonth(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');

        $this->assertSame(31, $gregorian->getDaysInMonth(1, 2024));
        $this->assertSame(29, $gregorian->getDaysInMonth(2, 2024));
        $this->assertSame(28, $gregorian->getDaysInMonth(2, 2023));
    }
}
