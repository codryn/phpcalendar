<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Profile;

use Codryn\PHPCalendar\Profile\FaerunProfile;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FaerunProfile (Harptos Calendar)
 */
class FaerunProfileTest extends TestCase
{
    private FaerunProfile $profile;

    protected function setUp(): void
    {
        $this->profile = new FaerunProfile();
    }

    public function testGetName(): void
    {
        $this->assertSame('faerun', $this->profile->getName());
    }

    public function testGetDisplayName(): void
    {
        $this->assertSame('Faerûn (Harptos Calendar)', $this->profile->getDisplayName());
    }

    public function testGetMonthNames(): void
    {
        $months = $this->profile->getMonthNames();

        $this->assertIsArray($months);
        $this->assertCount(12, $months);

        // Verify Harptos month names
        $this->assertSame('Hammer', $months[1]);
        $this->assertSame('Alturiak', $months[2]);
        $this->assertSame('Ches', $months[3]);
        $this->assertSame('Tarsakh', $months[4]);
        $this->assertSame('Mirtul', $months[5]);
        $this->assertSame('Kythorn', $months[6]);
        $this->assertSame('Flamerule', $months[7]);
        $this->assertSame('Eleasis', $months[8]);
        $this->assertSame('Eleint', $months[9]);
        $this->assertSame('Marpenoth', $months[10]);
        $this->assertSame('Uktar', $months[11]);
        $this->assertSame('Nightal', $months[12]);
    }

    public function testGetMonthCount(): void
    {
        $this->assertSame(12, $this->profile->getMonthCount());
    }

    public function testGetDaysInMonthAlways30(): void
    {
        // Harptos: each month has exactly 30 days
        for ($month = 1; $month <= 12; $month++) {
            $this->assertSame(30, $this->profile->getDaysInMonth($month, 1492));
            $this->assertSame(30, $this->profile->getDaysInMonth($month, 1493));
        }
    }

    public function testIsLeapYearEvery4Years(): void
    {
        // Shieldmeet occurs every 4 years
        $this->assertTrue($this->profile->isLeapYear(1492));
        $this->assertTrue($this->profile->isLeapYear(1496));
        $this->assertTrue($this->profile->isLeapYear(1500));

        $this->assertFalse($this->profile->isLeapYear(1493));
        $this->assertFalse($this->profile->isLeapYear(1494));
        $this->assertFalse($this->profile->isLeapYear(1495));
    }

    public function testGetEpochNotation(): void
    {
        $epoch = $this->profile->getEpochNotation();

        $this->assertIsArray($epoch);
        $this->assertArrayHasKey('before', $epoch);
        $this->assertArrayHasKey('after', $epoch);
        $this->assertSame('DR', $epoch['after']); // Dalereckoning
    }

    public function testGetFormatPatterns(): void
    {
        $patterns = $this->profile->getFormatPatterns();

        $this->assertIsArray($patterns);
        $this->assertNotEmpty($patterns);
    }

    public function testGetMetadata(): void
    {
        $metadata = $this->profile->getMetadata();

        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('source', $metadata);
        $this->assertArrayHasKey('setting', $metadata);
        $this->assertStringContainsString('Forgotten Realms', $metadata['setting']);
    }
}
