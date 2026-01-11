<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Locale;

use Codryn\PHPCalendar\Locale\Locale;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Locale class
 */
class LocaleTest extends TestCase
{
    public function testSupportedLocales(): void
    {
        $this->assertTrue(Locale::isSupported('en-us'));
        $this->assertTrue(Locale::isSupported('de'));
        $this->assertTrue(Locale::isSupported('fr'));
        $this->assertTrue(Locale::isSupported('es'));
        $this->assertTrue(Locale::isSupported('it'));
    }

    public function testUnsupportedLocale(): void
    {
        $this->assertFalse(Locale::isSupported('unsupported'));
        $this->assertFalse(Locale::isSupported('ja'));
        $this->assertFalse(Locale::isSupported('zh'));
    }

    public function testNormalize(): void
    {
        $this->assertSame('en-us', Locale::normalize('en-us'));
        $this->assertSame('de', Locale::normalize('de'));
        $this->assertSame('en-us', Locale::normalize('unsupported'));
        $this->assertSame('en-us', Locale::normalize(null));
    }

    public function testNormalizeCaseInsensitive(): void
    {
        $this->assertSame('de', Locale::normalize('DE'));
        $this->assertSame('en-us', Locale::normalize('EN-US'));
        $this->assertSame('fr', Locale::normalize('FR'));
    }

    public function testDefaultLocale(): void
    {
        $this->assertSame('en-us', Locale::DEFAULT_LOCALE);
    }
}
