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

        // Intercalary days retain an unambiguous numeric representation
        // (e.g. 1492-07-32 = Shieldmeet) and also have a display name.
        $festivalName = null;
        $regularDays = $calendar->getDaysInMonth($point->getMonth(), $point->getYear());
        if ($point->getDay() > $regularDays) {
            $ordinal = $point->getDay() - $regularDays;
            foreach ($calendar->getProfile()->getNamelessDays() as $group) {
                if ($group['position'] !== $point->getMonth()) {
                    continue;
                }
                $festivalName = $group['names'][$ordinal] ?? null;
                if ($festivalName === null && $group['leap'] && $calendar->isLeapYear($point->getYear())
                    && $ordinal === \count($group['names']) + 1) {
                    $festivalName = $group['leapName'] ?? 'Leap Day';
                }
                break;
            }
        }
        $namedFestival = $festivalName !== null && \str_contains($pattern, 'F');

        // Named patterns show the festival name without a spurious day number.
        // Numeric patterns continue to expose the reversible overflow date.
        $replacements = [
            'Y' => (string) $point->getYear(),
            'y' => \substr((string) $point->getYear(), -2),
            'F' => $namedFestival ? $festivalName : $monthName,
            'm' => $namedFestival ? '' : \str_pad((string) $point->getMonth(), 2, '0', STR_PAD_LEFT),
            'n' => $namedFestival ? '' : (string) $point->getMonth(),
            'd' => $namedFestival ? '' : \str_pad((string) $point->getDay(), 2, '0', STR_PAD_LEFT),
            'j' => $namedFestival ? '' : (string) $point->getDay(),
            'H' => \str_pad((string) $point->getHour(), 2, '0', STR_PAD_LEFT),
            'i' => \str_pad((string) $point->getMinute(), 2, '0', STR_PAD_LEFT),
            's' => \str_pad((string) $point->getSecond(), 2, '0', STR_PAD_LEFT),
        ];
        // Use strtr for safe simultaneous replacement
        $formatted = \strtr($pattern, $replacements);
        if ($namedFestival) {
            $formatted = \trim(\preg_replace('/\s+([,.])/', '$1', $formatted) ?? $formatted);
        }

        return $formatted;
    }
}
