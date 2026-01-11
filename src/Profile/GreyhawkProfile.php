<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Locale\GreyhawkTranslations;

/**
 * Greyhawk Calendar Profile
 *
 * Common Year (CY) calendar for the World of Greyhawk
 */
final class GreyhawkProfile extends AbstractCalendarProfile
{
    use CopyrightNoticeTrait;

    public function getName(): string
    {
        return 'greyhawk';
    }

    public function getDisplayName(): string
    {
        return GreyhawkTranslations::getDisplayName($this->locale);
    }

    public function getMonthNames(): array
    {
        return GreyhawkTranslations::getMonthNames($this->locale);
    }

    public function getDaysInMonth(int $month, int $year): int
    {
        // Festivals (1, 5, 9, 13) have 7 days
        // Regular months (2-4, 6-8, 10-12, 14-16) have 28 days
        if (\in_array($month, [1, 5, 9, 13], true)) {
            return 7; // Festival weeks
        }

        return 28; // Regular months
    }

    public function isLeapYear(int $year): bool
    {
        // No leap years in standard Greyhawk calendar
        return false;
    }

    public function getEpochNotation(): array
    {
        return GreyhawkTranslations::getEpochNotation($this->locale);
    }

    public function getFormatPatterns(): array
    {
        return [
            'd F Y CY',
            'F d, Y CY',
            'Y-m-d',
        ];
    }

    public function getMetadata(): array
    {
        return [
            'source' => 'World of Greyhawk Campaign Setting',
            'setting' => 'Oerth (Greyhawk)',
            'description' => 'Common Year calendar with 12 months of 28 days plus 4 festival weeks (364 days total)',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCopyrightNotice(): ?string
    {
        return $this->getWizardsOfTheCoastCopyright('Greyhawk', 'Greyhawk');
    }
}
