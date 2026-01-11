<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

/**
 * Immutable time span value object
 *
 * Represents duration between two TimePoints
 */
final class TimeSpan
{
    private int $seconds;

    private int $microseconds;

    /**
     * @param int $seconds Total seconds (can be negative)
     * @param int $microseconds Additional microseconds (0-999999)
     */
    private function __construct(int $seconds, int $microseconds = 0)
    {
        $this->seconds = $seconds;
        $this->microseconds = $microseconds;
    }

    /**
     * Create TimeSpan from seconds
     *
     * @param int $seconds Total seconds
     * @param int $microseconds Additional microseconds (default 0)
     * @return self
     */
    public static function fromSeconds(int $seconds, int $microseconds = 0): self
    {
        return new self($seconds, $microseconds);
    }

    /**
     * Get total seconds
     *
     * @return int Total seconds (can be negative)
     */
    public function getTotalSeconds(): int
    {
        return $this->seconds;
    }

    /**
     * Get microseconds component
     *
     * @return int Microseconds (0-999999)
     */
    public function getMicroseconds(): int
    {
        return $this->microseconds;
    }

    /**
     * Get total days
     *
     * @return int Total days (rounded down)
     */
    public function getTotalDays(): int
    {
        return \intdiv($this->seconds, 86400);
    }

    /**
     * Get total hours
     *
     * @return int Total hours (rounded down)
     */
    public function getTotalHours(): int
    {
        return \intdiv($this->seconds, 3600);
    }

    /**
     * Get total minutes
     *
     * @return int Total minutes (rounded down)
     */
    public function getTotalMinutes(): int
    {
        return \intdiv($this->seconds, 60);
    }

    /**
     * Check if span is negative
     *
     * @return bool True if represents past
     */
    public function isNegative(): bool
    {
        return $this->seconds < 0;
    }

    /**
     * Get absolute value
     *
     * @return self New TimeSpan with absolute value
     */
    public function abs(): self
    {
        return new self(\abs($this->seconds), \abs($this->microseconds));
    }

    /**
     * Negate the span
     *
     * @return self New TimeSpan with reversed direction
     */
    public function negate(): self
    {
        return new self(-$this->seconds, -$this->microseconds);
    }
}
