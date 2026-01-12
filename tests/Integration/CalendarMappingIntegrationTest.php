<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Integration;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarMapping;
use Codryn\PHPCalendar\Calendar\CalendarMappingConfiguration;
use Codryn\PHPCalendar\Calendar\DateConverter;
use Codryn\PHPCalendar\Calendar\TimePoint;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for calendar mapping and date conversion
 *
 * Tests real-world scenarios of converting dates between different calendar systems
 */
class CalendarMappingIntegrationTest extends TestCase
{
    public function testConvertGregorianToFaerunAndBack(): void
    {
        // Setup calendars
        $gregorian = Calendar::fromProfile('gregorian');
        $faerun = Calendar::fromProfile('faerun');

        // Create mapping: January 1, 2024 (Gregorian) = 1 Hammer 1492 DR (Faerûn)
        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $gregorian, $faerun);

        // Convert a date from Gregorian to Faerûn
        // Note: Due to nameless days in Faerûn, the exact day may differ
        $christmas2024 = new TimePoint($gregorian, 2024, 12, 25);
        $faerunDate = $mapping->convert($christmas2024);

        $this->assertSame(1492, $faerunDate->getYear());
        // The conversion preserves the time difference from the correlation point
        // which may result in different day/month due to calendar structure differences

        // Convert back to Gregorian - should give us the exact same date
        $gregorianDateBack = $mapping->reverseConvert($faerunDate);

        $this->assertSame(2024, $gregorianDateBack->getYear());
        $this->assertSame(12, $gregorianDateBack->getMonth());
        $this->assertSame(25, $gregorianDateBack->getDay());
    }

    public function testDateConverterWithMultipleCalendarSystems(): void
    {
        // Setup calendars
        $gregorian = Calendar::fromProfile('gregorian');
        $faerun = Calendar::fromProfile('faerun');
        $golarion = Calendar::fromProfile('golarion');

        // Create date converter
        $converter = new DateConverter();

        // Register mapping: Gregorian <-> Faerûn
        $faerunConfig = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );
        $faerunMapping = new CalendarMapping($faerunConfig, $gregorian, $faerun);
        $converter->registerMapping($faerunMapping);

        // Register mapping: Gregorian <-> Golarion
        $golarionConfig = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'golarion',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 4724, 'month' => 1, 'day' => 1],
            ],
        );
        $golarionMapping = new CalendarMapping($golarionConfig, $gregorian, $golarion);
        $converter->registerMapping($golarionMapping);

        // Convert a Gregorian date to both fantasy calendars
        $newYear2024 = new TimePoint($gregorian, 2024, 1, 1);

        $faerunNewYear = $converter->convert($newYear2024, $faerun);
        $this->assertSame(1492, $faerunNewYear->getYear());
        $this->assertSame(1, $faerunNewYear->getMonth());
        $this->assertSame(1, $faerunNewYear->getDay());

        $golarionNewYear = $converter->convert($newYear2024, $golarion);
        $this->assertSame(4724, $golarionNewYear->getYear());
        $this->assertSame(1, $golarionNewYear->getMonth());
        $this->assertSame(1, $golarionNewYear->getDay());

        // Convert back from Faerûn to Gregorian
        $backToGregorian = $converter->convert($faerunNewYear, $gregorian);
        $this->assertSame(2024, $backToGregorian->getYear());
        $this->assertSame(1, $backToGregorian->getMonth());
        $this->assertSame(1, $backToGregorian->getDay());
    }

    public function testConvertDatesAcrossYears(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');
        $faerun = Calendar::fromProfile('faerun');

        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $gregorian, $faerun);

        // Test converting dates and ensuring round-trip works
        // Note: The exact day/month in Faerûn may differ due to nameless days,
        // but round-trip conversion should always return to the original Gregorian date
        $testDates = [
            [2024, 3, 15],
            [2024, 6, 30],
            [2024, 9, 20],
            [2024, 12, 31],
        ];

        foreach ($testDates as $date) {
            $gregorianDate = new TimePoint(
                $gregorian,
                $date[0],
                $date[1],
                $date[2],
            );

            $faerunDate = $mapping->convert($gregorianDate);
            $this->assertSame(1492, $faerunDate->getYear()); // Year should match correlation

            // Convert back and verify it matches the original
            $backToGregorian = $mapping->reverseConvert($faerunDate);
            $this->assertSame($date[0], $backToGregorian->getYear());
            $this->assertSame($date[1], $backToGregorian->getMonth());
            $this->assertSame($date[2], $backToGregorian->getDay());
        }
    }

    public function testConvertDatesWithTimeComponents(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');
        $faerun = Calendar::fromProfile('faerun');

        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $gregorian, $faerun);

        // Convert a date with specific time
        $gregorianDateTime = new TimePoint($gregorian, 2024, 6, 15, 15, 30, 45);
        $faerunDateTime = $mapping->convert($gregorianDateTime);

        $this->assertSame(1492, $faerunDateTime->getYear());
        $this->assertSame(6, $faerunDateTime->getMonth());
        $this->assertSame(15, $faerunDateTime->getDay());
        $this->assertSame(15, $faerunDateTime->getHour());
        $this->assertSame(30, $faerunDateTime->getMinute());
        $this->assertSame(45, $faerunDateTime->getSecond());
    }

    public function testMappingWithValidDateRange(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');
        $faerun = Calendar::fromProfile('faerun');

        // Create a mapping valid only for the year 2024
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

        $mapping = new CalendarMapping($config, $gregorian, $faerun);

        // Convert a date within the valid range
        $validDate = new TimePoint($gregorian, 2024, 6, 15);
        $faerunDate = $mapping->convert($validDate);

        $this->assertSame(1492, $faerunDate->getYear());
        $this->assertSame(6, $faerunDate->getMonth());
        $this->assertSame(15, $faerunDate->getDay());

        // Trying to convert a date outside the range would throw an exception
        // (tested in unit tests)
    }

    public function testRoundTripConversionPreservesDate(): void
    {
        $gregorian = Calendar::fromProfile('gregorian');
        $faerun = Calendar::fromProfile('faerun');

        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $gregorian, $faerun);

        // Test that converting to Faerûn and back preserves the original date
        $originalDate = new TimePoint($gregorian, 2024, 7, 4, 12, 0, 0);
        $faerunDate = $mapping->convert($originalDate);
        $backToGregorian = $mapping->reverseConvert($faerunDate);

        $this->assertSame($originalDate->getYear(), $backToGregorian->getYear());
        $this->assertSame($originalDate->getMonth(), $backToGregorian->getMonth());
        $this->assertSame($originalDate->getDay(), $backToGregorian->getDay());
        $this->assertSame($originalDate->getHour(), $backToGregorian->getHour());
        $this->assertSame($originalDate->getMinute(), $backToGregorian->getMinute());
        $this->assertSame($originalDate->getSecond(), $backToGregorian->getSecond());
    }
}
