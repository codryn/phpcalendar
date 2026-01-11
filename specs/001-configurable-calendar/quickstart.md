# Quickstart Guide: PHPCalendar

**Package**: codryn/phpcalendar  
**Minimum PHP**: 8.0  
**Installation**: `composer require codryn/phpcalendar`

## 5-Minute Quickstart

### 1. Install via Composer

```bash
composer require codryn/phpcalendar
```

### 2. Create a Calendar

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Codryn\PHPCalendar\Calendar\Calendar;

// Use a pre-built calendar profile
$calendar = Calendar::fromProfile('gregorian');
```

### 3. Parse Dates

```php
// Parse a date string
$birthday = $calendar->parse('1990-05-15');

echo "Year: " . $birthday->getYear();   // 1990
echo "Month: " . $birthday->getMonth(); // 5
echo "Day: " . $birthday->getDay();     // 15
```

### 4. Format Dates

```php
// Format a date
$formatted = $calendar->format($birthday);
echo $formatted; // "May 15, 1990"

// Custom format
$custom = $calendar->format($birthday, 'd/m/Y');
echo $custom; // "15/05/1990"
```

### 5. Calculate Time Differences

```php
$start = $calendar->parse('2024-01-01');
$end = $calendar->parse('2024-12-31');

$diff = $calendar->diff($start, $end);
echo $diff->getTotalDays() . " days"; // "365 days"
```

## Common Use Cases

### Calculate Age

```php
$calendar = Calendar::fromProfile('gregorian');
$birthdate = $calendar->parse('1990-05-15');
$today = $calendar->parse('2024-01-08');

$age = $calendar->diff($birthdate, $today);
echo "Age: " . floor($age->getTotalDays() / 365.25) . " years";
```

### Add Days to a Date

```php
use Codryn\PHPCalendar\Calendar\TimeSpan;

$start = $calendar->parse('2024-01-15');
$tenDays = TimeSpan::fromSeconds(86400 * 10);
$future = $start->add($tenDays);

echo $calendar->format($future); // "January 25, 2024"
```

### Subtract Time

```php
$start = $calendar->parse('2024-01-15');
$oneWeek = TimeSpan::fromSeconds(86400 * 7);
$past = $start->subtract($oneWeek);

echo $calendar->format($past); // "January 8, 2024"
```

### Compare Dates

```php
$date1 = $calendar->parse('2024-01-01');
$date2 = $calendar->parse('2024-12-31');

if ($date1->isBefore($date2)) {
    echo "Date1 is before Date2";
}

if ($date2->isAfter($date1)) {
    echo "Date2 is after Date1";
}

if ($date1->equals($date1)) {
    echo "Dates are equal";
}
```

## Fantasy Calendar Examples

### Faerûn (Forgotten Realms) Calendar

```php
$faerun = Calendar::fromProfile('faerun');
$date = $faerun->parse('15 Mirtul 1492 DR');

echo "Month: " . $faerun->getMonthNames()[$date->getMonth() - 1]; // "Mirtul"
echo $faerun->format($date); // "15 Mirtul 1492 DR"
```

### Golarion (Pathfinder) Calendar

```php
$golarion = Calendar::fromProfile('golarion');
$date = $golarion->parse('10 Abadius 4724 AR');

echo $golarion->format($date); // "10 Abadius 4724 AR"
```

### Das Schwarze Auge (The Dark Eye)

```php
$dsa = Calendar::fromProfile('dsa');
$date = $dsa->parse('1 Praios 1000 BF');

echo $dsa->format($date); // "1 Praios 1000 BF"
```

### Eberron Calendar

```php
$eberron = Calendar::fromProfile('eberron');
$date = $eberron->parse('1 Zarantyr 998 YK');

echo $eberron->format($date); // "1 Zarantyr 998 YK"
```

## Custom Calendar Creation

```php
use Codryn\PHPCalendar\Calendar\CalendarConfiguration;

$config = new CalendarConfiguration(
    name: 'my-calendar',
    displayName: 'My Custom Calendar',
    monthNames: [
        'First Month',
        'Second Month',
        'Third Month'
    ],
    daysPerMonth: [30, 30, 30],
    leapYearRule: fn(int $year) => $year % 5 === 0,
    epochNotation: [
        'before' => 'Before Era',
        'after' => 'After Era'
    ],
    formatPatterns: ['d M Y', 'd/m/Y']
);

$customCalendar = Calendar::fromConfiguration($config);
$date = $customCalendar->parse('15 First Month 100');
echo $customCalendar->format($date);
```

## Error Handling

### Invalid Dates

```php
use Codryn\PHPCalendar\Calendar\Exception\InvalidDateException;

try {
    $date = $calendar->parse('February 30, 2024'); // Doesn't exist!
} catch (InvalidDateException $e) {
    echo "Error: " . $e->getMessage();
    // "Invalid date: February only has 29 days in 2024"
}
```

### Cross-Calendar Operations

```php
use Codryn\PHPCalendar\Calendar\Exception\IncompatibleCalendarException;

$gregorian = Calendar::fromProfile('gregorian');
$faerun = Calendar::fromProfile('faerun');

$date1 = $gregorian->parse('2024-01-01');
$date2 = $faerun->parse('1 Hammer 1492 DR');

try {
    $diff = $gregorian->diff($date1, $date2);
} catch (IncompatibleCalendarException $e) {
    echo "Error: Cannot mix calendars";
}
```

### Invalid Date Arithmetic

```php
use Codryn\PHPCalendar\Calendar\Exception\DateArithmeticException;

$date = $calendar->parse('2024-01-31');
$oneMonth = TimeSpan::fromSeconds(86400 * 31);

try {
    $result = $date->add($oneMonth); // Would be Feb 31 - invalid!
} catch (DateArithmeticException $e) {
    echo "Error: " . $e->getMessage();
    // "Date arithmetic resulted in invalid date: February 31, 2024 does not exist"
}
```

## Available Calendar Profiles

| Profile Name | Calendar System | Example Date |
|-------------|-----------------|--------------|
| `gregorian` | Gregorian Calendar | "December 25, 2024" |
| `faerun` | Harptos (Forgotten Realms) | "15 Mirtul 1492 DR" |
| `golarion` | Absalom Reckoning (Pathfinder) | "10 Abadius 4724 AR" |
| `dsa` | Aventurian Calendar (The Dark Eye) | "1 Praios 1000 BF" |
| `eberron` | Galifar Calendar | "1 Zarantyr 998 YK" |
| `dragonlance` | Krynn Calendar | "1 Brookgreen 352 AC" |
| `greyhawk` | Common Year (Greyhawk) | "1 Needfest 591 CY" |

## Best Practices

### 1. Keep TimePoints in Same Calendar

```php
// GOOD: Same calendar
$gregorian = Calendar::fromProfile('gregorian');
$date1 = $gregorian->parse('2024-01-01');
$date2 = $gregorian->parse('2024-12-31');
$diff = $gregorian->diff($date1, $date2); // Works!

// BAD: Different calendars
$faerun = Calendar::fromProfile('faerun');
$date3 = $faerun->parse('1 Hammer 1492 DR');
$diff = $gregorian->diff($date1, $date3); // Throws exception!
```

### 2. Handle Exceptions

Always wrap calendar operations in try-catch blocks when dealing with user input:

```php
try {
    $date = $calendar->parse($userInput);
    $formatted = $calendar->format($date);
    echo $formatted;
} catch (InvalidDateException $e) {
    echo "Invalid date provided";
} catch (CalendarException $e) {
    echo "Calendar error: " . $e->getMessage();
}
```

### 3. Use TimeSpan for Durations

```php
// GOOD: Explicit duration
$oneDay = TimeSpan::fromSeconds(86400);
$tomorrow = $today->add($oneDay);

// AVOID: Magic numbers
$tomorrow = $today->add(TimeSpan::fromSeconds(86400)); // Less clear
```

### 4. Cache Calendar Instances

```php
// Calendar instances are immutable and reusable
class CalendarService {
    private Calendar $gregorian;
    
    public function __construct() {
        $this->gregorian = Calendar::fromProfile('gregorian');
    }
    
    public function parseDate(string $input): TimePoint {
        return $this->gregorian->parse($input);
    }
}
```

## Next Steps

- **API Documentation**: See [contracts/](contracts/) for detailed API specifications
- **Data Model**: See [data-model.md](data-model.md) for entity relationships
- **Advanced Usage**: See [README.md](../../../README.md) for advanced patterns
- **Contributing**: See [CONTRIBUTING.md](../../../CONTRIBUTING.md) for development guidelines

## Support

- **Issues**: https://github.com/codryn/phpcalendar/issues
- **Discussions**: https://github.com/codryn/phpcalendar/discussions
- **Documentation**: https://phpcalendar.codryn.dev
