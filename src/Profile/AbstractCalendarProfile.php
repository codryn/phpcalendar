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
            $days += $this->isLeapYear($y) ? 366 : 365;
        }

        // Add days for months
        for ($m = 1; $m < $month; $m++) {
            $days += $this->getDaysInMonth($m, $year);
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
            $daysInYear = $this->isLeapYear($year) ? 366 : 365;
            if ($days < $daysInYear) {
                break;
            }
            $days -= $daysInYear;
            $year++;
        }

        // Find month
        $month = 1;
        while ($month <= $this->getMonthCount()) {
            $daysInMonth = $this->getDaysInMonth($month, $year);
            if ($days < $daysInMonth) {
                break;
            }
            $days -= $daysInMonth;
            $month++;
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
