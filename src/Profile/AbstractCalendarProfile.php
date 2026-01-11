<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Calendar\CalendarProfileInterface;

/**
 * Base class for calendar profile implementations
 *
 * Provides common functionality for calendar profiles
 */
abstract class AbstractCalendarProfile implements CalendarProfileInterface
{
    /**
     * @inheritDoc
     */
    public function getMonthCount(): int
    {
        return \count($this->getMonthNames());
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'source' => 'Unknown',
            'setting' => 'Unknown',
            'description' => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNamelessDays(): array
    {
        // No nameless days by default
        return [];
    }

    /**
     * Get total days in a given year (including nameless days)
     *
     * @param int $year Year to check
     * @return int Total days in year
     */
    protected function getDaysInYear(int $year): int
    {
        $days = 0;

        // Add days from all months
        for ($m = 1; $m <= $this->getMonthCount(); $m++) {
            $days += $this->getDaysInMonth($m, $year);
        }

        // Add nameless days
        foreach ($this->getNamelessDays() as $namelessGroup) {
            $count = \count($namelessGroup['names']);
            // Add leap day if applicable
            if ($namelessGroup['leap'] && $this->isLeapYear($year)) {
                $count++;
            }
            $days += $count;
        }

        return $days;
    }

    /**
     * @inheritDoc
     */
    public function dateToSeconds(
        int $year,
        int $month,
        int $day,
        int $hour = 0,
        int $minute = 0,
        int $second = 0,
        int $microsecond = 0,
    ): float {
        // Calculate days since epoch
        $days = 0;

        // Add days for years (year 1 is epoch)
        for ($y = 1; $y < $year; $y++) {
            $days += $this->getDaysInYear($y);
        }

        // Add days for months
        for ($m = 1; $m < $month; $m++) {
            $days += $this->getDaysInMonth($m, $year);
        }

        // Add nameless days that occur before the current month
        foreach ($this->getNamelessDays() as $namelessGroup) {
            if ($namelessGroup['position'] < $month) {
                $count = \count($namelessGroup['names']);
                // Only add leap day if it's a leap year
                if ($namelessGroup['leap'] && $this->isLeapYear($year)) {
                    $count++;
                }
                $days += $count;
            }
        }

        // Add days (day 1 is the first day)
        $days += $day - 1;

        // Convert to seconds
        $seconds = $days * 86400;
        $seconds += $hour * 3600;
        $seconds += $minute * 60;
        $seconds += $second;

        // Add microseconds as fractional part
        return $seconds + ($microsecond / 1000000.0);
    }

    /**
     * @inheritDoc
     */
    public function secondsToDate(float $seconds): array
    {
        // Extract microseconds
        $microsecond = (int) \round(($seconds - \floor($seconds)) * 1000000);
        $totalSeconds = (int) \floor($seconds);

        // Extract time components
        $hour = (int) \floor($totalSeconds / 3600) % 24;
        $minute = (int) \floor($totalSeconds / 60) % 60;
        $second = $totalSeconds % 60;

        // Calculate days since epoch
        $days = (int) \floor($totalSeconds / 86400);

        // Find year
        $year = 1;
        while (true) {
            $daysInYear = $this->getDaysInYear($year);
            if ($days < $daysInYear) {
                break;
            }
            $days -= $daysInYear;
            $year++;
        }

        // Now find month and day within the year
        // We need to iterate through months and nameless day groups
        $month = 1;
        $monthCount = $this->getMonthCount();
        $foundMonth = false;

        for ($m = 1; $m <= $monthCount; $m++) {
            // First check if there are nameless days after the previous month
            foreach ($this->getNamelessDays() as $namelessGroup) {
                if ($namelessGroup['position'] === $m - 1) {
                    $namelessCount = \count($namelessGroup['names']);
                    if ($namelessGroup['leap'] && $this->isLeapYear($year)) {
                        $namelessCount++;
                    }

                    if ($days < $namelessCount) {
                        // We're in a nameless day period.
                        // Nameless days don't belong to any specific month, so we represent them
                        // as being in the previous month's last day for display purposes.
                        // The important thing is that the calculations account for these days.
                        $month = $m - 1;
                        if ($month < 1) {
                            $month = $monthCount;
                        }
                        $day = $this->getDaysInMonth($month, $year);

                        return [
                            'year' => $year,
                            'month' => $month,
                            'day' => $day,
                            'hour' => $hour,
                            'minute' => $minute,
                            'second' => $second,
                            'microsecond' => $microsecond,
                        ];
                    }

                    $days -= $namelessCount;
                }
            }

            // Now check the month itself
            $daysInMonth = $this->getDaysInMonth($m, $year);
            if ($days < $daysInMonth) {
                $month = $m;
                $foundMonth = true;
                break;
            }
            $days -= $daysInMonth;
        }

        // Check for nameless days at the end of the year
        // Only check this if we completed the month loop without finding a month
        if (!$foundMonth) {
            foreach ($this->getNamelessDays() as $namelessGroup) {
                if ($namelessGroup['position'] === $monthCount) {
                    $namelessCount = \count($namelessGroup['names']);
                    if ($namelessGroup['leap'] && $this->isLeapYear($year)) {
                        $namelessCount++;
                    }

                    if ($days < $namelessCount) {
                        // We're in the nameless days at the end of the year.
                        // Nameless days don't belong to any specific month, so we represent them
                        // as being in the last month's last day for display purposes.
                        // This is acceptable because nameless days are primarily used for
                        // calculation purposes (year length, date differences) rather than
                        // for specific date representation.
                        $month = $monthCount;
                        $day = $this->getDaysInMonth($month, $year);

                        return [
                            'year' => $year,
                            'month' => $month,
                            'day' => $day,
                            'hour' => $hour,
                            'minute' => $minute,
                            'second' => $second,
                            'microsecond' => $microsecond,
                        ];
                    }
                }
            }
        }

        // Day is remaining days + 1
        $day = $days + 1;

        return [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'second' => $second,
            'microsecond' => $microsecond,
        ];
    }
}
