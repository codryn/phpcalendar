<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Acceptance;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Exception\InvalidDateException;
use PHPUnit\Framework\TestCase;

/**
 * Acceptance tests for User Story 2: Parse and Format Dates
 *
 * Goal: Enable parsing date strings to TimePoint objects and formatting
 * TimePoint back to strings with round-trip consistency
 */
class UserStory2Test extends TestCase
{
    public function testParseGregorianDateString(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $date = $calendar->parse('2024-12-25');

        $this->assertSame(2024, $date->getYear());
        $this->assertSame(12, $date->getMonth());
        $this->assertSame(25, $date->getDay());
    }

    public function testFormatTimePointToString(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $date = $calendar->parse('2024-12-25');

        $formatted = $calendar->format($date);

        $this->assertStringContainsString('December', $formatted);
        $this->assertStringContainsString('25', $formatted);
        $this->assertStringContainsString('2024', $formatted);
    }

    public function testFormatWithCustomPattern(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $date = $calendar->parse('2024-12-25');

        $formatted = $calendar->format($date, 'Y-m-d');

        $this->assertSame('2024-12-25', $formatted);
    }

    public function testInvalidDateStringThrowsException(): void
    {
        $calendar = Calendar::fromProfile('gregorian');

        $this->expectException(InvalidDateException::class);

        $calendar->parse('invalid-date-string');
    }

    public function testParseVariousFormats(): void
    {
        $calendar = Calendar::fromProfile('gregorian');

        $date1 = $calendar->parse('2024-12-25');
        $date2 = $calendar->parse('December 25, 2024');
        $date3 = $calendar->parse('2024/12/25');

        $this->assertSame(2024, $date1->getYear());
        $this->assertSame(2024, $date2->getYear());
        $this->assertSame(2024, $date3->getYear());
    }
}
