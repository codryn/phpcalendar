<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;
use Codryn\PHPCalendar\Parser\DateFormatter;
use Codryn\PHPCalendar\Parser\DateParser;

/**
 * Main Calendar class
 *
 * Entry point for calendar operations
 */
final class Calendar
{
    private CalendarProfileInterface $profile;

    private DateParser $parser;

    private DateFormatter $formatter;

    /**
     * @param CalendarProfileInterface $profile Calendar profile
     */
    private function __construct(CalendarProfileInterface $profile)
    {
        $this->profile = $profile;
        $this->parser = new DateParser();
        $this->formatter = new DateFormatter();
    }

    /**
     * Create calendar from a pre-built profile
     *
     * @param string $profileName Profile identifier (e.g., 'gregorian', 'faerun')
     * @return self
     * @throws \InvalidArgumentException if profile not found
     */
    public static function fromProfile(string $profileName): self
    {
        $profile = ProfileRegistry::get($profileName);

        return new self($profile);
    }

    /**
     * Get calendar name
     *
     * @return string Calendar identifier
     */
    public function getName(): string
    {
        return $this->profile->getName();
    }

    /**
     * Get human-readable calendar name
     *
     * @return string Display name
     */
    public function getDisplayName(): string
    {
        return $this->profile->getDisplayName();
    }

    /**
     * Parse date string into TimePoint
     *
     * @param string $dateString Date to parse
     * @return TimePoint
     * @throws \Codryn\PHPCalendar\Exception\InvalidDateException if date cannot be parsed
     */
    public function parse(string $dateString): TimePoint
    {
        return $this->parser->parse($this, $dateString);
    }

    /**
     * Format TimePoint as string
     *
     * @param TimePoint $point Time point to format
     * @param string|null $pattern Optional format pattern (null = default)
     * @return string Formatted date string
     * @throws IncompatibleCalendarException if point from different calendar
     */
    public function format(TimePoint $point, ?string $pattern = null): string
    {
        if ($point->getCalendar() !== $this) {
            throw new IncompatibleCalendarException(
                'Cannot format TimePoint from different calendar',
            );
        }

        return $this->formatter->format($this, $point, $pattern);
    }

    /**
     * Get month names for this calendar
     *
     * @return array<int, string> Month names (1-indexed keys)
     */
    public function getMonthNames(): array
    {
        return $this->profile->getMonthNames();
    }

    /**
     * Get number of months in this calendar
     *
     * @return int Month count
     */
    public function getMonthCount(): int
    {
        return $this->profile->getMonthCount();
    }

    /**
     * Get days in specific month/year
     *
     * @param int $month Month (1-based)
     * @param int $year Year (negative for pre-epoch)
     * @return int Number of days
     */
    public function getDaysInMonth(int $month, int $year): int
    {
        return $this->profile->getDaysInMonth($month, $year);
    }

    /**
     * Check if year is leap year
     *
     * @param int $year Year to check
     * @return bool True if leap year
     */
    public function isLeapYear(int $year): bool
    {
        return $this->profile->isLeapYear($year);
    }

    /**
     * Calculate time difference between two points
     *
     * @param TimePoint $start Starting time point
     * @param TimePoint $end Ending time point
     * @return TimeSpan Duration from start to end
     * @throws IncompatibleCalendarException if points from different calendars
     */
    public function diff(TimePoint $start, TimePoint $end): TimeSpan
    {
        if ($start->getCalendar() !== $this || $end->getCalendar() !== $this) {
            throw new IncompatibleCalendarException(
                'Cannot calculate difference between TimePoints from different calendars',
            );
        }

        // Convert both dates to seconds since epoch for calculation
        $startSeconds = $this->toSeconds($start);
        $endSeconds = $this->toSeconds($end);

        $diffSeconds = $endSeconds - $startSeconds;
        $diffMicroseconds = $end->getMicrosecond() - $start->getMicrosecond();

        return TimeSpan::fromSeconds($diffSeconds, $diffMicroseconds);
    }

    /**
     * Convert TimePoint to seconds since epoch (simplified)
     *
     * @param TimePoint $point Time point to convert
     * @return int Seconds since epoch
     */
    private function toSeconds(TimePoint $point): int
    {
        // Simple approximation: calculate days since epoch then convert to seconds
        $days = 0;

        // Add days for years
        for ($y = 1; $y < $point->getYear(); $y++) {
            $days += $this->isLeapYear($y) ? 366 : 365;
        }

        // Add days for months
        for ($m = 1; $m < $point->getMonth(); $m++) {
            $days += $this->getDaysInMonth($m, $point->getYear());
        }

        // Add days
        $days += $point->getDay() - 1; // -1 because day 1 is the start

        $seconds = $days * 86400;
        $seconds += $point->getHour() * 3600;
        $seconds += $point->getMinute() * 60;
        $seconds += $point->getSecond();

        return $seconds;
    }

    /**
     * Get epoch notation (e.g., CE/BCE, DR, AR)
     *
     * @return array{before: string, after: string} Era labels
     */
    public function getEpochNotation(): array
    {
        return $this->profile->getEpochNotation();
    }
}
