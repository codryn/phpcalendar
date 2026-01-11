<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

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
        return 'Das Schwarze Auge (Aventurian Calendar)';
    }

    public function getMonthNames(): array
    {
        return [
            1 => 'Praios',
            2 => 'Rondra',
            3 => 'Efferd',
            4 => 'Travia',
            5 => 'Boron',
            6 => 'Hesinde',
            7 => 'Firun',
            8 => 'Tsa',
            9 => 'Phex',
            10 => 'Peraine',
            11 => 'Ingerimm',
            12 => 'Rahja',
        ];
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
        return [
            'before' => 'Before BF',
            'after' => 'BF',
        ];
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
        return [
            [
                'position' => 12, // After the 12th month
                'names' => [
                    1 => 'First Nameless Day',
                    2 => 'Second Nameless Day',
                    3 => 'Third Nameless Day',
                    4 => 'Fourth Nameless Day',
                    5 => 'Fifth Nameless Day',
                ],
                'leap' => false, // No extra leap day
            ],
        ];
    }
}
