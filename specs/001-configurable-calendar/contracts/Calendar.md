# API Contract: Calendar Class

**Package**: `Codryn\PHPCalendar`  
**Version**: 1.0.0  
**Purpose**: Main entry point for calendar operations

## Class Signature

```php
<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

final class Calendar
{
    /**
     * Create calendar from a pre-built profile
     *
     * @param string $profileName Profile identifier (e.g., 'gregorian', 'faerun')
     * @return self
     * @throws \InvalidArgumentException if profile not found
     */
    public static function fromProfile(string $profileName): self;

    /**
     * Create calendar from custom configuration
     *
     * @param CalendarConfiguration $config Custom calendar parameters
     * @return self
     * @throws InvalidCalendarConfigException if configuration invalid
     */
    public static function fromConfiguration(CalendarConfiguration $config): self;

    /**
     * Get calendar name
     *
     * @return string Calendar identifier
     */
    public function getName(): string;

    /**
     * Get human-readable calendar name
     *
     * @return string Display name
     */
    public function getDisplayName(): string;

    /**
     * Parse date string into TimePoint
     *
     * @param string $dateString Date to parse (format depends on calendar profile)
     * @return TimePoint
     * @throws InvalidDateException if date cannot be parsed or is invalid
     */
    public function parse(string $dateString): TimePoint;

    /**
     * Format TimePoint as string
     *
     * @param TimePoint $point Time point to format
     * @param string|null $pattern Optional format pattern (null = default)
     * @return string Formatted date string
     * @throws IncompatibleCalendarException if point from different calendar
     */
    public function format(TimePoint $point, ?string $pattern = null): string;

    /**
     * Calculate time difference between two points
     *
     * @param TimePoint $start Starting time point
     * @param TimePoint $end Ending time point
     * @return TimeSpan Duration from start to end (negative if end before start)
     * @throws IncompatibleCalendarException if points from different calendars
     */
    public function diff(TimePoint $start, TimePoint $end): TimeSpan;

    /**
     * Get month names for this calendar
     *
     * @return array<string> Month names in order
     */
    public function getMonthNames(): array;

    /**
     * Get number of months in this calendar
     *
     * @return int Month count
     */
    public function getMonthCount(): int;

    /**
     * Get days in specific month/year
     *
     * @param int $month Month (1-based)
     * @param int $year Year (negative for pre-epoch)
     * @return int Number of days
     */
    public function getDaysInMonth(int $month, int $year): int;

    /**
     * Check if year is leap year
     *
     * @param int $year Year to check
     * @return bool True if leap year
     */
    public function isLeapYear(int $year): bool;

    /**
     * Get epoch notation (e.g., CE/BCE, DR, AR)
     *
     * @return array{before: string, after: string} Era labels
     */
    public function getEpochNotation(): array;
}
```

## Usage Examples

### Example 1: Create Gregorian Calendar
```php
$calendar = Calendar::fromProfile('gregorian');
echo $calendar->getDisplayName(); // "Gregorian Calendar"
```

### Example 2: Parse and Format Dates
```php
$calendar = Calendar::fromProfile('gregorian');
$date = $calendar->parse('2024-12-25');
echo $calendar->format($date); // "December 25, 2024"
```

### Example 3: Calculate Age
```php
$calendar = Calendar::fromProfile('gregorian');
$birthdate = $calendar->parse('1990-05-15');
$today = $calendar->parse('2024-01-08');
$age = $calendar->diff($birthdate, $today);
echo $age->getTotalDays() . ' days old'; // "12291 days old"
```

### Example 4: Custom Calendar
```php
$config = new CalendarConfiguration(
    name: 'custom',
    displayName: 'My Custom Calendar',
    monthNames: ['Alpha', 'Beta', 'Gamma'],
    daysPerMonth: [30, 30, 30],
    leapYearRule: fn(int $year) => $year % 5 === 0,
    epochNotation: ['before' => 'BE', 'after' => 'AE'],
    formatPatterns: ['d M Y']
);
$calendar = Calendar::fromConfiguration($config);
```

## Error Scenarios

### Scenario 1: Invalid Profile
```php
try {
    $calendar = Calendar::fromProfile('nonexistent');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage(); 
    // "Calendar profile 'nonexistent' not found. Available: gregorian, faerun, ..."
}
```

### Scenario 2: Invalid Date
```php
$calendar = Calendar::fromProfile('gregorian');
try {
    $date = $calendar->parse('February 30, 2024');
} catch (InvalidDateException $e) {
    echo $e->getMessage(); 
    // "Invalid date: February only has 29 days in 2024"
}
```

### Scenario 3: Cross-Calendar Operations
```php
$gregorian = Calendar::fromProfile('gregorian');
$faerun = Calendar::fromProfile('faerun');

$date1 = $gregorian->parse('2024-01-01');
$date2 = $faerun->parse('1 Hammer 1492 DR');

try {
    $diff = $gregorian->diff($date1, $date2);
} catch (IncompatibleCalendarException $e) {
    echo $e->getMessage();
    // "Cannot calculate difference between TimePoints from different calendars (gregorian vs faerun)"
}
```

## Thread Safety

Calendar instances are immutable and thread-safe after construction.

## Performance Characteristics

- Profile lookup: O(1) hash table lookup
- Date parsing: O(n) where n = number of format patterns to try
- Date formatting: O(1) for standard patterns
- Date arithmetic: O(1) for simple additions, O(m) for month-aware arithmetic where m = months

## Version Compatibility

- Requires: PHP 8.0+
- Breaking changes increment MAJOR version
- New profiles/features increment MINOR version
- Bug fixes increment PATCH version
