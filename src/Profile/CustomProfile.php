<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Calendar\CalendarConfiguration;

/**
 * Custom profile adapter wrapping CalendarConfiguration
 */
final class CustomProfile extends AbstractCalendarProfile
{
    private CalendarConfiguration $config;

    public function __construct(CalendarConfiguration $config)
    {
        $this->config = $config;
    }

    public function getName(): string
    {
        return $this->config->getName();
    }

    public function getDisplayName(): string
    {
        return $this->config->getDisplayName();
    }

    public function getMonthNames(): array
    {
        return $this->config->getMonthNames();
    }

    public function getDaysInMonth(int $month, int $year): int
    {
        $daysPerMonth = $this->config->getDaysPerMonth();

        return $daysPerMonth[$month] ?? 30;
    }

    public function isLeapYear(int $year): bool
    {
        $leapYearRule = $this->config->getLeapYearRule();

        if ($leapYearRule === null) {
            return false;
        }

        return $leapYearRule($year);
    }

    public function getEpochNotation(): array
    {
        return $this->config->getEpochNotation();
    }

    public function getFormatPatterns(): array
    {
        return $this->config->getFormatPatterns();
    }

    public function getMetadata(): array
    {
        return [
            'source' => 'Custom',
            'setting' => 'User-defined',
            'description' => 'Custom calendar configuration',
        ];
    }

    public function getNamelessDays(): array
    {
        return $this->config->getNamelessDays();
    }
}
