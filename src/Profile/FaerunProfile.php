<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

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
        return 'Faerûn (Harptos Calendar)';
    }

    /**
     * @inheritDoc
     */
    public function getMonthNames(): array
    {
        return [
            1 => 'Hammer',
            2 => 'Alturiak',
            3 => 'Ches',
            4 => 'Tarsakh',
            5 => 'Mirtul',
            6 => 'Kythorn',
            7 => 'Flamerule',
            8 => 'Eleasis',
            9 => 'Eleint',
            10 => 'Marpenoth',
            11 => 'Uktar',
            12 => 'Nightal',
        ];
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
        return [
            'before' => 'Before DR',
            'after' => 'DR',
        ];
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
}
