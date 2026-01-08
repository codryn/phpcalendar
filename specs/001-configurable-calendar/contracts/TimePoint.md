# API Contract: TimePoint Class

**Package**: `Codryn\PHPCalendar\Calendar`  
**Version**: 1.0.0  
**Purpose**: Immutable value object representing a point in time

## Class Signature

```php
<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

final class TimePoint
{
    /**
     * Get parent calendar
     *
     * @return Calendar
     */
    public function getCalendar(): Calendar;

    /**
     * Get year (negative for pre-epoch)
     *
     * @return int
     */
    public function getYear(): int;

    /**
     * Get month (1-based)
     *
     * @return int
     */
    public function getMonth(): int;

    /**
     * Get day of month (1-based)
     *
     * @return int
     */
    public function getDay(): int;

    /**
     * Get hour (0-23)
     *
     * @return int
     */
    public function getHour(): int;

    /**
     * Get minute (0-59)
     *
     * @return int
     */
    public function getMinute(): int;

    /**
     * Get second (0-59)
     *
     * @return int
     */
    public function getSecond(): int;

    /**
     * Get microsecond (0-999999)
     *
     * @return int
     */
    public function getMicrosecond(): int;

    /**
     * Add duration to this time point
     *
     * @param TimeSpan $span Duration to add
     * @return TimePoint New time point (this instance unchanged)
     * @throws DateArithmeticException if result is invalid date
     */
    public function add(TimeSpan $span): TimePoint;

    /**
     * Subtract duration from this time point
     *
     * @param TimeSpan $span Duration to subtract
     * @return TimePoint New time point (this instance unchanged)
     * @throws DateArithmeticException if result is invalid date
     */
    public function subtract(TimeSpan $span): TimePoint;

    /**
     * Check equality with another time point
     *
     * @param TimePoint $other Point to compare
     * @return bool True if equal (same calendar, same moment)
     * @throws IncompatibleCalendarException if different calendars
     */
    public function equals(TimePoint $other): bool;

    /**
     * Check if this point is before another
     *
     * @param TimePoint $other Point to compare
     * @return bool True if this is before other
     * @throws IncompatibleCalendarException if different calendars
     */
    public function isBefore(TimePoint $other): bool;

    /**
     * Check if this point is after another
     *
     * @param TimePoint $other Point to compare
     * @return bool True if this is after other
     * @throws IncompatibleCalendarException if different calendars
     */
    public function isAfter(TimePoint $other): bool;
}
```

## Usage Examples

### Example 1: Access Components
```php
$calendar = Calendar::fromProfile('gregorian');
$date = $calendar->parse('2024-12-25 15:30:00');

echo $date->getYear();   // 2024
echo $date->getMonth();  // 12
echo $date->getDay();    // 25
echo $date->getHour();   // 15
echo $date->getMinute(); // 30
echo $date->getSecond(); // 0
```

### Example 2: Date Arithmetic
```php
$calendar = Calendar::fromProfile('gregorian');
$date = $calendar->parse('2024-01-15');

$span = TimeSpan::fromSeconds(86400 * 7); // 7 days
$futureDate = $date->add($span);
echo $calendar->format($futureDate); // "January 22, 2024"

$pastDate = $date->subtract($span);
echo $calendar->format($pastDate); // "January 8, 2024"
```

### Example 3: Comparison
```php
$calendar = Calendar::fromProfile('gregorian');
$date1 = $calendar->parse('2024-01-01');
$date2 = $calendar->parse('2024-12-31');

if ($date1->isBefore($date2)) {
    echo "Date1 is before Date2"; // This prints
}

if ($date1->equals($date1)) {
    echo "Date equals itself"; // This prints
}
```

### Example 4: Invalid Arithmetic
```php
$calendar = Calendar::fromProfile('gregorian');
$date = $calendar->parse('2024-01-31');

$oneMonth = TimeSpan::fromSeconds(86400 * 31); // Approximate month
try {
    $result = $date->add($oneMonth); // Would be Feb 31 - invalid!
} catch (DateArithmeticException $e) {
    echo $e->getMessage();
    // "Date arithmetic resulted in invalid date: February 31, 2024 does not exist"
}
```

## Error Scenarios

### Scenario 1: Cross-Calendar Comparison
```php
$gregorian = Calendar::fromProfile('gregorian');
$faerun = Calendar::fromProfile('faerun');

$date1 = $gregorian->parse('2024-01-01');
$date2 = $faerun->parse('1 Hammer 1492 DR');

try {
    if ($date1->isBefore($date2)) {
        // ...
    }
} catch (IncompatibleCalendarException $e) {
    echo $e->getMessage();
    // "Cannot compare TimePoints from different calendars (gregorian vs faerun)"
}
```

### Scenario 2: Invalid Date Arithmetic
```php
$calendar = Calendar::fromProfile('gregorian');
$date = $calendar->parse('2024-01-31');

// Adding 1 month worth of seconds would create Feb 31
$span = TimeSpan::fromSeconds(86400 * 31);
try {
    $result = $date->add($span);
} catch (DateArithmeticException $e) {
    // Handle invalid date result
    // Recommendation: Use smaller spans or implement business logic for month-end handling
}
```

## Immutability

TimePoint instances are immutable. All operations (add, subtract) return new instances:

```php
$original = $calendar->parse('2024-01-15');
$modified = $original->add(TimeSpan::fromSeconds(86400));

echo $calendar->format($original);  // Still "January 15, 2024"
echo $calendar->format($modified);  // "January 16, 2024"
```

## Equality Semantics

TimePoints are equal if:
1. They belong to the same calendar
2. All components match (year, month, day, hour, minute, second, microsecond)

```php
$date1 = $calendar->parse('2024-01-15 12:00:00');
$date2 = $calendar->parse('2024-01-15 12:00:00');
$date3 = $calendar->parse('2024-01-15 12:00:01');

$date1->equals($date2); // true
$date1->equals($date3); // false (1 second difference)
```

## Thread Safety

TimePoint instances are immutable and thread-safe.

## Performance Characteristics

- Component access: O(1)
- Arithmetic operations: O(1) for seconds, O(m) for month/year awareness where m = affected months
- Comparisons: O(1)

## Memory Footprint

Approximately 64 bytes per TimePoint instance (8 ints + object overhead + calendar reference).
