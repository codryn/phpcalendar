<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;

/**
 * Date converter for converting dates between multiple calendar systems
 *
 * Manages multiple calendar mappings and provides a unified API for date conversion
 */
final class DateConverter
{
    /**
     * @var array<string, CalendarMapping>
     */
    private array $mappings = [];

    /**
     * Register a calendar mapping
     *
     * @param CalendarMapping $mapping Calendar mapping to register
     * @return void
     */
    public function registerMapping(CalendarMapping $mapping): void
    {
        $sourceCalendar = $mapping->getSourceCalendar()->getName();
        $targetCalendar = $mapping->getTargetCalendar()->getName();

        $key = $this->getMappingKey($sourceCalendar, $targetCalendar);
        $this->mappings[$key] = $mapping;

        // Also register reverse mapping if bidirectional
        if ($mapping->getConfiguration()->isBidirectional()) {
            $reverseKey = $this->getMappingKey($targetCalendar, $sourceCalendar);
            $this->mappings[$reverseKey] = $mapping;
        }
    }

    /**
     * Check if conversion is possible between two calendars
     *
     * @param string $sourceCalendarName Source calendar name
     * @param string $targetCalendarName Target calendar name
     * @return bool True if conversion is possible
     */
    public function canConvert(string $sourceCalendarName, string $targetCalendarName): bool
    {
        $key = $this->getMappingKey($sourceCalendarName, $targetCalendarName);

        return isset($this->mappings[$key]);
    }

    /**
     * Convert a date from one calendar to another
     *
     * @param TimePoint $date Date to convert
     * @param Calendar $targetCalendar Target calendar
     * @return TimePoint Converted date in target calendar
     * @throws IncompatibleCalendarException if no mapping exists or conversion fails
     */
    public function convert(TimePoint $date, Calendar $targetCalendar): TimePoint
    {
        $sourceCalendarName = $date->getCalendar()->getName();
        $targetCalendarName = $targetCalendar->getName();

        $key = $this->getMappingKey($sourceCalendarName, $targetCalendarName);

        if (!isset($this->mappings[$key])) {
            throw new IncompatibleCalendarException(
                "No mapping found between '{$sourceCalendarName}' and '{$targetCalendarName}'",
            );
        }

        $mapping = $this->mappings[$key];

        // Check if we need to use forward or reverse conversion
        if ($mapping->getSourceCalendar()->getName() === $sourceCalendarName) {
            return $mapping->convert($date);
        }

        return $mapping->reverseConvert($date);
    }

    /**
     * Get all registered mappings
     *
     * @return array<string, CalendarMapping>
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }

    /**
     * Get a specific mapping
     *
     * @param string $sourceCalendarName Source calendar name
     * @param string $targetCalendarName Target calendar name
     * @return CalendarMapping|null Mapping if exists, null otherwise
     */
    public function getMapping(string $sourceCalendarName, string $targetCalendarName): ?CalendarMapping
    {
        $key = $this->getMappingKey($sourceCalendarName, $targetCalendarName);

        return $this->mappings[$key] ?? null;
    }

    /**
     * Generate a unique key for a calendar pair
     */
    private function getMappingKey(string $sourceCalendar, string $targetCalendar): string
    {
        return "{$sourceCalendar}->{$targetCalendar}";
    }
}
