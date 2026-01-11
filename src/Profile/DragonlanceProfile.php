<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Locale\DragonlanceTranslations;

/**
 * Dragonlance Calendar Profile
 *
 * Krynn calendar with AC/PC (After/Pre Cataclysm) reckoning
 */
final class DragonlanceProfile extends AbstractCalendarProfile
{
    private const DAYS_PER_MONTH = [
        1 => 31,  // Winter Deep
        2 => 28,  // Winter Wane
        3 => 31,  // Spring Dawning
        4 => 30,  // Spring Rain
        5 => 31,  // Spring Bloom
        6 => 30,  // Summer Home
        7 => 31,  // Summer Run
        8 => 31,  // Summer End
        9 => 30,  // Autumn Harvest
        10 => 31, // Autumn Twilight
        11 => 30, // Autumn Dark
        12 => 31, // Winter Come
    ];

    public function getName(): string
    {
        return 'dragonlance';
    }

    public function getDisplayName(): string
    {
        return DragonlanceTranslations::getDisplayName($this->locale);
    }

    public function getMonthNames(): array
    {
        return DragonlanceTranslations::getMonthNames($this->locale);
    }

    public function getDaysInMonth(int $month, int $year): int
    {
        return self::DAYS_PER_MONTH[$month] ?? 30;
    }

    public function isLeapYear(int $year): bool
    {
        // Using standard Gregorian leap year logic for Krynn
        if ($year % 400 === 0) {
            return true;
        }
        if ($year % 100 === 0) {
            return false;
        }

        return $year % 4 === 0;
    }

    public function getEpochNotation(): array
    {
        return DragonlanceTranslations::getEpochNotation($this->locale);
    }

    public function getFormatPatterns(): array
    {
        return [
            'd F Y AC',
            'F d, Y AC',
            'Y-m-d',
        ];
    }

    public function getMetadata(): array
    {
        return [
            'source' => 'Dragonlance Campaign Setting',
            'setting' => 'Krynn',
            'description' => 'Krynn calendar with varying month lengths, AC/PC reckoning from the Cataclysm',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCopyrightNotice(): ?string
    {
        return 'The calendar names, month names, and associated terminology from the Dragonlance '
            . 'setting are the property of Wizards of the Coast. This calendar implementation is provided '
            . 'for non-commercial use only to help game masters and players keep track of their campaigns. '
            . 'Dungeons & Dragons, D&D, Dragonlance, Krynn, and all related trademarks are property of '
            . 'Wizards of the Coast LLC.';
    }
}
