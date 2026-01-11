<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Locale\FaerunTranslations;

/**
 * Faerûn (Harptos Calendar) Profile
 *
 * Calendar system used in the Forgotten Realms (D&D)
 * 12 months of 30 days each, plus 5 annual festivals
 * Shieldmeet (leap day) every 4 years
 */
final class FaerunProfile extends AbstractCalendarProfile
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'faerun';
    }

    /**
     * @inheritDoc
     */
    public function getDisplayName(): string
    {
        return FaerunTranslations::getDisplayName($this->locale);
    }

    /**
     * @inheritDoc
     */
    public function getMonthNames(): array
    {
        return FaerunTranslations::getMonthNames($this->locale);
    }

    /**
     * @inheritDoc
     */
    public function getDaysInMonth(int $month, int $year): int
    {
        // All months have exactly 30 days in Harptos
        return 30;
    }

    /**
     * @inheritDoc
     */
    public function isLeapYear(int $year): bool
    {
        // Shieldmeet occurs every 4 years
        return $year % 4 === 0;
    }

    /**
     * @inheritDoc
     */
    public function getEpochNotation(): array
    {
        return FaerunTranslations::getEpochNotation($this->locale);
    }

    /**
     * @inheritDoc
     */
    public function getFormatPatterns(): array
    {
        return [
            'j F Y \D\R',   // 15 Mirtul 1492 DR
        ];
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'source' => 'Forgotten Realms Campaign Setting',
            'setting' => 'Forgotten Realms (Dungeons & Dragons)',
            'description' => 'Harptos Calendar with 12 months of 30 days plus annual festivals',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNamelessDays(): array
    {
        return FaerunTranslations::getNamelessDays($this->locale);
    }

    /**
     * @inheritDoc
     */
    public function getCopyrightNotice(): ?string
    {
        return 'The calendar names, month names, and associated terminology from the Forgotten Realms '
            . 'setting are the property of Wizards of the Coast. This calendar implementation is provided '
            . 'for non-commercial use only to help game masters and players keep track of their campaigns. '
            . 'Dungeons & Dragons, D&D, Forgotten Realms, and all related trademarks are property of '
            . 'Wizards of the Coast LLC.';
    }
}
