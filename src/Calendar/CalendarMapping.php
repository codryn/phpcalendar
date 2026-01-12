<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;
use Codryn\PHPCalendar\Exception\InvalidDateException;

/**
 * Calendar mapping implementation
 *
 * Handles date conversion between two calendar systems using correlation dates
 */
final class CalendarMapping
{
    private CalendarMappingConfiguration $config;

    private Calendar $sourceCalendar;

    private Calendar $targetCalendar;

    private TimePoint $sourceCorrelation;

    private TimePoint $targetCorrelation;

    /**
     * @param CalendarMappingConfiguration $config Mapping configuration
     * @param Calendar $sourceCalendar Source calendar instance
     * @param Calendar $targetCalendar Target calendar instance
     * @throws InvalidDateException if correlation dates are invalid
     * @throws IncompatibleCalendarException if calendar names don't match configuration
     */
    public function __construct(
        CalendarMappingConfiguration $config,
        Calendar $sourceCalendar,
        Calendar $targetCalendar,
    ) {
        $this->validateCalendars($config, $sourceCalendar, $targetCalendar);

        $this->config = $config;
        $this->sourceCalendar = $sourceCalendar;
        $this->targetCalendar = $targetCalendar;

        // Create correlation TimePoints
        $sourceDate = $config->getCorrelationDate()['source'];
        $targetDate = $config->getCorrelationDate()['target'];

        $this->sourceCorrelation = new TimePoint(
            $sourceCalendar,
            $sourceDate['year'],
            $sourceDate['month'],
            $sourceDate['day'],
        );

        $this->targetCorrelation = new TimePoint(
            $targetCalendar,
            $targetDate['year'],
            $targetDate['month'],
            $targetDate['day'],
        );
    }

    /**
     * Convert a date from source to target calendar
     *
     * @param TimePoint $sourceDate Date in source calendar
     * @return TimePoint Date in target calendar
     * @throws IncompatibleCalendarException if date is from wrong calendar
     * @throws InvalidDateException if date is outside valid range or result is invalid
     */
    public function convert(TimePoint $sourceDate): TimePoint
    {
        if ($sourceDate->getCalendar() !== $this->sourceCalendar) {
            throw new IncompatibleCalendarException(
                'TimePoint must be from the source calendar',
            );
        }

        $this->validateDateInRange($sourceDate);

        // Calculate time difference from correlation point
        $diff = $this->sourceCalendar->diff($this->sourceCorrelation, $sourceDate);

        // Apply the same difference to target correlation
        return $this->targetCorrelation->add($diff);
    }

    /**
     * Convert a date from target to source calendar (reverse conversion)
     *
     * @param TimePoint $targetDate Date in target calendar
     * @return TimePoint Date in source calendar
     * @throws IncompatibleCalendarException if date is from wrong calendar or mapping not bidirectional
     * @throws InvalidDateException if result is invalid
     */
    public function reverseConvert(TimePoint $targetDate): TimePoint
    {
        if (!$this->config->isBidirectional()) {
            throw new IncompatibleCalendarException(
                'This mapping does not support reverse conversion',
            );
        }

        if ($targetDate->getCalendar() !== $this->targetCalendar) {
            throw new IncompatibleCalendarException(
                'TimePoint must be from the target calendar',
            );
        }

        // Calculate time difference from target correlation point
        $diff = $this->targetCalendar->diff($this->targetCorrelation, $targetDate);

        // Apply the same difference to source correlation
        return $this->sourceCorrelation->add($diff);
    }

    public function getSourceCalendar(): Calendar
    {
        return $this->sourceCalendar;
    }

    public function getTargetCalendar(): Calendar
    {
        return $this->targetCalendar;
    }

    public function getConfiguration(): CalendarMappingConfiguration
    {
        return $this->config;
    }

    /**
     * Validate that calendars match the configuration
     *
     * @throws IncompatibleCalendarException if calendar names don't match
     */
    private function validateCalendars(
        CalendarMappingConfiguration $config,
        Calendar $sourceCalendar,
        Calendar $targetCalendar,
    ): void {
        if ($sourceCalendar->getName() !== $config->getSourceCalendarName()) {
            throw new IncompatibleCalendarException(
                "Source calendar '{$sourceCalendar->getName()}' does not match configuration '{$config->getSourceCalendarName()}'",
            );
        }

        if ($targetCalendar->getName() !== $config->getTargetCalendarName()) {
            throw new IncompatibleCalendarException(
                "Target calendar '{$targetCalendar->getName()}' does not match configuration '{$config->getTargetCalendarName()}'",
            );
        }
    }

    /**
     * Validate that date is within the valid range
     *
     * @throws InvalidDateException if date is outside valid range
     */
    private function validateDateInRange(TimePoint $date): void
    {
        $validRange = $this->config->getValidRange();

        if ($validRange['min'] !== null) {
            $minDate = $validRange['min'];
            $min = new TimePoint(
                $this->sourceCalendar,
                $minDate['year'],
                $minDate['month'],
                $minDate['day'],
            );

            $diff = $this->sourceCalendar->diff($min, $date);
            if ($diff->getTotalSeconds() < 0) {
                throw new InvalidDateException(
                    'Date is before minimum valid date for conversion',
                );
            }
        }

        if ($validRange['max'] !== null) {
            $maxDate = $validRange['max'];
            $max = new TimePoint(
                $this->sourceCalendar,
                $maxDate['year'],
                $maxDate['month'],
                $maxDate['day'],
            );

            $diff = $this->sourceCalendar->diff($date, $max);
            if ($diff->getTotalSeconds() < 0) {
                throw new InvalidDateException(
                    'Date is after maximum valid date for conversion',
                );
            }
        }
    }
}
