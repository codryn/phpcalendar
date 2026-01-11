<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

use Codryn\PHPCalendar\Profile\DragonlanceProfile;
use Codryn\PHPCalendar\Profile\DSAProfile;
use Codryn\PHPCalendar\Profile\EberronProfile;
use Codryn\PHPCalendar\Profile\FaerunProfile;
use Codryn\PHPCalendar\Profile\GolarionProfile;
use Codryn\PHPCalendar\Profile\GregorianProfile;
use Codryn\PHPCalendar\Profile\GreyhawkProfile;
use InvalidArgumentException;

/**
 * Registry for calendar profiles
 *
 * Manages lookup and registration of calendar profiles
 */
final class ProfileRegistry
{
    /** @var array<string, CalendarProfileInterface> */
    private static array $profiles = [];

    /** @var bool */
    private static bool $initialized = false;

    /**
     * Get profile by name
     *
     * @param string $name Profile name
     * @return CalendarProfileInterface
     * @throws InvalidArgumentException if profile not found
     */
    public static function get(string $name): CalendarProfileInterface
    {
        self::initialize();

        if (!isset(self::$profiles[$name])) {
            $available = \implode(', ', \array_keys(self::$profiles));

            throw new InvalidArgumentException(
                "Unknown calendar profile: '{$name}'. Available profiles: {$available}",
            );
        }

        return self::$profiles[$name];
    }

    /**
     * Register a profile
     *
     * @param string $name Profile name
     * @param CalendarProfileInterface $profile Profile instance
     */
    public static function register(string $name, CalendarProfileInterface $profile): void
    {
        self::$profiles[$name] = $profile;
    }

    /**
     * Get all available profile names
     *
     * @return array<int, string>
     */
    public static function getAvailableProfiles(): array
    {
        self::initialize();

        return \array_keys(self::$profiles);
    }

    /**
     * Initialize default profiles
     */
    private static function initialize(): void
    {
        if (self::$initialized) {
            return;
        }

        self::register('gregorian', new GregorianProfile());
        self::register('faerun', new FaerunProfile());
        self::register('golarion', new GolarionProfile());
        self::register('dsa', new DSAProfile());
        self::register('eberron', new EberronProfile());
        self::register('dragonlance', new DragonlanceProfile());
        self::register('greyhawk', new GreyhawkProfile());

        self::$initialized = true;
    }

    /**
     * Reset registry (for testing)
     */
    public static function reset(): void
    {
        self::$profiles = [];
        self::$initialized = false;
    }
}
