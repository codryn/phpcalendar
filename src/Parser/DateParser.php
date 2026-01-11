<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Parser;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\TimePoint;
use Codryn\PHPCalendar\Exception\InvalidDateException;
use DateTimeImmutable;

/**
 * Date parser for converting strings to TimePoint objects
 */
final class DateParser
{
    /**
     * Parse date string to TimePoint
     *
     * @param Calendar $calendar Calendar context
     * @param string $dateString Date string to parse
     * @return TimePoint
     * @throws InvalidDateException if parsing fails
     */
    public function parse(Calendar $calendar, string $dateString): TimePoint
    {
        // Try to parse using PHP's native parser first
        try {
            $dt = new DateTimeImmutable($dateString);

            return new TimePoint(
                $calendar,
                (int) $dt->format('Y'),
                (int) $dt->format('n'),
                (int) $dt->format('j'),
                (int) $dt->format('G'),
                (int) $dt->format('i'),
                (int) $dt->format('s'),
                (int) $dt->format('u'),
            );
        } catch (\Exception $e) {
            throw new InvalidDateException("Failed to parse date: {$dateString}", 0, $e);
        }
    }
}
