<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Integration;

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarMapping;
use Codryn\PHPCalendar\Calendar\CalendarMappingConfiguration;
use Codryn\PHPCalendar\Calendar\TimePoint;
use Codryn\PHPCalendar\Calendar\TimeSpan;
use Codryn\PHPCalendar\Exception\InvalidDateException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Regression coverage for nameless/festival days via calendar mapping.
 * Correlation: 2024-01-01 (Gregorian) == 1 Hammer 1492 DR (Harptos).
 * Both years are leap years, so this also exercises Shieldmeet.
 */
final class FaerunFestivalMappingTest extends TestCase
{
    private Calendar $gregorian;

    private Calendar $faerun;

    private CalendarMapping $mapping;

    protected function setUp(): void
    {
        $this->gregorian = Calendar::fromProfile('gregorian');
        $this->faerun = Calendar::fromProfile('faerun');
        $this->mapping = new CalendarMapping(
            new CalendarMappingConfiguration(
                sourceCalendarName: 'gregorian',
                targetCalendarName: 'faerun',
                correlationDate: [
                    'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
                    'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
                ],
            ),
            $this->gregorian,
            $this->faerun,
        );
    }

    /** @return iterable<string, array{string, string, int, int}> */
    public static function festivals(): iterable
    {
        yield 'Midwinter' => ['2024-01-31', 'Midwinter, 1492', 1, 31];
        yield 'Greengrass' => ['2024-05-01', 'Greengrass, 1492', 4, 31];
        yield 'Midsummer' => ['2024-07-31', 'Midsummer, 1492', 7, 31];
        yield 'Shieldmeet' => ['2024-08-01', 'Shieldmeet, 1492', 7, 32];
        yield 'Highharvestide' => ['2024-10-01', 'Highharvestide, 1492', 9, 31];
        yield 'Feast of the Moon' => ['2024-12-01', 'Feast of the Moon, 1492', 11, 31];
    }

    #[DataProvider('festivals')]
    public function testMappedFestivalsRemainDistinctAndRoundTrip(
        string $source,
        string $expected,
        int $month,
        int $day,
    ): void {
        $sourceDate = $this->gregorian->parse($source);
        $target = $this->mapping->convert($sourceDate);

        self::assertSame($expected, $this->faerun->format($target));
        self::assertSame($month, $target->getMonth());
        self::assertSame($day, $target->getDay());
        self::assertSame(1492, $target->getYear());
        self::assertSame(
            $source,
            $this->gregorian->format($this->mapping->reverseConvert($target), 'Y-m-d'),
            'A festival must not map back to the previous ordinary day.',
        );
    }

    public function testDatesImmediatelyAroundMidwinter(): void
    {
        self::assertSame('Hammer 30, 1492', $this->formatMapped('2024-01-30'));
        self::assertSame('Midwinter, 1492', $this->formatMapped('2024-01-31'));
        self::assertSame('Alturiak 1, 1492', $this->formatMapped('2024-02-01'));
    }

    public function testArithmeticCanCrossFestivalAndKeepItsIdentity(): void
    {
        $lastHammer = new TimePoint($this->faerun, 1492, 1, 30);
        $midwinter = $lastHammer->add(TimeSpan::fromSeconds(86400));
        $alturiak = $midwinter->add(TimeSpan::fromSeconds(86400));

        self::assertSame('Midwinter, 1492', $this->faerun->format($midwinter));
        self::assertSame('Alturiak 1, 1492', $this->faerun->format($alturiak));
        self::assertSame(2 * 86400, $this->faerun->diff($lastHammer, $alturiak)->getTotalSeconds());
    }

    public function testShieldmeetIsOnlyInLeapYears(): void
    {
        self::assertSame('Midsummer, 1493', $this->formatMapped('2025-08-01'));
        self::assertSame('Eleasis 1, 1493', $this->formatMapped('2025-08-02'));

        $this->expectException(InvalidDateException::class);
        new TimePoint($this->faerun, 1493, 7, 32);
    }

    public function testFestivalFormattingPreservesTimeAndYearBoundary(): void
    {
        $source = new TimePoint($this->gregorian, 2024, 1, 31, 17, 45);
        $target = $this->mapping->convert($source);

        self::assertSame('Midwinter, 1492 17:45', $this->faerun->format($target, 'F j, Y H:i'));
        self::assertSame(17, $target->getHour());
        self::assertSame(45, $target->getMinute());
        self::assertSame('Nightal 30, 1492', $this->formatMapped('2024-12-31'));
        self::assertSame('Hammer 1, 1493', $this->formatMapped('2025-01-01'));
    }

    public function testOtherNamelessDaysCalendarAlsoRetainsIdentity(): void
    {
        $dsa = Calendar::fromProfile('dsa');
        $date = new TimePoint($dsa, 1045, 12, 30);
        $next = $date->add(TimeSpan::fromSeconds(86400));

        self::assertSame('First Nameless Day, 1045', $dsa->format($next));
        self::assertSame(31, $next->getDay());
        self::assertSame('Praios 1, 1046', $dsa->format($next->add(TimeSpan::fromSeconds(5 * 86400))));
    }

    private function formatMapped(string $source): string
    {
        return $this->faerun->format($this->mapping->convert($this->gregorian->parse($source)));
    }
}
