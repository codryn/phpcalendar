# API Contract: TimeSpan Class

**Package**: `Codryn\PHPCalendar\Calendar`  
**Version**: 1.0.0  
**Purpose**: Immutable value object representing duration between time points

## Class Signature

```php
<?php

declare(strict_types=1);

namespace Codryn\PHPCalendar\Calendar;

final class TimeSpan
{
    /**
     * Create TimeSpan from total seconds and microseconds
     *
     * @param int $seconds Total seconds (can be negative)
     * @param int $microseconds Additional microseconds 0-999999 (same sign as seconds)
     * @return self
     * @throws \InvalidArgumentException if microseconds out of range
     */
    public static function fromSeconds(int $seconds, int $microseconds = 0): self;

    /**
     * Get total seconds in span
     *
     * @return int Total seconds (negative for past direction)
     */
    public function getTotalSeconds(): int;

    /**
     * Get microsecond component
     *
     * @return int Microseconds (0-999999 or negative)
     */
    public function getMicroseconds(): int;

    /**
     * Get total days (truncated)
     *
     * @return int Days = seconds / 86400
     */
    public function getTotalDays(): int;

    /**
     * Get total hours (truncated)
     *
     * @return int Hours = seconds / 3600
     */
    public function getTotalHours(): int;

    /**
     * Get total minutes (truncated)
     *
     * @return int Minutes = seconds / 60
     */
    public function getTotalMinutes(): int;

    /**
     * Check if span represents past direction
     *
     * @return bool True if negative
     */
    public function isNegative(): bool;

    /**
     * Get absolute value of span
     *
     * @return TimeSpan New span with positive values
     */
    public function abs(): self;

    /**
     * Reverse direction of span
     *
     * @return TimeSpan New span with negated values
     */
    public function negate(): self;

    /**
     * Add another span
     *
     * @param TimeSpan $other Span to add
     * @return TimeSpan New span with combined duration
     */
    public function add(TimeSpan $other): self;

    /**
     * Subtract another span
     *
     * @param TimeSpan $other Span to subtract
     * @return TimeSpan New span with difference
     */
    public function subtract(TimeSpan $other): self;
}
```

## Usage Examples

### Example 1: Create from Seconds
```php
// 7 days
$week = TimeSpan::fromSeconds(86400 * 7);
echo $week->getTotalDays();    // 7
echo $week->getTotalHours();   // 168
echo $week->getTotalMinutes(); // 10080

// 1 hour, 30 minutes, 45 seconds
$span = TimeSpan::fromSeconds(3600 + 1800 + 45);
echo $span->getTotalSeconds(); // 5445
```

### Example 2: High Precision with Microseconds
```php
$span = TimeSpan::fromSeconds(1, 500000); // 1.5 seconds
echo $span->getTotalSeconds();   // 1
echo $span->getMicroseconds();   // 500000
```

### Example 3: Negative Spans (Past Direction)
```php
$future = $calendar->parse('2024-12-31');
$past = $calendar->parse('2024-01-01');

$span = $calendar->diff($future, $past);
echo $span->isNegative(); // true (going backwards)
echo $span->getTotalDays(); // -365

$reversed = $span->negate();
echo $reversed->getTotalDays(); // 365
```

### Example 4: Span Arithmetic
```php
$oneWeek = TimeSpan::fromSeconds(86400 * 7);
$twoWeeks = TimeSpan::fromSeconds(86400 * 14);

$total = $oneWeek->add($twoWeeks);
echo $total->getTotalDays(); // 21

$difference = $twoWeeks->subtract($oneWeek);
echo $difference->getTotalDays(); // 7
```

### Example 5: Absolute Values
```php
$pastSpan = TimeSpan::fromSeconds(-86400 * 30); // 30 days ago
echo $pastSpan->getTotalDays(); // -30
echo $pastSpan->isNegative();   // true

$absolute = $pastSpan->abs();
echo $absolute->getTotalDays(); // 30
echo $absolute->isNegative();   // false
```

## Conversion Helpers

### Days to TimeSpan
```php
function daysToTimeSpan(int $days): TimeSpan {
    return TimeSpan::fromSeconds($days * 86400);
}
```

### Hours to TimeSpan
```php
function hoursToTimeSpan(int $hours): TimeSpan {
    return TimeSpan::fromSeconds($hours * 3600);
}
```

### Minutes to TimeSpan
```php
function minutesToTimeSpan(int $minutes): TimeSpan {
    return TimeSpan::fromSeconds($minutes * 60);
}
```

## Error Scenarios

### Scenario 1: Invalid Microseconds
```php
try {
    $span = TimeSpan::fromSeconds(10, 1_500_000); // > 999999
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "Microseconds must be between -999999 and 999999"
}
```

### Scenario 2: Sign Mismatch
```php
try {
    $span = TimeSpan::fromSeconds(-10, 500000); // Negative seconds, positive microseconds
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "Seconds and microseconds must have consistent sign"
}
```

## Precision Notes

### Integer Limits
- Maximum span: ~68 years (2^31 seconds)
- For larger spans, consider breaking into smaller ranges
- Microseconds provide precision to 1/1,000,000 second

### Truncation
```php
$span = TimeSpan::fromSeconds(86400 + 3600); // 1 day + 1 hour

echo $span->getTotalDays();    // 1 (truncated, not rounded)
echo $span->getTotalHours();   // 25
echo $span->getTotalMinutes(); // 1500
```

To get remaining hours/minutes:
```php
$totalSeconds = $span->getTotalSeconds();
$days = intdiv($totalSeconds, 86400);
$remainingSeconds = $totalSeconds % 86400;
$hours = intdiv($remainingSeconds, 3600);
$minutes = intdiv($remainingSeconds % 3600, 60);

echo "$days days, $hours hours, $minutes minutes";
```

## Immutability

TimeSpan instances are immutable. All operations return new instances:

```php
$original = TimeSpan::fromSeconds(100);
$doubled = $original->add($original);

echo $original->getTotalSeconds(); // Still 100
echo $doubled->getTotalSeconds();  // 200
```

## Thread Safety

TimeSpan instances are immutable and thread-safe.

## Performance Characteristics

- Creation: O(1)
- All getters: O(1)
- Arithmetic operations: O(1)

## Memory Footprint

Approximately 24 bytes per TimeSpan instance (2 ints + object overhead).

## Comparison with PHP DateInterval

Unlike PHP's `DateInterval`, TimeSpan:
- Uses seconds as base unit (unambiguous across calendars)
- Is immutable (DateInterval is mutable)
- Supports microsecond precision
- Simpler API focused on duration, not P1Y2M3D notation
- Works across different calendar systems

For PHP interop:
```php
function dateIntervalToTimeSpan(\DateInterval $interval): TimeSpan {
    $seconds = ($interval->days * 86400)
             + ($interval->h * 3600)
             + ($interval->i * 60)
             + $interval->s;
    return TimeSpan::fromSeconds($interval->invert ? -$seconds : $seconds);
}
```
