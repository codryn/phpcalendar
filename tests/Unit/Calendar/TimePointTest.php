<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Calendar;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\TimePoint;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for TimePoint value object
 */
class TimePointTest extends TestCase
{
    private Calendar $calendar;

    protected function setUp(): void
    {
        $this->calendar = Calendar::fromProfile('gregorian');
    }

    public function testCreateTimePointWithAllComponents(): void
    {
        $point = new TimePoint($this->calendar, 2024, 12, 25, 14, 30, 45, 123456);

        $this->assertSame($this->calendar, $point->getCalendar());
        $this->assertSame(2024, $point->getYear());
        $this->assertSame(12, $point->getMonth());
        $this->assertSame(25, $point->getDay());
        $this->assertSame(14, $point->getHour());
        $this->assertSame(30, $point->getMinute());
        $this->assertSame(45, $point->getSecond());
        $this->assertSame(123456, $point->getMicrosecond());
    }

    public function testCreateTimePointWithDefaultTimeComponents(): void
    {
        $point = new TimePoint($this->calendar, 2024, 1, 1);

        $this->assertSame(0, $point->getHour());
        $this->assertSame(0, $point->getMinute());
        $this->assertSame(0, $point->getSecond());
        $this->assertSame(0, $point->getMicrosecond());
    }

    public function testCreateTimePointWithPartialTimeComponents(): void
    {
        $point = new TimePoint($this->calendar, 2024, 6, 15, 10, 30);

        $this->assertSame(10, $point->getHour());
        $this->assertSame(30, $point->getMinute());
        $this->assertSame(0, $point->getSecond());
        $this->assertSame(0, $point->getMicrosecond());
    }
}
