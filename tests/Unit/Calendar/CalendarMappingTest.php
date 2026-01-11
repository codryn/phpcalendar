<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Calendar;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarMapping;
use Codryn\PHPCalendar\Calendar\CalendarMappingConfiguration;
use Codryn\PHPCalendar\Calendar\TimePoint;
use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;
use Codryn\PHPCalendar\Exception\InvalidDateException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CalendarMapping
 */
class CalendarMappingTest extends TestCase
{
    private Calendar $gregorian;

    private Calendar $faerun;

    protected function setUp(): void
    {
        $this->gregorian = Calendar::fromProfile('gregorian');
        $this->faerun = Calendar::fromProfile('faerun');
    }

    public function testCreateMapping(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        $this->assertSame($this->gregorian, $mapping->getSourceCalendar());
        $this->assertSame($this->faerun, $mapping->getTargetCalendar());
        $this->assertSame($config, $mapping->getConfiguration());
    }

    public function testConvertDateFromGregorianToFaerun(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        // Convert Gregorian 2024-01-10 to Faerûn
        $gregorianDate = new TimePoint($this->gregorian, 2024, 1, 10);
        $faerunDate = $mapping->convert($gregorianDate);

        $this->assertSame($this->faerun, $faerunDate->getCalendar());
        $this->assertSame(1492, $faerunDate->getYear());
        $this->assertSame(1, $faerunDate->getMonth());
        $this->assertSame(10, $faerunDate->getDay());
    }

    public function testReverseConvertDateFromFaerunToGregorian(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        // Convert Faerûn 1492-01-20 back to Gregorian
        $faerunDate = new TimePoint($this->faerun, 1492, 1, 20);
        $gregorianDate = $mapping->reverseConvert($faerunDate);

        $this->assertSame($this->gregorian, $gregorianDate->getCalendar());
        $this->assertSame(2024, $gregorianDate->getYear());
        $this->assertSame(1, $gregorianDate->getMonth());
        $this->assertSame(20, $gregorianDate->getDay());
    }

    public function testConvertDateWithTimePrecision(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        // Convert with time components
        $gregorianDate = new TimePoint($this->gregorian, 2024, 1, 5, 14, 30, 45);
        $faerunDate = $mapping->convert($gregorianDate);

        $this->assertSame(1492, $faerunDate->getYear());
        $this->assertSame(1, $faerunDate->getMonth());
        $this->assertSame(5, $faerunDate->getDay());
        $this->assertSame(14, $faerunDate->getHour());
        $this->assertSame(30, $faerunDate->getMinute());
        $this->assertSame(45, $faerunDate->getSecond());
    }

    public function testConvertThrowsExceptionForWrongCalendar(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        $this->expectException(IncompatibleCalendarException::class);
        $this->expectExceptionMessage('TimePoint must be from the source calendar');

        // Try to convert a date from Faerûn using convert (should be reverseConvert)
        $faerunDate = new TimePoint($this->faerun, 1492, 1, 5);
        $mapping->convert($faerunDate);
    }

    public function testReverseConvertThrowsExceptionForWrongCalendar(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        $this->expectException(IncompatibleCalendarException::class);
        $this->expectExceptionMessage('TimePoint must be from the target calendar');

        // Try to reverse convert a date from Gregorian
        $gregorianDate = new TimePoint($this->gregorian, 2024, 1, 5);
        $mapping->reverseConvert($gregorianDate);
    }

    public function testMappingWithValidRangeMinimum(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
            validRange: [
                'min' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'max' => null,
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        $this->expectException(InvalidDateException::class);
        $this->expectExceptionMessage('Date is before minimum valid date for conversion');

        // Try to convert a date before the minimum
        $gregorianDate = new TimePoint($this->gregorian, 2023, 12, 31);
        $mapping->convert($gregorianDate);
    }

    public function testMappingWithValidRangeMaximum(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
            validRange: [
                'min' => null,
                'max' => ['year' => 2024, 'month' => 12, 'day' => 31],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        $this->expectException(InvalidDateException::class);
        $this->expectExceptionMessage('Date is after maximum valid date for conversion');

        // Try to convert a date after the maximum
        $gregorianDate = new TimePoint($this->gregorian, 2025, 1, 1);
        $mapping->convert($gregorianDate);
    }

    public function testMappingWithValidDateInRange(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
            validRange: [
                'min' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'max' => ['year' => 2024, 'month' => 12, 'day' => 31],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);

        // Convert a date within the valid range
        $gregorianDate = new TimePoint($this->gregorian, 2024, 6, 15);
        $faerunDate = $mapping->convert($gregorianDate);

        $this->assertSame(1492, $faerunDate->getYear());
        $this->assertSame(6, $faerunDate->getMonth());
    }

    public function testThrowsExceptionWhenSourceCalendarDoesNotMatchConfig(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'faerun',  // Wrong calendar
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $this->expectException(IncompatibleCalendarException::class);
        $this->expectExceptionMessage("Source calendar 'gregorian' does not match configuration 'faerun'");

        new CalendarMapping($config, $this->gregorian, $this->faerun);
    }

    public function testThrowsExceptionWhenTargetCalendarDoesNotMatchConfig(): void
    {
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'gregorian',  // Wrong calendar
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $this->expectException(IncompatibleCalendarException::class);
        $this->expectExceptionMessage("Target calendar 'faerun' does not match configuration 'gregorian'");

        new CalendarMapping($config, $this->gregorian, $this->faerun);
    }
}
