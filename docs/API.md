# PHPCalendar API Documentation

Complete API reference for PHPCalendar library.

## Table of Contents

- [Core Classes](#core-classes)
  - [Calendar](#calendar)
  - [TimePoint](#timepoint)
  - [TimeSpan](#timespan)
  - [CalendarConfiguration](#calendarconfiguration)
- [Calendar Profiles](#calendar-profiles)
  - [GregorianProfile](#gregorianprofile)
  - [FaerunProfile](#faerunprofile)
  - [GolarionProfile](#golarionprofile)
  - [DSAProfile](#dsaprofile)
  - [EberronProfile](#eberronprofile)
  - [DragonlanceProfile](#dragonlanceprofile)
  - [GreyhawkProfile](#greyhawkprofile)
- [Validators](#validators)
- [Exceptions](#exceptions)

## Core Classes

### Calendar

The main entry point for calendar operations.

#### Static Factory Methods

##### `fromProfile(string $profileName): Calendar`

Create a calendar from a pre-built profile.

```php
use Codryn\PHPCalendar\Calendar\Calendar;

$calendar = Calendar::fromProfile('gregorian');
$calendar = Calendar::fromProfile('faerun');
$calendar = Calendar::fromProfile('golarion');
```

**Parameters:**
- `$profileName` (string): Profile identifier ('gregorian', 'faerun', 'golarion', 'dsa', 'eberron', 'dragonlance', 'greyhawk')

**Returns:** `Calendar` instance

**Throws:** `InvalidArgumentException` if profile not found

##### `fromConfiguration(CalendarConfiguration $config): Calendar`

Create a calendar from custom configuration.

```php
use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarConfiguration;

$config = new CalendarConfiguration(
    name: 'my-calendar',
    displayName: 'My Custom Calendar',
    monthNames: [1 => 'Firstmonth', 2 => 'Secondmonth', 3 => 'Thirdmonth'],
    daysPerMonth: [1 => 30, 2 => 30, 3 => 30],
    leapYearRule: fn(int $year) => $year % 5 === 0,
    epochNotation: ['before' => 'BCE', 'after' => 'CE'],
    formatPatterns: ['d F Y', 'Y-m-d']
);

$calendar = Calendar::fromConfiguration($config);
```

**Parameters:**
- `$config` (CalendarConfiguration): Custom calendar configuration

**Returns:** `Calendar` instance

**Throws:** `InvalidCalendarConfigException` if configuration is invalid

#### Instance Methods

##### `getName(): string`

Get the calendar's identifier.

```php
$name = $calendar->getName(); // 'gregorian'
```

##### `getDisplayName(): string`

Get the human-readable calendar name.

```php
$displayName = $calendar->getDisplayName(); // 'Gregorian Calendar'
```

##### `getMonthNames(): array<int, string>`

Get all month names indexed from 1.

```php
$months = $calendar->getMonthNames();
// [1 => 'January', 2 => 'February', ...]
```

##### `getMonthCount(): int`

Get the number of months in the calendar.

```php
$count = $calendar->getMonthCount(); // 12
```

##### `getDaysInMonth(int $month, int $year): int`

Get the number of days in a specific month/year.

```php
$days = $calendar->getDaysInMonth(2, 2024); // 29 (leap year)
$days = $calendar->getDaysInMonth(2, 2023); // 28
```

##### `isLeapYear(int $year): bool`

Check if a year is a leap year.

```php
$isLeap = $calendar->isLeapYear(2024); // true
$isLeap = $calendar->isLeapYear(2023); // false
```

##### `parse(string $dateString): TimePoint`

Parse a date string into a TimePoint.

```php
$timePoint = $calendar->parse('December 25, 2024');
$timePoint = $calendar->parse('2024-12-25');
```

**Throws:** `InvalidDateException` if date cannot be parsed

##### `format(TimePoint $point, ?string $pattern = null): string`

Format a TimePoint to a string.

```php
$formatted = $calendar->format($timePoint, 'F d, Y'); // 'December 25, 2024'
$formatted = $calendar->format($timePoint, 'Y-m-d'); // '2024-12-25'
```

**Throws:** `IncompatibleCalendarException` if TimePoint from different calendar

##### `diff(TimePoint $start, TimePoint $end): TimeSpan`

Calculate the difference between two TimePoints.

```php
$start = $calendar->parse('2024-01-01');
$end = $calendar->parse('2024-12-31');
$span = $calendar->diff($start, $end);
echo $span->getTotalDays(); // 365
```

**Throws:** `IncompatibleCalendarException` if TimePoints from different calendars

##### `getEpochNotation(): array{before: string, after: string}`

Get the epoch notation (e.g., BCE/CE, DR, AR).

```php
$notation = $calendar->getEpochNotation();
// ['before' => 'BCE', 'after' => 'CE']
```

---

### TimePoint

Represents an immutable point in time.

#### Constructor

```php
use Codryn\PHPCalendar\Calendar\TimePoint;

$timePoint = new TimePoint(
    calendar: $calendar,
    year: 2024,
    month: 12,
    day: 25,
    hour: 14,
    minute: 30,
    second: 0,
    microsecond: 0
);
```

**Throws:** `InvalidDateException` if date is invalid

#### Methods

##### `getCalendar(): Calendar`

Get the associated calendar.

##### `getYear(): int`

Get the year component.

##### `getMonth(): int`

Get the month component (1-based).

##### `getDay(): int`

Get the day component (1-based).

##### `getHour(): int`

Get the hour component (0-23).

##### `getMinute(): int`

Get the minute component (0-59).

##### `getSecond(): int`

Get the second component (0-59).

##### `getMicrosecond(): int`

Get the microsecond component (0-999999).

##### `add(TimeSpan $span): TimePoint`

Add a TimeSpan to this TimePoint, returning a new TimePoint.

```php
$future = $timePoint->add(TimeSpan::fromSeconds(86400)); // Add 1 day
```

##### `subtract(TimeSpan $span): TimePoint`

Subtract a TimeSpan from this TimePoint, returning a new TimePoint.

```php
$past = $timePoint->subtract(TimeSpan::fromSeconds(3600)); // Subtract 1 hour
```

---

### TimeSpan

Represents a duration of time.

#### Static Factory Methods

##### `fromSeconds(int $seconds, int $microseconds = 0): TimeSpan`

Create a TimeSpan from seconds and microseconds.

```php
use Codryn\PHPCalendar\Calendar\TimeSpan;

$span = TimeSpan::fromSeconds(86400); // 1 day
$span = TimeSpan::fromSeconds(3600, 500000); // 1 hour + 500ms
```

#### Methods

##### `getTotalSeconds(): int`

Get total seconds (excluding microseconds).

##### `getMicroseconds(): int`

Get the microsecond component.

##### `getTotalDays(): int`

Get total days (rounded down).

```php
$days = $span->getTotalDays();
```

##### `getTotalHours(): int`

Get total hours (rounded down).

##### `getTotalMinutes(): int`

Get total minutes (rounded down).

##### `isNegative(): bool`

Check if the span is negative.

##### `abs(): TimeSpan`

Get the absolute value of the span.

##### `negate(): TimeSpan`

Negate the span (positive becomes negative, vice versa).

---

### CalendarConfiguration

Value object for custom calendar configuration.

#### Constructor

```php
use Codryn\PHPCalendar\Calendar\CalendarConfiguration;

$config = new CalendarConfiguration(
    name: 'my-calendar',
    displayName: 'My Custom Calendar',
    monthNames: [1 => 'Month1', 2 => 'Month2', ...],
    daysPerMonth: [1 => 30, 2 => 30, ...],
    leapYearRule: fn(int $year) => $year % 4 === 0,
    epochNotation: ['before' => 'Before', 'after' => 'After'],
    formatPatterns: ['d F Y', 'Y-m-d']
);
```

**Parameters:**
- `name` (string): Unique identifier (alphanumeric, hyphens, underscores only)
- `displayName` (string): Human-readable name
- `monthNames` (array<int, string>): Month names indexed from 1
- `daysPerMonth` (array<int, int>): Days per month indexed from 1
- `leapYearRule` (?callable): Function to determine leap years (optional)
- `epochNotation` (array{before: string, after: string}): Epoch labels
- `formatPatterns` (array<int, string>): Date format patterns

---

## Calendar Profiles

All fantasy RPG calendar profiles include copyright notices that can be accessed via the `getCopyrightNotice()` method. These notices acknowledge that the calendar names and terminology are used solely for non-commercial purposes to help game masters keep track of their campaigns.

### GregorianProfile

Standard Gregorian calendar with proper leap year rules.

```php
$calendar = Calendar::fromProfile('gregorian');
```

- **12 months** with varying days (28-31)
- **Leap year**: Divisible by 4, except century years unless divisible by 400
- **Epoch**: BCE/CE
- **Copyright**: None (real-world calendar)

### FaerunProfile

Faerûn Harptos calendar from Forgotten Realms.

```php
$calendar = Calendar::fromProfile('faerun');

// Access copyright notice
$profile = new \Codryn\PHPCalendar\Profile\FaerunProfile();
$copyright = $profile->getCopyrightNotice();
```

- **12 months** of 30 days each + 5 festivals
- **Leap year**: Every 4 years (Shieldmeet)
- **Epoch**: Dale Reckoning (DR)
- **Copyright**: Wizards of the Coast LLC (D&D, Forgotten Realms)

### GolarionProfile

Golarion Absalom Reckoning from Pathfinder.

```php
$calendar = Calendar::fromProfile('golarion');

// Access copyright notice
$profile = new \Codryn\PHPCalendar\Profile\GolarionProfile();
$copyright = $profile->getCopyrightNotice();
```

- **12 months** with varying days (28-31)
- **Leap year**: Every 8 years
- **Epoch**: Absalom Reckoning (AR)
- **Copyright**: Paizo Inc. (Pathfinder, Golarion)

### DSAProfile

Das Schwarze Auge (The Dark Eye) Aventurian calendar.

```php
$calendar = Calendar::fromProfile('dsa');

// Access copyright notice
$profile = new \Codryn\PHPCalendar\Profile\DSAProfile();
$copyright = $profile->getCopyrightNotice();
```

- **12 months** of 30 days each + 5 nameless days
- **No leap years**
- **Epoch**: Bosparans Fall (BF)
- **Copyright**: Ulisses Spiele GmbH (Das Schwarze Auge, The Dark Eye)

### EberronProfile

Eberron Galifar Calendar.

```php
$calendar = Calendar::fromProfile('eberron');

// Access copyright notice
$profile = new \Codryn\PHPCalendar\Profile\EberronProfile();
$copyright = $profile->getCopyrightNotice();
```

- **12 months** of 28 days each (336 days/year)
- **No leap years**
- **Epoch**: Years of Kingdom (YK)
- **Copyright**: Wizards of the Coast LLC (D&D, Eberron)

### DragonlanceProfile

Dragonlance Krynn calendar.

```php
$calendar = Calendar::fromProfile('dragonlance');

// Access copyright notice
$profile = new \Codryn\PHPCalendar\Profile\DragonlanceProfile();
$copyright = $profile->getCopyrightNotice();
```

- **12 months** with varying days (28-31)
- **Leap year**: Gregorian rules
- **Epoch**: After/Pre Cataclysm (AC/PC)
- **Copyright**: Wizards of the Coast LLC (D&D, Dragonlance)

### GreyhawkProfile

World of Greyhawk Common Year calendar.

```php
$calendar = Calendar::fromProfile('greyhawk');

// Access copyright notice
$profile = new \Codryn\PHPCalendar\Profile\GreyhawkProfile();
$copyright = $profile->getCopyrightNotice();
```

- **12 regular months** (28 days) + 4 festival weeks (7 days)
- **No leap years**
- **Epoch**: Common Year (CY)
- **Copyright**: Wizards of the Coast LLC (D&D, Greyhawk)

---

## Validators

### CalendarValidator

Validates custom calendar configurations.

```php
use Codryn\PHPCalendar\Validator\CalendarValidator;

$validator = new CalendarValidator();
$validator->validate($config); // Throws InvalidCalendarConfigException if invalid
```

---

## Exceptions

All exceptions extend `CalendarException`.

### CalendarException

Base exception for all calendar-related errors.

### InvalidDateException

Thrown when a date is invalid (e.g., February 30, invalid time components).

### InvalidCalendarConfigException

Thrown when a custom calendar configuration is invalid.

### IncompatibleCalendarException

Thrown when operations are attempted between TimePoints from different calendars.

### DateArithmeticException

Thrown when date arithmetic produces an invalid result.

---

## Format Patterns

Common format patterns used in `format()`:

- `Y` - 4-digit year (2024)
- `y` - 2-digit year (24)
- `m` - 2-digit month (01-12)
- `n` - Month number (1-12)
- `F` - Full month name (January)
- `M` - Short month name (Jan)
- `d` - 2-digit day (01-31)
- `j` - Day number (1-31)
- `H` - 24-hour format (00-23)
- `i` - Minutes (00-59)
- `s` - Seconds (00-59)

Example: `'F d, Y'` → `'December 25, 2024'`
