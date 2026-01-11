<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Parser;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\TimePoint;

/**
 * Date formatter for converting TimePoint to strings
 */
final class DateFormatter
{
    /**
     * Format TimePoint as string
     *
     * @param Calendar $calendar Calendar context
     * @param TimePoint $point Time point to format
     * @param string|null $pattern Format pattern (null = default)
     * @return string Formatted date string
     */
    public function format(Calendar $calendar, TimePoint $point, ?string $pattern = null): string
    {
        if ($pattern === null) {
            $pattern = 'F j, Y'; // Default: "December 25, 2024"
        }

        // Build date string using calendar month names
        $monthNames = $calendar->getMonthNames();
        $monthName = $monthNames[$point->getMonth()];

        // Direct replacement with proper values
        // Order matters! Replace longer patterns before shorter ones to avoid conflicts
        $replacements = [
            'Y' => (string) $point->getYear(),
            'y' => \substr((string) $point->getYear(), -2),
            'F' => $monthName,
            'm' => \str_pad((string) $point->getMonth(), 2, '0', STR_PAD_LEFT),
            'n' => (string) $point->getMonth(),
            'd' => \str_pad((string) $point->getDay(), 2, '0', STR_PAD_LEFT),
            'j' => (string) $point->getDay(),
            'H' => \str_pad((string) $point->getHour(), 2, '0', STR_PAD_LEFT),
            'i' => \str_pad((string) $point->getMinute(), 2, '0', STR_PAD_LEFT),
            's' => \str_pad((string) $point->getSecond(), 2, '0', STR_PAD_LEFT),
        ];

        // Use strtr for safe simultaneous replacement
        return \strtr($pattern, $replacements);
    }
}
