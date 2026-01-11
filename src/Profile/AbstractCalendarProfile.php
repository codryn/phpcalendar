<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Profile;

use Codryn\PHPCalendar\Calendar\CalendarProfileInterface;

/**
 * Base class for calendar profile implementations
 *
 * Provides common functionality for calendar profiles
 */
abstract class AbstractCalendarProfile implements CalendarProfileInterface
{
    /**
     * @inheritDoc
     */
    public function getMonthCount(): int
    {
        return \count($this->getMonthNames());
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'source' => 'Unknown',
            'setting' => 'Unknown',
            'description' => '',
        ];
    }
}
