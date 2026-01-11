<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

use Codryn\PHPCalendar\Exception\InvalidDateException;

/**
 * Immutable time point value object
 *
 * Represents a specific moment in time within a calendar
 */
final class TimePoint
{
    private Calendar $calendar;

    private int $year;

    private int $month;

    private int $day;

    private int $hour;

    private int $minute;

    private int $second;

    private int $microsecond;

    /**
     * @param Calendar $calendar Parent calendar
     * @param int $year Year (negative for BCE/pre-epoch)
     * @param int $month Month (1-based)
     * @param int $day Day of month (1-based)
     * @param int $hour Hour (0-23, default 0)
     * @param int $minute Minute (0-59, default 0)
     * @param int $second Second (0-59, default 0)
     * @param int $microsecond Microsecond (0-999999, default 0)
     * @throws InvalidDateException if date is invalid
     */
    public function __construct(
        Calendar $calendar,
        int $year,
        int $month,
        int $day,
        int $hour = 0,
        int $minute = 0,
        int $second = 0,
        int $microsecond = 0,
    ) {
        $this->validate($calendar, $year, $month, $day, $hour, $minute, $second, $microsecond);

        $this->calendar = $calendar;
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->microsecond = $microsecond;
    }

    public function getCalendar(): Calendar
    {
        return $this->calendar;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getMinute(): int
    {
        return $this->minute;
    }

    public function getSecond(): int
    {
        return $this->second;
    }

    public function getMicrosecond(): int
    {
        return $this->microsecond;
    }

    /**
     * Add time span to this time point
     *
     * @param TimeSpan $span Time span to add
     * @return self New TimePoint in the future
     * @throws InvalidDateException if result is invalid
     */
    public function add(TimeSpan $span): self
    {
        // Convert current point to seconds
        $currentSeconds = $this->toSeconds();
        $newSeconds = $currentSeconds + $span->getTotalSeconds();
        $newMicroseconds = $this->microsecond + $span->getMicroseconds();

        // Normalize microseconds
        if ($newMicroseconds >= 1000000) {
            $newSeconds += \intdiv($newMicroseconds, 1000000);
            $newMicroseconds = $newMicroseconds % 1000000;
        }

        // Convert back to date components
        return $this->fromSeconds($this->calendar, $newSeconds, $newMicroseconds);
    }

    /**
     * Subtract time span from this time point
     *
     * @param TimeSpan $span Time span to subtract
     * @return self New TimePoint in the past
     * @throws InvalidDateException if result is invalid
     */
    public function subtract(TimeSpan $span): self
    {
        return $this->add($span->negate());
    }

    /**
     * Convert time point to seconds since epoch
     *
     * @return int Seconds since epoch
     */
    private function toSeconds(): int
    {
        $days = 0;

        // Add days for years
        for ($y = 1; $y < $this->year; $y++) {
            $days += $this->calendar->isLeapYear($y) ? 366 : 365;
        }

        // Add days for months
        for ($m = 1; $m < $this->month; $m++) {
            $days += $this->calendar->getDaysInMonth($m, $this->year);
        }

        // Add days
        $days += $this->day - 1;

        $seconds = $days * 86400;
        $seconds += $this->hour * 3600;
        $seconds += $this->minute * 60;
        $seconds += $this->second;

        return $seconds;
    }

    /**
     * Create TimePoint from seconds since epoch
     *
     * @param Calendar $calendar Calendar context
     * @param int $totalSeconds Total seconds since epoch
     * @param int $microseconds Microseconds component
     * @return self
     * @throws InvalidDateException if result is invalid
     */
    private function fromSeconds(Calendar $calendar, int $totalSeconds, int $microseconds = 0): self
    {
        // Extract time components
        $days = \intdiv($totalSeconds, 86400);
        $remainingSeconds = $totalSeconds % 86400;

        $hour = \intdiv($remainingSeconds, 3600);
        $remainingSeconds %= 3600;

        $minute = \intdiv($remainingSeconds, 60);
        $second = $remainingSeconds % 60;

        // Convert days to year/month/day
        $year = 1;
        $month = 1;
        $day = 1;

        // Find the year
        while ($days > 0) {
            $daysInYear = $calendar->isLeapYear($year) ? 366 : 365;

            if ($days >= $daysInYear) {
                $days -= $daysInYear;
                $year++;
            } else {
                break;
            }
        }

        // Find the month
        while ($days > 0) {
            $daysInMonth = $calendar->getDaysInMonth($month, $year);

            if ($days >= $daysInMonth) {
                $days -= $daysInMonth;
                $month++;
            } else {
                break;
            }
        }

        // Remaining days
        $day += $days;

        return new self($calendar, $year, $month, $day, $hour, $minute, $second, $microseconds);
    }

    /**
     * Validate time point components
     *
     * @throws InvalidDateException if any component is invalid
     */
    private function validate(
        Calendar $calendar,
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute,
        int $second,
        int $microsecond,
    ): void {
        // Validate month
        if ($month < 1 || $month > $calendar->getMonthCount()) {
            throw new InvalidDateException(
                "Invalid month: {$month}. Must be between 1 and {$calendar->getMonthCount()}",
            );
        }

        // Validate day
        $daysInMonth = $calendar->getDaysInMonth($month, $year);

        if ($day < 1 || $day > $daysInMonth) {
            throw new InvalidDateException(
                "Invalid day: {$day}. Must be between 1 and {$daysInMonth} for month {$month}",
            );
        }

        // Validate time components
        if ($hour < 0 || $hour > 23) {
            throw new InvalidDateException("Invalid hour: {$hour}. Must be between 0 and 23");
        }

        if ($minute < 0 || $minute > 59) {
            throw new InvalidDateException("Invalid minute: {$minute}. Must be between 0 and 59");
        }

        if ($second < 0 || $second > 59) {
            throw new InvalidDateException("Invalid second: {$second}. Must be between 0 and 59");
        }

        if ($microsecond < 0 || $microsecond > 999999) {
            throw new InvalidDateException(
                "Invalid microsecond: {$microsecond}. Must be between 0 and 999999",
            );
        }
    }
}
