<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Validator;

use Codryn\PHPCalendar\Calendar\CalendarConfiguration;
use Codryn\PHPCalendar\Exception\InvalidCalendarConfigException;

/**
 * Validator for CalendarConfiguration
 *
 * Enforces calendar configuration rules
 */
final class CalendarValidator
{
    /**
     * Validate calendar configuration
     *
     * @param CalendarConfiguration $config Configuration to validate
     * @throws InvalidCalendarConfigException if invalid
     */
    public function validate(CalendarConfiguration $config): void
    {
        $this->validateName($config->getName());
        $this->validateDisplayName($config->getDisplayName());
        $this->validateMonthNames($config->getMonthNames());
        $this->validateDaysPerMonth($config->getDaysPerMonth(), $config->getMonthNames());
        $this->validateEpochNotation($config->getEpochNotation());
        $this->validateFormatPatterns($config->getFormatPatterns());
    }

    /**
     * @param string $name
     * @throws InvalidCalendarConfigException
     */
    private function validateName(string $name): void
    {
        if (empty($name)) {
            throw new InvalidCalendarConfigException('Calendar name cannot be empty');
        }

        if (!\preg_match('/^[a-zA-Z0-9\-_]+$/', $name)) {
            throw new InvalidCalendarConfigException(
                'Calendar name must contain only alphanumeric characters, hyphens, and underscores',
            );
        }
    }

    /**
     * @param string $displayName
     * @throws InvalidCalendarConfigException
     */
    private function validateDisplayName(string $displayName): void
    {
        if (empty($displayName)) {
            throw new InvalidCalendarConfigException('Display name cannot be empty');
        }
    }

    /**
     * @param array<int, string> $monthNames
     * @throws InvalidCalendarConfigException
     */
    private function validateMonthNames(array $monthNames): void
    {
        if (empty($monthNames)) {
            throw new InvalidCalendarConfigException('Calendar must have at least one month');
        }

        foreach ($monthNames as $index => $name) {
            if (empty($name)) {
                throw new InvalidCalendarConfigException("Month name at index {$index} cannot be empty");
            }
        }
    }

    /**
     * @param array<int, int> $daysPerMonth
     * @param array<int, string> $monthNames
     * @throws InvalidCalendarConfigException
     */
    private function validateDaysPerMonth(array $daysPerMonth, array $monthNames): void
    {
        if (\count($daysPerMonth) !== \count($monthNames)) {
            throw new InvalidCalendarConfigException(
                'Number of daysPerMonth entries must match number of months',
            );
        }

        foreach ($daysPerMonth as $index => $days) {
            if ($days < 1) {
                throw new InvalidCalendarConfigException("Days in month {$index} must be at least 1");
            }
        }
    }

    /**
     * @param array{before: string, after: string} $epochNotation
     * @throws InvalidCalendarConfigException
     */
    private function validateEpochNotation(array $epochNotation): void
    {
        if (empty($epochNotation['before']) || empty($epochNotation['after'])) {
            throw new InvalidCalendarConfigException('Epoch notation values cannot be empty');
        }
    }

    /**
     * @param array<int, string> $formatPatterns
     * @throws InvalidCalendarConfigException
     */
    private function validateFormatPatterns(array $formatPatterns): void
    {
        if (empty($formatPatterns)) {
            throw new InvalidCalendarConfigException('Calendar must have at least one format pattern');
        }
    }
}
