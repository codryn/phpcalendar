<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Locale\GregorianTranslations;

/**
 * Gregorian Calendar Profile
 *
 * Standard modern calendar with leap year rules
 */
final class GregorianProfile extends AbstractCalendarProfile
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'gregorian';
    }

    /**
     * @inheritDoc
     */
    public function getDisplayName(): string
    {
        return GregorianTranslations::getDisplayName($this->locale);
    }

    /**
     * @inheritDoc
     */
    public function getMonthNames(): array
    {
        return GregorianTranslations::getMonthNames($this->locale);
    }

    /**
     * @inheritDoc
     */
    public function getDaysInMonth(int $month, int $year): int
    {
        $daysPerMonth = [
            1 => 31, 2 => 28, 3 => 31, 4 => 30,
            5 => 31, 6 => 30, 7 => 31, 8 => 31,
            9 => 30, 10 => 31, 11 => 30, 12 => 31,
        ];

        $days = $daysPerMonth[$month] ?? 30;

        // Add leap day for February
        if ($month === 2 && $this->isLeapYear($year)) {
            $days = 29;
        }

        return $days;
    }

    /**
     * @inheritDoc
     */
    public function isLeapYear(int $year): bool
    {
        // Leap year if divisible by 4, except for years divisible by 100
        // unless also divisible by 400
        if ($year % 400 === 0) {
            return true;
        }

        if ($year % 100 === 0) {
            return false;
        }

        return $year % 4 === 0;
    }

    /**
     * @inheritDoc
     */
    public function getEpochNotation(): array
    {
        return GregorianTranslations::getEpochNotation($this->locale);
    }

    /**
     * @inheritDoc
     */
    public function getFormatPatterns(): array
    {
        return [
            'F j, Y',     // December 25, 2024
            'Y-m-d',      // 2024-12-25
            'd/m/Y',      // 25/12/2024
            'm/d/Y',      // 12/25/2024
        ];
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'source' => 'International standard',
            'setting' => 'Real world',
            'description' => 'Standard Gregorian calendar used internationally',
        ];
    }
}
