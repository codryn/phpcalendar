<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Locale\DSATranslations;

/**
 * Das Schwarze Auge (The Dark Eye) Calendar Profile
 *
 * Aventurian calendar with Bosparans Fall (BF) reckoning
 */
final class DSAProfile extends AbstractCalendarProfile
{
    public function getName(): string
    {
        return 'dsa';
    }

    public function getDisplayName(): string
    {
        return DSATranslations::getDisplayName($this->locale);
    }

    public function getMonthNames(): array
    {
        return DSATranslations::getMonthNames($this->locale);
    }

    public function getDaysInMonth(int $month, int $year): int
    {
        // All months have 30 days (+ 5 nameless days at year end, not counted in months)
        return 30;
    }

    public function isLeapYear(int $year): bool
    {
        // No leap years in standard Aventurian calendar
        return false;
    }

    public function getEpochNotation(): array
    {
        return DSATranslations::getEpochNotation($this->locale);
    }

    public function getFormatPatterns(): array
    {
        return [
            'd. F Y BF',
            'F d, Y BF',
            'Y-m-d',
        ];
    }

    public function getMetadata(): array
    {
        return [
            'source' => 'Das Schwarze Auge (The Dark Eye) RPG',
            'setting' => 'Aventuria',
            'description' => 'Bosparans Fall calendar with 12 months of 30 days plus 5 nameless days',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNamelessDays(): array
    {
        return DSATranslations::getNamelessDays($this->locale);
    }

    /**
     * @inheritDoc
     */
    public function getCopyrightNotice(): ?string
    {
        return 'The calendar names, month names, and associated terminology from Das Schwarze Auge '
            . '(The Dark Eye) are the property of Ulisses Spiele. This calendar implementation is provided '
            . 'for non-commercial use only to help game masters and players keep track of their campaigns. '
            . 'Das Schwarze Auge, The Dark Eye, Aventuria, and all related trademarks are property of '
            . 'Ulisses Spiele GmbH.';
    }
}
