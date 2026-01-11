<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

/**
 * Golarion Calendar Profile (Pathfinder)
 *
 * Absalom Reckoning (AR) calendar system
 */
final class GolarionProfile extends AbstractCalendarProfile
{
    private const DAYS_PER_MONTH = [
        1 => 31,  // Abadius
        2 => 28,  // Calistril
        3 => 31,  // Pharast
        4 => 30,  // Gozran
        5 => 31,  // Desnus
        6 => 30,  // Sarenith
        7 => 31,  // Erastus
        8 => 31,  // Arodus
        9 => 30,  // Rova
        10 => 31, // Lamashan
        11 => 30, // Neth
        12 => 31, // Kuthona
    ];

    public function getName(): string
    {
        return 'golarion';
    }

    public function getDisplayName(): string
    {
        return 'Golarion (Absalom Reckoning)';
    }

    public function getMonthNames(): array
    {
        return [
            1 => 'Abadius',
            2 => 'Calistril',
            3 => 'Pharast',
            4 => 'Gozran',
            5 => 'Desnus',
            6 => 'Sarenith',
            7 => 'Erastus',
            8 => 'Arodus',
            9 => 'Rova',
            10 => 'Lamashan',
            11 => 'Neth',
            12 => 'Kuthona',
        ];
    }

    public function getDaysInMonth(int $month, int $year): int
    {
        return self::DAYS_PER_MONTH[$month] ?? 30;
    }

    public function isLeapYear(int $year): bool
    {
        // Leap year every 8 years in Golarion
        return $year % 8 === 0;
    }

    public function getEpochNotation(): array
    {
        return [
            'before' => 'Before AR',
            'after' => 'AR',
        ];
    }

    public function getFormatPatterns(): array
    {
        return [
            'd F Y AR',
            'F d, Y AR',
            'Y-m-d',
        ];
    }

    public function getMetadata(): array
    {
        return [
            'source' => 'Pathfinder Campaign Setting',
            'setting' => 'Golarion',
            'description' => 'Absalom Reckoning calendar, dating from the founding of Absalom in 1 AR',
        ];
    }
}
