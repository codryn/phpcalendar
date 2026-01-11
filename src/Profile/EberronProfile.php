<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Locale\EberronTranslations;

/**
 * Eberron Calendar Profile
 *
 * Galifar Calendar (YK - Years since Kingdom founding)
 */
final class EberronProfile extends AbstractCalendarProfile
{
    public function getName(): string
    {
        return 'eberron';
    }

    public function getDisplayName(): string
    {
        return EberronTranslations::getDisplayName($this->locale);
    }

    public function getMonthNames(): array
    {
        return EberronTranslations::getMonthNames($this->locale);
    }

    public function getDaysInMonth(int $month, int $year): int
    {
        // All months have exactly 28 days
        return 28;
    }

    public function isLeapYear(int $year): bool
    {
        // No leap years in Eberron
        return false;
    }

    public function getEpochNotation(): array
    {
        return EberronTranslations::getEpochNotation($this->locale);
    }

    public function getFormatPatterns(): array
    {
        return [
            'd F Y YK',
            'F d, Y YK',
            'Y-m-d',
        ];
    }

    public function getMetadata(): array
    {
        return [
            'source' => 'Eberron Campaign Setting (D&D)',
            'setting' => 'Eberron',
            'description' => 'Galifar Calendar with 12 months of 28 days each (336 days per year)',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCopyrightNotice(): ?string
    {
        return 'The calendar names, month names, and associated terminology from the Eberron '
            . 'setting are the property of Wizards of the Coast. This calendar implementation is provided '
            . 'for non-commercial use only to help game masters and players keep track of their campaigns. '
            . 'Dungeons & Dragons, D&D, Eberron, and all related trademarks are property of '
            . 'Wizards of the Coast LLC.';
    }
}
