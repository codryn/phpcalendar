<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;
use Codryn\PHPCalendar\Exception\InvalidCalendarConfigException;
use Codryn\PHPCalendar\Parser\DateFormatter;
use Codryn\PHPCalendar\Parser\DateParser;
use Codryn\PHPCalendar\Profile\CustomProfile;
use Codryn\PHPCalendar\Validator\CalendarValidator;

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
     * Create calendar from custom configuration
     *
     * @param CalendarConfiguration $config Custom calendar parameters
     * @return self
     * @throws InvalidCalendarConfigException if configuration invalid
     */
    public static function fromConfiguration(CalendarConfiguration $config): self
    {
        $validator = new CalendarValidator();
        $validator->validate($config);

        $profile = new CustomProfile($config);

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
     * @return int Number of days in month
     */
    public function getDaysInMonth(int $month, int $year): int
    {
        return $this->profile->getDaysInMonth($month, $year);
    }

    /**
     * Check if given year is a leap year
     *
     * @param int $year Year to check
     * @return bool True if leap year
     */
    public function isLeapYear(int $year): bool
    {
        return $this->profile->isLeapYear($year);
    }

    /**
     * Calculate time difference between two TimePoints
     *
     * @param TimePoint $start Start time
     * @param TimePoint $end End time
     * @return TimeSpan Time difference
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
        // Use the calendar profile's dateToSeconds method which handles nameless days
        $totalSeconds = $this->profile->dateToSeconds(
            $point->getYear(),
            $point->getMonth(),
            $point->getDay(),
            $point->getHour(),
            $point->getMinute(),
            $point->getSecond(),
            $point->getMicrosecond()
        );

        return (int) $totalSeconds;
    }

    /**
     * Get epoch notation
     *
     * @return array{before: string, after: string} Epoch notation
     */
    public function getEpochNotation(): array
    {
        return $this->profile->getEpochNotation();
    }

    /**
     * Get the profile for calendar calculations
     *
     * @internal
     * @return CalendarProfileInterface
     */
    public function getProfile(): CalendarProfileInterface
    {
        return $this->profile;
    }

    /**
     * Convert date components to Unix timestamp
     *
     * @internal
     * @param int $year Year
     * @param int $month Month (1-based)
     * @param int $day Day (1-based)
     * @param int $hour Hour (0-23)
     * @param int $minute Minute (0-59)
     * @param int $second Second (0-59)
     * @param int $microsecond Microsecond (0-999999)
     * @return float Seconds since epoch with microseconds
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
        return $this->profile->dateToSeconds($year, $month, $day, $hour, $minute, $second, $microsecond);
    }

    /**
     * Convert Unix timestamp to date components
     *
     * @internal
     * @param float $seconds Seconds since epoch with microseconds
     * @return array{year: int, month: int, day: int, hour: int, minute: int, second: int, microsecond: int}
     */
    public function secondsToDate(float $seconds): array
    {
        return $this->profile->secondsToDate($seconds);
    }
}
