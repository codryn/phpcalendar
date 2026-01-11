<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

/**
 * Trait providing copyright notice generation for RPG calendar profiles
 *
 * This trait provides standardized copyright notice methods for calendar
 * implementations based on tabletop role-playing game settings. It ensures
 * consistent messaging about non-commercial use and proper attribution to
 * intellectual property holders.
 *
 * The trait is designed for use by calendar profile classes that represent
 * game system calendars (e.g., D&D, Pathfinder, Das Schwarze Auge).
 */
trait CopyrightNoticeTrait
{
    /**
     * Generate a copyright notice for Wizards of the Coast properties
     *
     * @param string $setting The game setting name (e.g., "Forgotten Realms", "Eberron")
     * @param string $trademarks Additional trademarks specific to the setting
     * @return string The copyright notice
     */
    private function getWizardsOfTheCoastCopyright(string $setting, string $trademarks): string
    {
        return "The calendar names, month names, and associated terminology from the {$setting} "
            . 'setting are the property of Wizards of the Coast. This calendar implementation is provided '
            . 'for non-commercial use only to help game masters and players keep track of their campaigns. '
            . "Dungeons & Dragons, D&D, {$trademarks}, and all related trademarks are property of "
            . 'Wizards of the Coast LLC.';
    }

    /**
     * Generate a copyright notice for Paizo Inc. properties
     *
     * @param string $setting The game setting name (e.g., "Golarion")
     * @param string $trademarks Additional trademarks specific to the setting
     * @return string The copyright notice
     */
    private function getPaizoCopyright(string $setting, string $trademarks): string
    {
        return "The calendar names, month names, and associated terminology from the {$setting} "
            . 'setting are the property of Paizo Inc. This calendar implementation is provided '
            . 'for non-commercial use only to help game masters and players keep track of their campaigns. '
            . "{$trademarks}, and all related trademarks are property of Paizo Inc.";
    }

    /**
     * Generate a copyright notice for Ulisses Spiele properties
     *
     * @param string $gameName The game name (e.g., "Das Schwarze Auge (The Dark Eye)")
     * @param string $trademarks Additional trademarks specific to the setting
     * @return string The copyright notice
     */
    private function getUlissesSpieleCopyright(string $gameName, string $trademarks): string
    {
        return "The calendar names, month names, and associated terminology from {$gameName} "
            . 'are the property of Ulisses Spiele. This calendar implementation is provided '
            . 'for non-commercial use only to help game masters and players keep track of their campaigns. '
            . "{$trademarks}, and all related trademarks are property of "
            . 'Ulisses Spiele GmbH.';
    }
}
