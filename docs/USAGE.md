# PHPCalendar Usage Guide

Quick start guide and common usage patterns for PHPCalendar.

## Installation

```bash
composer require codryn/phpcalendar
```

## Quick Start

```php
<?php

use Codryn\PHPCalendar\Calendar\Calendar;

// Create a calendar from a built-in profile
$calendar = Calendar::fromProfile('gregorian');

// Parse a date string
$date = $calendar->parse('December 25, 2024');

// Format the date
echo $calendar->format($date, 'F d, Y'); // December 25, 2024

// Add time to a date
use Codryn\PHPCalendar\Calendar\TimeSpan;

$future = $date->add(TimeSpan::fromSeconds(86400 * 7)); // Add 7 days
echo $calendar->format($future, 'F d, Y'); // January 1, 2025
```

## Usage Patterns

### 1. Working with Built-in Calendar Profiles

#### Gregorian Calendar

```php
$calendar = Calendar::fromProfile('gregorian');

$christmas = $calendar->parse('2024-12-25');
echo $calendar->format($christmas, 'l, F j, Y'); // Wednesday, December 25, 2024

// Check leap year
if ($calendar->isLeapYear(2024)) {
    echo "2024 is a leap year!";
}
```

#### Fantasy Calendars

**Faerûn (Forgotten Realms):**
```php
$calendar = Calendar::fromProfile('faerun');

$date = $calendar->parse('1 Hammer 1492 DR');
echo $calendar->format($date, 'd F Y'); // 1 Hammer 1492

// Faerun has 12 months of 30 days + 5 festival days
echo $calendar->getMonthCount(); // 17 (12 months + 5 festivals)
```

**Golarion (Pathfinder):**
```php
$calendar = Calendar::fromProfile('golarion');

$date = $calendar->parse('15 Rova 4724 AR');
echo $calendar->format($date, 'd F Y'); // 15 Rova 4724

// Leap year every 8 years
echo $calendar->isLeapYear(4720) ? 'Leap' : 'Normal'; // Leap
```

**Das Schwarze Auge:**
```php
$calendar = Calendar::fromProfile('dsa');

$date = $calendar->parse('12 Praios 1045 BF');
// 12 months of 30 days + 5 nameless days
```

**Eberron:**
```php
$calendar = Calendar::fromProfile('eberron');

$date = $calendar->parse('10 Olarune 998 YK');
// 12 months of exactly 28 days (no leap years)
```

**Dragonlance:**
```php
$calendar = Calendar::fromProfile('dragonlance');

$date = $calendar->parse('25 Phoenix 351 AC');
// AC (After Cataclysm) / PC (Pre Cataclysm) epochs
```

**Greyhawk:**
```php
$calendar = Calendar::fromProfile('greyhawk');

$date = $calendar->parse('1 Needfest 591 CY');
// 12 months + 4 festival weeks = 364 days
```

### 2. Creating Custom Calendars

```php
use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarConfiguration;

// Define your custom calendar
$config = new CalendarConfiguration(
    name: 'mars-sol',
    displayName: 'Martian Solar Calendar',
    monthNames: [
        1 => 'Ares', 2 => 'Phobos', 3 => 'Deimos',
        4 => 'Olympus', 5 => 'Valles', 6 => 'Mariner',
        7 => 'Viking', 8 => 'Sojourner', 9 => 'Spirit',
        10 => 'Opportunity', 11 => 'Curiosity', 12 => 'Perseverance'
    ],
    daysPerMonth: [
        1 => 31, 2 => 31, 3 => 31, 4 => 31,
        5 => 31, 6 => 31, 7 => 31, 8 => 31,
        9 => 31, 10 => 31, 11 => 31, 12 => 36
    ],
    leapYearRule: fn(int $year) => $year % 10 === 0,
    epochNotation: ['before' => 'BL', 'after' => 'AL'], // Before/After Landing
    formatPatterns: ['d F Y AL', 'Y-m-d']
);

$marsCalendar = Calendar::fromConfiguration($config);

$landingDay = $marsCalendar->parse('1 Ares 1 AL');
echo $marsCalendar->format($landingDay); // 1 Ares 1 AL
```

### 3. Date Arithmetic

```php
$calendar = Calendar::fromProfile('gregorian');
$today = $calendar->parse('2024-12-25');

// Add time using TimeSpan
use Codryn\PHPCalendar\Calendar\TimeSpan;

// Add days
$nextWeek = $today->add(TimeSpan::fromSeconds(86400 * 7));

// Add hours
$nextHour = $today->add(TimeSpan::fromSeconds(3600));

// Add minutes
$in30Mins = $today->add(TimeSpan::fromSeconds(1800));

// Subtract time
$lastWeek = $today->subtract(TimeSpan::fromSeconds(86400 * 7));

// Calculate difference between dates
$start = $calendar->parse('2024-01-01');
$end = $calendar->parse('2024-12-31');
$span = $calendar->diff($start, $end);

echo $span->getTotalDays(); // 365
echo $span->getTotalHours(); // 8760
echo $span->getTotalMinutes(); // 525600
```

### 4. Working with TimePoints

```php
use Codryn\PHPCalendar\Calendar\TimePoint;

$calendar = Calendar::fromProfile('gregorian');

// Create a TimePoint manually
$timePoint = new TimePoint(
    calendar: $calendar,
    year: 2024,
    month: 12,
    day: 25,
    hour: 14,
    minute: 30,
    second: 0
);

// Access components
echo $timePoint->getYear();   // 2024
echo $timePoint->getMonth();  // 12
echo $timePoint->getDay();    // 25
echo $timePoint->getHour();   // 14
echo $timePoint->getMinute(); // 30
echo $timePoint->getSecond(); // 0

// TimePoints are immutable - arithmetic returns new instances
$later = $timePoint->add(TimeSpan::fromSeconds(3600));
// $timePoint remains unchanged
```

### 5. Parsing and Formatting Dates

```php
$calendar = Calendar::fromProfile('gregorian');

// Parse various formats
$date1 = $calendar->parse('December 25, 2024');
$date2 = $calendar->parse('2024-12-25');
$date3 = $calendar->parse('25 Dec 2024');

// Format with custom patterns
echo $calendar->format($date1, 'F d, Y');    // December 25, 2024
echo $calendar->format($date1, 'Y-m-d');     // 2024-12-25
echo $calendar->format($date1, 'd/m/Y');     // 25/12/2024
echo $calendar->format($date1, 'l, F j, Y'); // Wednesday, December 25, 2024

// Time formatting
$withTime = new TimePoint($calendar, 2024, 12, 25, 14, 30, 0);
echo $calendar->format($withTime, 'Y-m-d H:i:s'); // 2024-12-25 14:30:00
```

### 6. Calendar Metadata

```php
$calendar = Calendar::fromProfile('faerun');

// Get calendar information
echo $calendar->getName();        // 'faerun'
echo $calendar->getDisplayName(); // 'Faerûn Harptos Calendar'
echo $calendar->getMonthCount();  // 17

// Get month names
$months = $calendar->getMonthNames();
foreach ($months as $number => $name) {
    echo "$number: $name\n";
}

// Check days in month
echo $calendar->getDaysInMonth(1, 1492); // 30 (Hammer has 30 days)
echo $calendar->getDaysInMonth(13, 1492); // 1 (Midwinter festival is 1 day)

// Epoch notation
$notation = $calendar->getEpochNotation();
echo $notation['after']; // 'DR' (Dale Reckoning)
```

### 7. Error Handling

```php
use Codryn\PHPCalendar\Exception\InvalidDateException;
use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;
use Codryn\PHPCalendar\Exception\InvalidCalendarConfigException;

try {
    $calendar = Calendar::fromProfile('gregorian');
    
    // Invalid date
    $date = $calendar->parse('February 30, 2024'); // Throws InvalidDateException
    
} catch (InvalidDateException $e) {
    echo "Invalid date: " . $e->getMessage();
}

try {
    $gregorian = Calendar::fromProfile('gregorian');
    $faerun = Calendar::fromProfile('faerun');
    
    $date1 = $gregorian->parse('2024-01-01');
    $date2 = $faerun->parse('1 Hammer 1492 DR');
    
    // Cannot compare dates from different calendars
    $span = $gregorian->diff($date1, $date2); // Throws IncompatibleCalendarException
    
} catch (IncompatibleCalendarException $e) {
    echo "Cannot mix calendars: " . $e->getMessage();
}
```

### 8. Practical Examples

#### Campaign Event Tracking

```php
$calendar = Calendar::fromProfile('faerun');

$campaignStart = $calendar->parse('1 Hammer 1492 DR');
$currentDate = $campaignStart;

// Track 30 in-game days
for ($i = 0; $i < 30; $i++) {
    echo "Day " . ($i + 1) . ": " . $calendar->format($currentDate, 'd F Y') . "\n";
    $currentDate = $currentDate->add(TimeSpan::fromSeconds(86400));
}
```

#### Age Calculator

```php
$calendar = Calendar::fromProfile('gregorian');

$birthDate = $calendar->parse('1990-01-15');
$today = $calendar->parse('2024-12-25');

$age = $calendar->diff($birthDate, $today);
$ageInYears = intdiv($age->getTotalDays(), 365);

echo "Age: $ageInYears years";
```

#### Historical Dating System

```php
$calendar = Calendar::fromProfile('dragonlance');

// Events before the Cataclysm use PC (Pre-Cataclysm)
$preCataclysm = $calendar->parse('100 Phoenix 0 PC');

// Events after use AC (After Cataclysm)
$postCataclysm = $calendar->parse('1 Phoenix 1 AC');
```

## Best Practices

1. **Reuse Calendar Instances**: Create calendar instances once and reuse them.
2. **Type Safety**: All methods have strict type hints; use them for IDE autocomplete.
3. **Immutability**: TimePoints and TimeSpans are immutable; operations return new instances.
4. **Exception Handling**: Always catch calendar exceptions in production code.
5. **Format Patterns**: Define format patterns as constants for consistency.

## See Also

- [API Documentation](API.md) - Complete API reference
- [Calendar Profiles](PROFILES.md) - Details on all built-in profiles
- [Custom Calendars](CUSTOM_CALENDARS.md) - Guide to creating custom calendars
