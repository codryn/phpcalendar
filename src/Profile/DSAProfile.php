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
    use CopyrightNoticeTrait;

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
    public function getCopyrightNotice(): string
    {
        return $this->getUlissesSpieleCopyright('Das Schwarze Auge (The Dark Eye)', 'Das Schwarze Auge, The Dark Eye, Aventuria');
    }
}
