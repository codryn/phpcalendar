<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

/**
 * Configuration for custom calendars
 *
 * Immutable value object defining custom calendar parameters
 */
final class CalendarConfiguration
{
    private string $name;

    private string $displayName;

    /** @var array<int, string> */
    private array $monthNames;

    /** @var array<int, int> */
    private array $daysPerMonth;

    /** @var callable(int): bool|null */
    private $leapYearRule;

    /** @var array{before: string, after: string} */
    private array $epochNotation;

    /** @var array<int, string> */
    private array $formatPatterns;

    /** @var array<int, array{position: int, names: array<int, string>, leap: bool}> */
    private array $namelessDays;

    /**
     * @param string $name Calendar identifier
     * @param string $displayName Human-readable name
     * @param array<int, string> $monthNames Month names (1-indexed)
     * @param array<int, int> $daysPerMonth Days in each month
     * @param callable(int): bool|null $leapYearRule Function to determine leap years
     * @param array{before: string, after: string} $epochNotation Era labels
     * @param array<int, string> $formatPatterns Date format patterns
     * @param array<int, array{position: int, names: array<int, string>, leap: bool}> $namelessDays Nameless days configuration
     */
    public function __construct(
        string $name,
        string $displayName,
        array $monthNames,
        array $daysPerMonth,
        ?callable $leapYearRule = null,
        array $epochNotation = ['before' => 'BE', 'after' => 'AE'],
        array $formatPatterns = ['F j, Y'],
        array $namelessDays = [],
    ) {
        $this->name = $name;
        $this->displayName = $displayName;
        $this->monthNames = $monthNames;
        $this->daysPerMonth = $daysPerMonth;
        $this->leapYearRule = $leapYearRule;
        $this->epochNotation = $epochNotation;
        $this->formatPatterns = $formatPatterns;
        $this->namelessDays = $namelessDays;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return array<int, string>
     */
    public function getMonthNames(): array
    {
        return $this->monthNames;
    }

    /**
     * @return array<int, int>
     */
    public function getDaysPerMonth(): array
    {
        return $this->daysPerMonth;
    }

    /**
     * @return callable(int): bool|null
     */
    public function getLeapYearRule(): ?callable
    {
        return $this->leapYearRule;
    }

    /**
     * @return array{before: string, after: string}
     */
    public function getEpochNotation(): array
    {
        return $this->epochNotation;
    }

    /**
     * @return array<int, string>
     */
    public function getFormatPatterns(): array
    {
        return $this->formatPatterns;
    }

    /**
     * @return array<int, array{position: int, names: array<int, string>, leap: bool}>
     */
    public function getNamelessDays(): array
    {
        return $this->namelessDays;
    }
}
