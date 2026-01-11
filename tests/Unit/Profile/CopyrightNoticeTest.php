<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Profile;

use Codryn\PHPCalendar\Profile\DSAProfile;
use Codryn\PHPCalendar\Profile\DragonlanceProfile;
use Codryn\PHPCalendar\Profile\EberronProfile;
use Codryn\PHPCalendar\Profile\FaerunProfile;
use Codryn\PHPCalendar\Profile\GolarionProfile;
use Codryn\PHPCalendar\Profile\GregorianProfile;
use Codryn\PHPCalendar\Profile\GreyhawkProfile;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for copyright notices on calendar profiles
 */
class CopyrightNoticeTest extends TestCase
{
    /**
     * Test that Faerûn (Forgotten Realms) profile has a copyright notice
     */
    public function testFaerunProfileHasCopyrightNotice(): void
    {
        $profile = new FaerunProfile();
        $copyright = $profile->getCopyrightNotice();

        $this->assertIsString($copyright);
        $this->assertStringContainsString('Forgotten Realms', $copyright);
        $this->assertStringContainsString('non-commercial', $copyright);
        $this->assertStringContainsString('game masters', $copyright);
        $this->assertStringContainsString('Wizards of the Coast', $copyright);
    }

    /**
     * Test that Golarion (Pathfinder) profile has a copyright notice
     */
    public function testGolarionProfileHasCopyrightNotice(): void
    {
        $profile = new GolarionProfile();
        $copyright = $profile->getCopyrightNotice();

        $this->assertIsString($copyright);
        $this->assertStringContainsString('Golarion', $copyright);
        $this->assertStringContainsString('non-commercial', $copyright);
        $this->assertStringContainsString('game masters', $copyright);
        $this->assertStringContainsString('Paizo', $copyright);
    }

    /**
     * Test that DSA (Das Schwarze Auge) profile has a copyright notice
     */
    public function testDSAProfileHasCopyrightNotice(): void
    {
        $profile = new DSAProfile();
        $copyright = $profile->getCopyrightNotice();

        $this->assertIsString($copyright);
        $this->assertStringContainsString('Das Schwarze Auge', $copyright);
        $this->assertStringContainsString('non-commercial', $copyright);
        $this->assertStringContainsString('game masters', $copyright);
        $this->assertStringContainsString('Ulisses Spiele', $copyright);
    }

    /**
     * Test that Eberron profile has a copyright notice
     */
    public function testEberronProfileHasCopyrightNotice(): void
    {
        $profile = new EberronProfile();
        $copyright = $profile->getCopyrightNotice();

        $this->assertIsString($copyright);
        $this->assertStringContainsString('Eberron', $copyright);
        $this->assertStringContainsString('non-commercial', $copyright);
        $this->assertStringContainsString('game masters', $copyright);
        $this->assertStringContainsString('Wizards of the Coast', $copyright);
    }

    /**
     * Test that Dragonlance profile has a copyright notice
     */
    public function testDragonlanceProfileHasCopyrightNotice(): void
    {
        $profile = new DragonlanceProfile();
        $copyright = $profile->getCopyrightNotice();

        $this->assertIsString($copyright);
        $this->assertStringContainsString('Dragonlance', $copyright);
        $this->assertStringContainsString('non-commercial', $copyright);
        $this->assertStringContainsString('game masters', $copyright);
        $this->assertStringContainsString('Wizards of the Coast', $copyright);
    }

    /**
     * Test that Greyhawk profile has a copyright notice
     */
    public function testGreyhawkProfileHasCopyrightNotice(): void
    {
        $profile = new GreyhawkProfile();
        $copyright = $profile->getCopyrightNotice();

        $this->assertIsString($copyright);
        $this->assertStringContainsString('Greyhawk', $copyright);
        $this->assertStringContainsString('non-commercial', $copyright);
        $this->assertStringContainsString('game masters', $copyright);
        $this->assertStringContainsString('Wizards of the Coast', $copyright);
    }

    /**
     * Test that Gregorian profile does not have a copyright notice
     * (it's a real-world calendar, not a game system)
     */
    public function testGregorianProfileHasNoCopyrightNotice(): void
    {
        $profile = new GregorianProfile();
        $copyright = $profile->getCopyrightNotice();

        $this->assertNull($copyright);
    }

    /**
     * Provide RPG profiles for testing
     *
     * @return array<string, object>
     */
    private function getRPGProfiles(): array
    {
        return [
            'faerun' => new FaerunProfile(),
            'golarion' => new GolarionProfile(),
            'dsa' => new DSAProfile(),
            'eberron' => new EberronProfile(),
            'dragonlance' => new DragonlanceProfile(),
            'greyhawk' => new GreyhawkProfile(),
        ];
    }

    /**
     * Test that all RPG copyright notices mention non-commercial use
     */
    public function testAllRPGProfilesHaveNonCommercialClause(): void
    {
        foreach ($this->getRPGProfiles() as $profile) {
            $copyright = $profile->getCopyrightNotice();
            $this->assertIsString($copyright);
            $this->assertStringContainsString('non-commercial', $copyright);
        }
    }

    /**
     * Test that all RPG copyright notices mention helping game masters
     */
    public function testAllRPGProfilesHaveGameMasterClause(): void
    {
        foreach ($this->getRPGProfiles() as $profile) {
            $copyright = $profile->getCopyrightNotice();
            $this->assertIsString($copyright);
            $this->assertStringContainsString('game masters', $copyright);
        }
    }
}
