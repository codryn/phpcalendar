# Calendar Mapping and Date Conversion

PHPCalendar supports converting dates between different calendar systems using calendar mappings. This is useful when you need to translate dates from one calendar (e.g., Gregorian) to another (e.g., Faerûn's Harptos calendar).

## Basic Concepts

- **Calendar Mapping**: Defines how two calendar systems relate to each other using correlation dates
- **Correlation Date**: A pair of corresponding dates in both calendars that serve as reference points
- **Date Converter**: Manages multiple mappings and provides conversion between calendars

## Simple Example: Gregorian to Faerûn

```php
use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarMapping;
use Codryn\PHPCalendar\Calendar\CalendarMappingConfiguration;
use Codryn\PHPCalendar\Calendar\TimePoint;

// Create calendar instances
$gregorian = Calendar::fromProfile('gregorian');
$faerun = Calendar::fromProfile('faerun');

// Define the mapping between calendars
// January 1, 2024 (Gregorian) corresponds to 1 Hammer 1492 DR (Faerûn)
$config = new CalendarMappingConfiguration(
    sourceCalendarName: 'gregorian',
    targetCalendarName: 'faerun',
    correlationDate: [
        'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
    ]
);

// Create the mapping
$mapping = new CalendarMapping($config, $gregorian, $faerun);

// Convert a Gregorian date to Faerûn
$christmas = new TimePoint($gregorian, 2024, 12, 25);
$faerunDate = $mapping->convert($christmas);

echo $faerun->format($faerunDate); // Output: Date in Faerûn calendar

// Convert back to Gregorian
$gregorianDate = $mapping->reverseConvert($faerunDate);
echo $gregorian->format($gregorianDate, 'Y-m-d'); // Output: 2024-12-25
```

## Using DateConverter for Multiple Calendars

When working with multiple calendar systems, use `DateConverter` to manage all mappings:

```php
use Codryn\PHPCalendar\Calendar\DateConverter;

// Create calendars
$gregorian = Calendar::fromProfile('gregorian');
$faerun = Calendar::fromProfile('faerun');
$golarion = Calendar::fromProfile('golarion');

// Create date converter
$converter = new DateConverter();

// Register Gregorian <-> Faerûn mapping
$faerunConfig = new CalendarMappingConfiguration(
    sourceCalendarName: 'gregorian',
    targetCalendarName: 'faerun',
    correlationDate: [
        'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
    ]
);
$faerunMapping = new CalendarMapping($faerunConfig, $gregorian, $faerun);
$converter->registerMapping($faerunMapping);

// Register Gregorian <-> Golarion mapping
$golarionConfig = new CalendarMappingConfiguration(
    sourceCalendarName: 'gregorian',
    targetCalendarName: 'golarion',
    correlationDate: [
        'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'target' => ['year' => 4724, 'month' => 1, 'day' => 1],
    ]
);
$golarionMapping = new CalendarMapping($golarionConfig, $gregorian, $golarion);
$converter->registerMapping($golarionMapping);

// Check if conversion is possible
if ($converter->canConvert('gregorian', 'faerun')) {
    // Convert a date
    $date = new TimePoint($gregorian, 2024, 6, 15);
    $faerunDate = $converter->convert($date, $faerun);
    echo $faerun->format($faerunDate);
}

// Convert between any registered calendars
$date = new TimePoint($gregorian, 2024, 6, 15);
$golarionDate = $converter->convert($date, $golarion);
echo $golarion->format($golarionDate);
```

## Valid Date Ranges

You can restrict conversions to specific date ranges:

```php
$config = new CalendarMappingConfiguration(
    sourceCalendarName: 'gregorian',
    targetCalendarName: 'faerun',
    correlationDate: [
        'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
    ],
    validRange: [
        'min' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'max' => ['year' => 2024, 'month' => 12, 'day' => 31],
    ]
);

$mapping = new CalendarMapping($config, $gregorian, $faerun);

// This will work (date within range)
$validDate = new TimePoint($gregorian, 2024, 6, 15);
$converted = $mapping->convert($validDate);

// This will throw InvalidDateException (date outside range)
$invalidDate = new TimePoint($gregorian, 2025, 1, 1);
$mapping->convert($invalidDate); // Throws exception
```

## Converting Dates with Time

Calendar mappings preserve time components (hours, minutes, seconds):

```php
$mapping = new CalendarMapping($config, $gregorian, $faerun);

// Convert a date with time
$dateTime = new TimePoint($gregorian, 2024, 6, 15, 14, 30, 45);
$faerunDateTime = $mapping->convert($dateTime);

echo $faerunDateTime->getHour();   // 14
echo $faerunDateTime->getMinute(); // 30
echo $faerunDateTime->getSecond(); // 45
```

## Understanding Calendar Differences

When converting between calendars with different structures (e.g., Gregorian vs. Faerûn), be aware that:

- **Gregorian**: 365 days (366 in leap years), 12 months with varying days (28-31)
- **Faerûn**: 365 days (366 with Shieldmeet), 12 months of 30 days + 5 nameless festival days

Calendar mappings work by calculating the time difference from the correlation point. This ensures accurate conversion, but the resulting date may not have an exact 1:1 day/month correspondence due to structural differences (like nameless days).

However, **round-trip conversions always preserve the original date**:

```php
$originalDate = new TimePoint($gregorian, 2024, 7, 4);
$faerunDate = $mapping->convert($originalDate);
$backToGregorian = $mapping->reverseConvert($faerunDate);

// $backToGregorian will be exactly 2024-07-04
```

## Use Cases

Calendar mapping is perfect for:

1. **RPG Campaign Management**: Track in-game dates across different game worlds
2. **Historical Simulations**: Convert between historical calendar systems
3. **Fantasy Worldbuilding**: Maintain consistency when characters travel between worlds
4. **Cross-Calendar Scheduling**: Plan events that span multiple calendar systems

## API Reference

### CalendarMappingConfiguration

- `getSourceCalendarName()`: Get source calendar identifier
- `getTargetCalendarName()`: Get target calendar identifier
- `getCorrelationDate()`: Get the correlation date pair
- `getValidRange()`: Get optional valid date range
- `isBidirectional()`: Check if reverse conversion is supported

### CalendarMapping

- `convert(TimePoint $date)`: Convert from source to target calendar
- `reverseConvert(TimePoint $date)`: Convert from target to source calendar
- `getSourceCalendar()`: Get source calendar instance
- `getTargetCalendar()`: Get target calendar instance
- `getConfiguration()`: Get mapping configuration

### DateConverter

- `registerMapping(CalendarMapping $mapping)`: Register a calendar mapping
- `canConvert(string $source, string $target)`: Check if conversion is possible
- `convert(TimePoint $date, Calendar $target)`: Convert date to target calendar
- `getMapping(string $source, string $target)`: Get specific mapping
- `getMappings()`: Get all registered mappings

## Error Handling

```php
use Codryn\PHPCalendar\Exception\IncompatibleCalendarException;
use Codryn\PHPCalendar\Exception\InvalidDateException;

try {
    $converted = $mapping->convert($date);
} catch (IncompatibleCalendarException $e) {
    // Wrong calendar or no mapping exists
    echo "Calendar incompatible: " . $e->getMessage();
} catch (InvalidDateException $e) {
    // Date outside valid range or invalid date
    echo "Invalid date: " . $e->getMessage();
}
```
