<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Acceptance;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarConfiguration;
use Codryn\PHPCalendar\Exception\InvalidCalendarConfigException;
use PHPUnit\Framework\TestCase;

/**
 * Acceptance tests for User Story 4: Create Fully Custom Calendar
 *
 * Goal: Enable developers to create custom calendars with user-defined
 * month names, day counts, leap year rules, and epoch notation
 */
class UserStory4Test extends TestCase
{
    public function testCreateCustomCalendarWithConfiguration(): void
    {
        $config = new CalendarConfiguration(
            name: 'custom',
            displayName: 'My Custom Calendar',
            monthNames: [1 => 'Alpha', 2 => 'Beta', 3 => 'Gamma'],
            daysPerMonth: [1 => 30, 2 => 30, 3 => 30],
            leapYearRule: fn (int $year) => $year % 5 === 0,
            epochNotation: ['before' => 'BE', 'after' => 'AE'],
            formatPatterns: ['F j, Y'],
        );

        $calendar = Calendar::fromConfiguration($config);

        $this->assertSame('custom', $calendar->getName());
        $this->assertSame('My Custom Calendar', $calendar->getDisplayName());
        $this->assertSame(3, $calendar->getMonthCount());
    }

    public function testCustomCalendarWithIrregularMonthLengths(): void
    {
        $config = new CalendarConfiguration(
            name: 'irregular',
            displayName: 'Irregular Calendar',
            monthNames: [1 => 'Short', 2 => 'Medium', 3 => 'Long'],
            daysPerMonth: [1 => 20, 2 => 30, 3 => 40],
        );

        $calendar = Calendar::fromConfiguration($config);

        $this->assertSame(20, $calendar->getDaysInMonth(1, 2024));
        $this->assertSame(30, $calendar->getDaysInMonth(2, 2024));
        $this->assertSame(40, $calendar->getDaysInMonth(3, 2024));
    }

    public function testCustomLeapYearRules(): void
    {
        $config = new CalendarConfiguration(
            name: 'leap5',
            displayName: 'Leap Every 5 Years',
            monthNames: [1 => 'Month1', 2 => 'Month2'],
            daysPerMonth: [1 => 30, 2 => 30],
            leapYearRule: fn (int $year) => $year % 5 === 0,
        );

        $calendar = Calendar::fromConfiguration($config);

        $this->assertTrue($calendar->isLeapYear(5));
        $this->assertTrue($calendar->isLeapYear(10));
        $this->assertFalse($calendar->isLeapYear(4));
        $this->assertFalse($calendar->isLeapYear(6));
    }

    public function testInvalidConfigurationThrowsException(): void
    {
        $this->expectException(InvalidCalendarConfigException::class);

        $config = new CalendarConfiguration(
            name: '',  // Invalid: empty name
            displayName: 'Invalid',
            monthNames: [1 => 'Month'],
            daysPerMonth: [1 => 30],
        );

        Calendar::fromConfiguration($config);
    }

    public function testMismatchedArrayLengthsThrowsException(): void
    {
        $this->expectException(InvalidCalendarConfigException::class);

        $config = new CalendarConfiguration(
            name: 'invalid',
            displayName: 'Invalid',
            monthNames: [1 => 'Month1', 2 => 'Month2', 3 => 'Month3'],
            daysPerMonth: [1 => 30, 2 => 30], // Missing 3rd entry
        );

        Calendar::fromConfiguration($config);
    }

    public function testCustomCalendarBehavesIdenticallyToProfileBased(): void
    {
        // Create a custom calendar mimicking Gregorian
        $config = new CalendarConfiguration(
            name: 'custom-gregorian',
            displayName: 'Custom Gregorian',
            monthNames: [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
            ],
            daysPerMonth: [
                1 => 31, 2 => 28, 3 => 31, 4 => 30,
                5 => 31, 6 => 30, 7 => 31, 8 => 31,
                9 => 30, 10 => 31, 11 => 30, 12 => 31,
            ],
        );

        $customCalendar = Calendar::fromConfiguration($config);

        $this->assertSame(12, $customCalendar->getMonthCount());
        $this->assertSame(31, $customCalendar->getDaysInMonth(1, 2024));
        $this->assertSame('January', $customCalendar->getMonthNames()[1]);
    }
}
