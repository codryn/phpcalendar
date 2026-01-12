<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

/**
 * Configuration for calendar mapping between two calendar systems
 *
 * Defines correlation dates that allow converting dates between compatible calendars
 */
final class CalendarMappingConfiguration
{
    private string $sourceCalendarName;

    private string $targetCalendarName;

    /**
     * @var array{source: array{year: int, month: int, day: int}, target: array{year: int, month: int, day: int}}
     */
    private array $correlationDate;

    /**
     * @var array{min: array{year: int, month: int, day: int}|null, max: array{year: int, month: int, day: int}|null}
     */
    private array $validRange;

    /**
     * @param string $sourceCalendarName Source calendar identifier
     * @param string $targetCalendarName Target calendar identifier
     * @param array{source: array{year: int, month: int, day: int}, target: array{year: int, month: int, day: int}} $correlationDate Pair of corresponding dates in both calendars
     * @param array{min: array{year: int, month: int, day: int}|null, max: array{year: int, month: int, day: int}|null}|null $validRange Optional valid date range for conversions
     */
    public function __construct(
        string $sourceCalendarName,
        string $targetCalendarName,
        array $correlationDate,
        ?array $validRange = null,
    ) {
        $this->sourceCalendarName = $sourceCalendarName;
        $this->targetCalendarName = $targetCalendarName;
        $this->correlationDate = $correlationDate;
        $this->validRange = $validRange ?? ['min' => null, 'max' => null];
    }

    public function getSourceCalendarName(): string
    {
        return $this->sourceCalendarName;
    }

    public function getTargetCalendarName(): string
    {
        return $this->targetCalendarName;
    }

    /**
     * @return array{source: array{year: int, month: int, day: int}, target: array{year: int, month: int, day: int}}
     */
    public function getCorrelationDate(): array
    {
        return $this->correlationDate;
    }

    /**
     * @return array{min: array{year: int, month: int, day: int}|null, max: array{year: int, month: int, day: int}|null}
     */
    public function getValidRange(): array
    {
        return $this->validRange;
    }

    /**
     * Check if the mapping supports bidirectional conversion
     *
     * @return bool Always true for now (all mappings are bidirectional)
     */
    public function isBidirectional(): bool
    {
        return true;
    }
}
