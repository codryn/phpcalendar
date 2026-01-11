<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Calendar;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarMapping;
use Codryn\PHPCalendar\Calendar\CalendarMappingConfiguration;
use Codryn\PHPCalendar\Calendar\DateConverter;
use Codryn\PHPCalendar\Calendar\TimePoint;
use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for DateConverter
 */
class DateConverterTest extends TestCase
{
    private Calendar $gregorian;

    private Calendar $faerun;

    private Calendar $golarion;

    protected function setUp(): void
    {
        $this->gregorian = Calendar::fromProfile('gregorian');
        $this->faerun = Calendar::fromProfile('faerun');
        $this->golarion = Calendar::fromProfile('golarion');
    }

    public function testRegisterMapping(): void
    {
        $converter = new DateConverter();

        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);
        $converter->registerMapping($mapping);

        $this->assertTrue($converter->canConvert('gregorian', 'faerun'));
        $this->assertTrue($converter->canConvert('faerun', 'gregorian')); // Bidirectional
    }

    public function testCannotConvertWithoutMapping(): void
    {
        $converter = new DateConverter();

        $this->assertFalse($converter->canConvert('gregorian', 'faerun'));
        $this->assertFalse($converter->canConvert('faerun', 'gregorian'));
    }

    public function testConvertBetweenCalendars(): void
    {
        $converter = new DateConverter();

        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);
        $converter->registerMapping($mapping);

        // Convert from Gregorian to Faerûn
        $gregorianDate = new TimePoint($this->gregorian, 2024, 1, 15);
        $faerunDate = $converter->convert($gregorianDate, $this->faerun);

        $this->assertSame($this->faerun, $faerunDate->getCalendar());
        $this->assertSame(1492, $faerunDate->getYear());
        $this->assertSame(1, $faerunDate->getMonth());
        $this->assertSame(15, $faerunDate->getDay());
    }

    public function testConvertBetweenCalendarsBidirectionally(): void
    {
        $converter = new DateConverter();

        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);
        $converter->registerMapping($mapping);

        // Convert from Faerûn to Gregorian (reverse)
        $faerunDate = new TimePoint($this->faerun, 1492, 2, 10);
        $gregorianDate = $converter->convert($faerunDate, $this->gregorian);

        $this->assertSame($this->gregorian, $gregorianDate->getCalendar());
        $this->assertSame(2024, $gregorianDate->getYear());
        $this->assertSame(2, $gregorianDate->getMonth());
        $this->assertSame(10, $gregorianDate->getDay());
    }

    public function testThrowsExceptionWhenNoMappingExists(): void
    {
        $converter = new DateConverter();

        $gregorianDate = new TimePoint($this->gregorian, 2024, 1, 1);

        $this->expectException(IncompatibleCalendarException::class);
        $this->expectExceptionMessage("No mapping found between 'gregorian' and 'faerun'");

        $converter->convert($gregorianDate, $this->faerun);
    }

    public function testRegisterMultipleMappings(): void
    {
        $converter = new DateConverter();

        // Mapping 1: Gregorian <-> Faerûn
        $config1 = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );
        $mapping1 = new CalendarMapping($config1, $this->gregorian, $this->faerun);
        $converter->registerMapping($mapping1);

        // Mapping 2: Gregorian <-> Golarion
        $config2 = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'golarion',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 4724, 'month' => 1, 'day' => 1],
            ],
        );
        $mapping2 = new CalendarMapping($config2, $this->gregorian, $this->golarion);
        $converter->registerMapping($mapping2);

        $this->assertTrue($converter->canConvert('gregorian', 'faerun'));
        $this->assertTrue($converter->canConvert('gregorian', 'golarion'));
        $this->assertFalse($converter->canConvert('faerun', 'golarion'));
    }

    public function testGetMapping(): void
    {
        $converter = new DateConverter();

        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);
        $converter->registerMapping($mapping);

        $retrieved = $converter->getMapping('gregorian', 'faerun');
        $this->assertNotNull($retrieved);
        $this->assertSame($mapping, $retrieved);
    }

    public function testGetMappingReturnsNullWhenNotFound(): void
    {
        $converter = new DateConverter();

        $retrieved = $converter->getMapping('gregorian', 'faerun');
        $this->assertNull($retrieved);
    }

    public function testGetAllMappings(): void
    {
        $converter = new DateConverter();

        $config = new CalendarMappingConfiguration(
            sourceCalendarName: 'gregorian',
            targetCalendarName: 'faerun',
            correlationDate: [
                'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
            ],
        );

        $mapping = new CalendarMapping($config, $this->gregorian, $this->faerun);
        $converter->registerMapping($mapping);

        $mappings = $converter->getMappings();

        // Should have 2 entries (forward and reverse)
        $this->assertCount(2, $mappings);
        $this->assertArrayHasKey('gregorian->faerun', $mappings);
        $this->assertArrayHasKey('faerun->gregorian', $mappings);
    }
}
