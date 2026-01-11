# Creating Custom Calendars

Complete guide to creating your own calendar configurations.

## Quick Start

```php
use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarConfiguration;

$config = new CalendarConfiguration(
    name: 'my-calendar',
    displayName: 'My Custom Calendar',
    monthNames: [1 => 'Month1', 2 => 'Month2', 3 => 'Month3'],
    daysPerMonth: [1 => 30, 2 => 30, 3 => 30],
    leapYearRule: fn(int $year) => $year % 5 === 0,
    epochNotation: ['before' => 'BCE', 'after' => 'CE'],
    formatPatterns: ['d F Y', 'Y-m-d']
);

$calendar = Calendar::fromConfiguration($config);
```

## Configuration Parameters

### `name` (string)

Unique identifier for the calendar. Must be alphanumeric with hyphens or underscores only.

**Requirements:**
- Must not be empty
- Only letters, numbers, hyphens (-), and underscores (_)
- Should be lowercase by convention

**Examples:**
```php
name: 'mars-sol'           // ✓ Good
name: 'lunar-calendar'     // ✓ Good
name: 'custom_calendar_v2' // ✓ Good
name: 'My Calendar!'       // ✗ Invalid (spaces and special chars)
```

### `displayName` (string)

Human-readable name shown to users.

**Requirements:**
- Must not be empty
- Any characters allowed

**Examples:**
```php
displayName: 'Martian Solar Calendar'
displayName: 'Lunar Calendar (v2.0)'
displayName: 'Custom Fantasy World Calendar'
```

### `monthNames` (array<int, string>)

Month names indexed from 1.

**Requirements:**
- Keys must be consecutive integers starting from 1
- Must have at least 1 month
- Each month name must be non-empty

**Examples:**
```php
monthNames: [
    1 => 'Firstmonth',
    2 => 'Secondmonth',
    3 => 'Thirdmonth'
]

// For calendars with festival days as separate "months"
monthNames: [
    1 => 'Winter',
    2 => 'Midwinter Festival',
    3 => 'Spring',
    4 => 'Spring Festival',
    // ...
]
```

### `daysPerMonth` (array<int, int>)

Number of days in each month for non-leap years.

**Requirements:**
- Keys must match `monthNames` keys (1-indexed, consecutive)
- Each value must be ≥ 1
- Array length must match `monthNames` length

**Examples:**
```php
daysPerMonth: [
    1 => 30,  // Firstmonth has 30 days
    2 => 30,  // Secondmonth has 30 days
    3 => 30   // Thirdmonth has 30 days
]

// Variable month lengths
daysPerMonth: [
    1 => 31,
    2 => 28,  // Leap year rule will add 1 day
    3 => 31,
    4 => 30
]

// Festival days as 1-day "months"
daysPerMonth: [
    1 => 30,
    2 => 1,   // Festival day
    3 => 30,
    4 => 1,   // Festival day
]
```

### `leapYearRule` (?callable)

Function that determines if a year is a leap year. Optional.

**Signature:** `function(int $year): bool`

**Requirements:**
- Must accept an integer year
- Must return a boolean
- Can be null (no leap years)

**Examples:**

**No leap years:**
```php
leapYearRule: null
```

**Every 4 years:**
```php
leapYearRule: fn(int $year) => $year % 4 === 0
```

**Gregorian rule:**
```php
leapYearRule: fn(int $year) => 
    ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0)
```

**Every 8 years:**
```php
leapYearRule: fn(int $year) => $year % 8 === 0
```

**Custom complex rule:**
```php
leapYearRule: function(int $year): bool {
    // Leap year if year is divisible by 5, but not by 10
    if ($year % 10 === 0) return false;
    if ($year % 5 === 0) return true;
    return false;
}
```

### `epochNotation` (array{before: string, after: string})

Labels for years before and after epoch (year 0).

**Requirements:**
- Must have 'before' and 'after' keys
- Both values must be non-empty strings

**Examples:**
```php
epochNotation: ['before' => 'BCE', 'after' => 'CE']
epochNotation: ['before' => 'BC', 'after' => 'AD']
epochNotation: ['before' => 'BL', 'after' => 'AL']  // Before/After Landing
epochNotation: ['before' => 'Pre-War', 'after' => 'Post-War']
```

### `formatPatterns` (array<int, string>)

Default date format patterns for parsing and formatting.

**Requirements:**
- Must have at least one pattern
- Each pattern must be non-empty

**Common format characters:**
- `Y` - 4-digit year (2024)
- `y` - 2-digit year (24)
- `m` - 2-digit month (01-12)
- `n` - Month number (1-12)
- `F` - Full month name
- `M` - Short month name (first 3 letters)
- `d` - 2-digit day (01-31)
- `j` - Day number (1-31)
- `H` - 24-hour format (00-23)
- `i` - Minutes (00-59)
- `s` - Seconds (00-59)

**Examples:**
```php
formatPatterns: ['d F Y', 'Y-m-d']
formatPatterns: ['F d, Y', 'd/m/Y', 'Y-m-d H:i:s']
formatPatterns: ['d M Y', 'j F Y', 'Y.m.d']
```

### `namelessDays` (array) [Optional]

Configuration for nameless days (intercalary days) that exist outside the regular month structure.

**Requirements:**
- Array of groups, each with:
  - `position` (int): After which month the nameless days occur (0 = start of year)
  - `names` (array<int, string>): Names of the nameless days (1-indexed)
  - `leap` (bool): Whether an extra nameless day is added in leap years

**Default:** `[]` (no nameless days)

**Use Cases:**
- Fantasy calendar festivals (e.g., Faerûn's Harptos calendar)
- Historical calendars with intercalary days
- Custom calendars that need days outside the month structure

**Examples:**

**5 nameless days at year end:**
```php
namelessDays: [
    [
        'position' => 12,  // After the 12th month
        'names' => [
            1 => 'First Nameless Day',
            2 => 'Second Nameless Day',
            3 => 'Third Nameless Day',
            4 => 'Fourth Nameless Day',
            5 => 'Fifth Nameless Day',
        ],
        'leap' => false,  // No extra day in leap years
    ],
]
```

**Multiple festival periods:**
```php
namelessDays: [
    [
        'position' => 1,  // After month 1
        'names' => [1 => 'Midwinter'],
        'leap' => false,
    ],
    [
        'position' => 7,  // After month 7
        'names' => [1 => 'Midsummer'],
        'leap' => true,  // Add Shieldmeet in leap years
    ],
    [
        'position' => 12,  // After month 12
        'names' => [1 => 'Year End Festival'],
        'leap' => false,
    ],
]
```

**Nameless days with custom names:**
```php
namelessDays: [
    [
        'position' => 6,
        'names' => [
            1 => 'Festival of Light',
            2 => 'Day of Remembrance',
            3 => 'Celebration Day',
        ],
        'leap' => false,
    ],
]
```

**How nameless days work:**
- Nameless days are automatically included in year length calculations
- Date arithmetic (adding/subtracting days) accounts for nameless days
- When calculating differences between dates, nameless days are counted
- Nameless days exist between months but don't belong to any specific month

## Complete Examples

### Example 1: Martian Calendar

A calendar for Mars with 12 months totaling 668 sols (Martian days).

```php
$marsConfig = new CalendarConfiguration(
    name: 'mars-sol',
    displayName: 'Martian Solar Calendar',
    monthNames: [
        1 => 'Ares',
        2 => 'Phobos',
        3 => 'Deimos',
        4 => 'Olympus',
        5 => 'Valles',
        6 => 'Mariner',
        7 => 'Viking',
        8 => 'Sojourner',
        9 => 'Spirit',
        10 => 'Opportunity',
        11 => 'Curiosity',
        12 => 'Perseverance'
    ],
    daysPerMonth: [
        1 => 56, 2 => 56, 3 => 56, 4 => 56,
        5 => 56, 6 => 56, 7 => 56, 8 => 56,
        9 => 56, 10 => 56, 11 => 56, 12 => 52
    ],
    leapYearRule: fn(int $year) => $year % 10 === 0,
    epochNotation: ['before' => 'BL', 'after' => 'AL'], // Before/After Landing
    formatPatterns: ['d F Y AL', 'Y-m-d']
);

$marsCalendar = Calendar::fromConfiguration($marsConfig);

$landingDay = $marsCalendar->parse('1 Ares 1 AL');
echo $marsCalendar->format($landingDay); // 1 Ares 1 AL
```

### Example 2: Lunar Calendar

A simple lunar calendar with 13 months of 28 days each.

```php
$lunarConfig = new CalendarConfiguration(
    name: 'lunar-simple',
    displayName: 'Simple Lunar Calendar',
    monthNames: [
        1 => 'First Moon',
        2 => 'Second Moon',
        3 => 'Third Moon',
        4 => 'Fourth Moon',
        5 => 'Fifth Moon',
        6 => 'Sixth Moon',
        7 => 'Seventh Moon',
        8 => 'Eighth Moon',
        9 => 'Ninth Moon',
        10 => 'Tenth Moon',
        11 => 'Eleventh Moon',
        12 => 'Twelfth Moon',
        13 => 'Thirteenth Moon'
    ],
    daysPerMonth: [
        1 => 28, 2 => 28, 3 => 28, 4 => 28,
        5 => 28, 6 => 28, 7 => 28, 8 => 28,
        9 => 28, 10 => 28, 11 => 28, 12 => 28,
        13 => 28
    ],
    leapYearRule: null, // No leap years
    epochNotation: ['before' => 'BM', 'after' => 'AM'], // Before/After Moon
    formatPatterns: ['j F Y', 'Y-m-d']
);

$lunarCalendar = Calendar::fromConfiguration($lunarConfig);
```

### Example 3: Fantasy World Calendar

A fantasy calendar with seasons and festivals.

```php
$fantasyConfig = new CalendarConfiguration(
    name: 'realm-of-eldoria',
    displayName: 'Eldorian Calendar',
    monthNames: [
        1 => 'Frostmoon',
        2 => 'Winterfest',      // 1-day festival
        3 => 'Thawmoon',
        4 => 'Blossommoon',
        5 => 'Springtide',      // 3-day festival
        6 => 'Sunmoon',
        7 => 'Harvestmoon',
        8 => 'Summerfest',      // 1-day festival
        9 => 'Goldmoon',
        10 => 'Leafmoon',
        11 => 'Harvestide',     // 3-day festival
        12 => 'Darkmoon'
    ],
    daysPerMonth: [
        1 => 30,
        2 => 1,   // Festival
        3 => 30,
        4 => 30,
        5 => 3,   // Festival
        6 => 30,
        7 => 30,
        8 => 1,   // Festival
        9 => 30,
        10 => 30,
        11 => 3,  // Festival
        12 => 30
    ],
    leapYearRule: fn(int $year) => $year % 6 === 0,
    epochNotation: ['before' => 'BE', 'after' => 'AE'], // Before/After Empire
    formatPatterns: ['d F Y AE', 'j F, Y']
);

$eldorianCalendar = Calendar::fromConfiguration($fantasyConfig);

$date = $eldorianCalendar->parse('15 Sunmoon 723 AE');
echo $eldorianCalendar->format($date, 'j F, Y'); // 15 Sunmoon, 723
```

### Example 4: Calendar with Nameless Days

A fantasy calendar with nameless days (intercalary days) between months.

```php
$config = new CalendarConfiguration(
    name: 'harpthos-inspired',
    displayName: 'Harpthos-Inspired Fantasy Calendar',
    monthNames: [
        1 => 'Deepwinter',
        2 => 'Latewinter',
        3 => 'Ches',
        4 => 'Tarsakh',
        5 => 'Mirtul',
        6 => 'Kythorn',
        7 => 'Flamerule',
        8 => 'Eleasis',
        9 => 'Eleint',
        10 => 'Marpenoth',
        11 => 'Uktar',
        12 => 'Nightal'
    ],
    daysPerMonth: [
        1 => 30, 2 => 30, 3 => 30, 4 => 30,
        5 => 30, 6 => 30, 7 => 30, 8 => 30,
        9 => 30, 10 => 30, 11 => 30, 12 => 30
    ],
    leapYearRule: fn(int $year) => $year % 4 === 0,
    epochNotation: ['before' => 'Before DR', 'after' => 'DR'],
    formatPatterns: ['j F Y \D\R'],
    namelessDays: [
        [
            'position' => 1,  // After Deepwinter
            'names' => [1 => 'Midwinter'],
            'leap' => false,
        ],
        [
            'position' => 4,  // After Tarsakh
            'names' => [1 => 'Greengrass'],
            'leap' => false,
        ],
        [
            'position' => 7,  // After Flamerule
            'names' => [1 => 'Midsummer'],
            'leap' => true,  // Shieldmeet in leap years
        ],
        [
            'position' => 9,  // After Eleint
            'names' => [1 => 'Highharvestide'],
            'leap' => false,
        ],
        [
            'position' => 11,  // After Uktar
            'names' => [1 => 'Feast of the Moon'],
            'leap' => false,
        ],
    ]
);

$calendar = Calendar::fromConfiguration($config);

// A year has 360 days in months + 5 festivals = 365 days (366 in leap years)
$date1 = $calendar->parse('1 Deepwinter 1492 DR');
$date2 = $calendar->parse('1 Deepwinter 1493 DR');
$span = $calendar->diff($date1, $date2);

echo $span->getTotalDays(); // 366 (leap year includes Shieldmeet)
```

### Example 5: Decimal Calendar

A calendar based on decimal system (10 months, 10-day weeks).

```php
$decimalConfig = new CalendarConfiguration(
    name: 'decimal',
    displayName: 'Revolutionary Decimal Calendar',
    monthNames: [
        1 => 'Primidi',
        2 => 'Duodi',
        3 => 'Tridi',
        4 => 'Quartidi',
        5 => 'Quintidi',
        6 => 'Sextidi',
        7 => 'Septidi',
        8 => 'Octidi',
        9 => 'Nonidi',
        10 => 'Decadi'
    ],
    daysPerMonth: [
        1 => 36, 2 => 36, 3 => 36, 4 => 36, 5 => 37,
        6 => 36, 7 => 36, 8 => 36, 9 => 36, 10 => 36
    ],
    leapYearRule: fn(int $year) => $year % 4 === 0,
    epochNotation: ['before' => 'BR', 'after' => 'AR'], // Before/After Revolution
    formatPatterns: ['d-m-Y', 'j F Y AR']
);

$decimalCalendar = Calendar::fromConfiguration($decimalConfig);
```

## Validation Rules

The `CalendarValidator` automatically validates your configuration:

### Name Validation
- ✓ Must not be empty
- ✓ Only alphanumeric, hyphens, underscores
- ✗ No spaces or special characters

### Month Names Validation
- ✓ Keys start at 1 and are consecutive
- ✓ At least 1 month
- ✓ All names non-empty
- ✗ No gaps in keys (e.g., 1, 2, 4 is invalid)

### Days Per Month Validation
- ✓ Keys match month names keys
- ✓ All values ≥ 1
- ✓ Same length as month names
- ✗ Days cannot be zero or negative

### Epoch Notation Validation
- ✓ Has 'before' and 'after' keys
- ✓ Both values non-empty
- ✗ Missing keys or empty values

### Format Patterns Validation
- ✓ At least one pattern
- ✓ All patterns non-empty
- ✗ Empty array or empty patterns

## Error Handling

```php
use Codryn\PHPCalendar\Exception\InvalidCalendarConfigException;

try {
    $config = new CalendarConfiguration(
        name: 'invalid name!',  // Invalid: contains space and exclamation
        displayName: 'Test',
        monthNames: [1 => 'Month1'],
        daysPerMonth: [1 => 30],
        leapYearRule: null,
        epochNotation: ['before' => 'B', 'after' => 'A'],
        formatPatterns: ['Y-m-d']
    );
    
    $calendar = Calendar::fromConfiguration($config);
} catch (InvalidCalendarConfigException $e) {
    echo "Configuration error: " . $e->getMessage();
    // "Calendar name must only contain alphanumeric characters, hyphens, and underscores"
}
```

## Best Practices

1. **Keep month names unique** for easier parsing
2. **Use consistent naming conventions** (e.g., all lowercase or title case)
3. **Document your leap year rule** if it's complex
4. **Provide multiple format patterns** for flexibility
5. **Test your calendar configuration** with edge cases
6. **Consider time zones** if your calendar needs them (future feature)
7. **Use descriptive epoch notations** that make sense in context

## Advanced: Leap Year Rules

### Common Patterns

**Every N years:**
```php
leapYearRule: fn(int $year) => $year % $n === 0
```

**Gregorian (complex rule):**
```php
leapYearRule: fn(int $year) => 
    ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0)
```

**Islamic (no leap year pattern, tabular):**
```php
leapYearRule: fn(int $year) => in_array($year % 30, [2, 5, 7, 10, 13, 16, 18, 21, 24, 26, 29])
```

**Custom with exceptions:**
```php
leapYearRule: function(int $year): bool {
    // Every 5 years, except multiples of 50, unless multiple of 250
    if ($year % 250 === 0) return true;
    if ($year % 50 === 0) return false;
    return $year % 5 === 0;
}
```

## See Also

- [API Documentation](API.md) - Complete API reference
- [Usage Guide](USAGE.md) - Common usage patterns
- [Profile Reference](PROFILES.md) - Built-in calendar profiles
