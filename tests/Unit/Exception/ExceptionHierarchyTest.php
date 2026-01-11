<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Tests\Unit\Exception;

use Codryn\PHPCalendar\Exception\CalendarException;
use Codryn\PHPCalendar\Exception\DateArithmeticException;
use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;
use Codryn\PHPCalendar\Exception\InvalidCalendarConfigException;
use Codryn\PHPCalendar\Exception\InvalidDateException;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Test exception hierarchy and inheritance
 */
class ExceptionHierarchyTest extends TestCase
{
    public function testCalendarExceptionExtendsException(): void
    {
        $exception = new CalendarException('Test message');

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame('Test message', $exception->getMessage());
    }

    public function testInvalidDateExceptionExtendsCalendarException(): void
    {
        $exception = new InvalidDateException('Invalid date');

        $this->assertInstanceOf(CalendarException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testInvalidCalendarConfigExceptionExtendsCalendarException(): void
    {
        $exception = new InvalidCalendarConfigException('Invalid config');

        $this->assertInstanceOf(CalendarException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testIncompatibleCalendarExceptionExtendsCalendarException(): void
    {
        $exception = new IncompatibleCalendarException('Incompatible calendars');

        $this->assertInstanceOf(CalendarException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testDateArithmeticExceptionExtendsCalendarException(): void
    {
        $exception = new DateArithmeticException('Arithmetic error');

        $this->assertInstanceOf(CalendarException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testExceptionsCanBeThrown(): void
    {
        $this->expectException(CalendarException::class);

        throw new CalendarException('Test exception');
    }

    public function testExceptionsCanBeCaughtByBaseType(): void
    {
        try {
            throw new InvalidDateException('Test');
        } catch (CalendarException $e) {
            $this->assertInstanceOf(InvalidDateException::class, $e);

            return;
        }

        $this->fail('Exception should have been caught');
    }
}
