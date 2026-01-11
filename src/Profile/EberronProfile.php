<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

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
        return 'Eberron (Galifar Calendar)';
    }

    public function getMonthNames(): array
    {
        return [
            1 => 'Zarantyr',
            2 => 'Olarune',
            3 => 'Therendor',
            4 => 'Eyre',
            5 => 'Dravago',
            6 => 'Nymm',
            7 => 'Lharvion',
            8 => 'Barrakas',
            9 => 'Rhaan',
            10 => 'Sypheros',
            11 => 'Aryth',
            12 => 'Vult',
        ];
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
        return [
            'before' => 'Before YK',
            'after' => 'YK',
        ];
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
}
