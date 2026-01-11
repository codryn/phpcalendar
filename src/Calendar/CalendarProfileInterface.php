<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

/**
 * Interface for calendar profile implementations
 *
 * Defines the contract for calendar rules and metadata
 */
interface CalendarProfileInterface
{
    /**
     * Get profile identifier (e.g., 'gregorian', 'faerun')
     *
     * @return string Profile name
     */
    public function getName(): string;

    /**
     * Get human-readable calendar name
     *
     * @return string Display name
     */
    public function getDisplayName(): string;

    /**
     * Get month names in order
     *
     * @return array<int, string> Month names (1-indexed keys)
     */
    public function getMonthNames(): array;

    /**
     * Get number of months in calendar
     *
     * @return int Month count
     */
    public function getMonthCount(): int;

    /**
     * Get days in specific month/year
     *
     * @param int $month Month (1-based)
     * @param int $year Year (negative for pre-epoch)
     * @return int Number of days
     */
    public function getDaysInMonth(int $month, int $year): int;

    /**
     * Check if year is leap year
     *
     * @param int $year Year to check
     * @return bool True if leap year
     */
    public function isLeapYear(int $year): bool;

    /**
     * Get epoch notation (e.g., CE/BCE, DR, AR)
     *
     * @return array{before: string, after: string} Era labels
     */
    public function getEpochNotation(): array;

    /**
     * Get acceptable date format patterns
     *
     * @return array<int, string> Format patterns
     */
    public function getFormatPatterns(): array;

    /**
     * Get calendar metadata (source, setting info)
     *
     * @return array<string, mixed> Metadata
     */
    public function getMetadata(): array;

    /**
     * Convert date components to Unix timestamp
     *
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
    ): float;

    /**
     * Convert Unix timestamp to date components
     *
     * @param float $seconds Seconds since epoch with microseconds
     * @return array{year: int, month: int, day: int, hour: int, minute: int, second: int, microsecond: int}
     */
    public function secondsToDate(float $seconds): array;
}
