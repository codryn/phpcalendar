<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Acceptance;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\TimeSpan;
use PHPUnit\Framework\TestCase;

/**
 * Acceptance tests for User Story 3: Calculate Time Differences
 *
 * Goal: Enable calculating TimeSpan between two TimePoints and performing
 * date arithmetic (add/subtract)
 */
class UserStory3Test extends TestCase
{
    public function testCalculateDifferenceBetweenDates(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $start = $calendar->parse('2024-01-01');
        $end = $calendar->parse('2024-12-31');

        $span = $calendar->diff($start, $end);

        $this->assertSame(365, $span->getTotalDays());
    }

    public function testCalculateDifferenceSpanningLeapYear(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $start = $calendar->parse('2024-01-01');
        $end = $calendar->parse('2025-01-01');

        $span = $calendar->diff($start, $end);

        // 2024 is leap year, so 366 days
        $this->assertSame(366, $span->getTotalDays());
    }

    public function testAddTimeSpanToDate(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $date = $calendar->parse('2024-01-01');
        $span = TimeSpan::fromSeconds(86400 * 30); // 30 days

        $future = $date->add($span);

        $this->assertSame(1, $future->getMonth());
        $this->assertSame(31, $future->getDay());
    }

    public function testSubtractTimeSpanFromDate(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $date = $calendar->parse('2024-02-15');
        $span = TimeSpan::fromSeconds(86400 * 10); // 10 days

        $past = $date->subtract($span);

        $this->assertSame(2, $past->getMonth());
        $this->assertSame(5, $past->getDay());
    }

    public function testNegativeTimeSpan(): void
    {
        $calendar = Calendar::fromProfile('gregorian');
        $start = $calendar->parse('2024-12-31');
        $end = $calendar->parse('2024-01-01');

        $span = $calendar->diff($start, $end);

        $this->assertTrue($span->isNegative());
        $this->assertSame(-365, $span->getTotalDays());
    }
}
