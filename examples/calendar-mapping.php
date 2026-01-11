<?php

declare(strict_types=1);

/**
 * Example: Calendar Mapping and Date Conversion
 *
 * This example demonstrates how to convert dates between different calendar systems
 * using PHPCalendar's mapping and conversion features.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarMapping;
use Codryn\PHPCalendar\Calendar\CalendarMappingConfiguration;
use Codryn\PHPCalendar\Calendar\DateConverter;
use Codryn\PHPCalendar\Calendar\TimePoint;

echo "=== Calendar Mapping Example ===" . PHP_EOL . PHP_EOL;

// Example 1: Simple conversion between Gregorian and Faerûn
echo "Example 1: Converting Gregorian to Faerûn Calendar" . PHP_EOL;
echo str_repeat('-', 50) . PHP_EOL;

$gregorian = Calendar::fromProfile('gregorian');
$faerun = Calendar::fromProfile('faerun');

// Define correlation: Jan 1, 2024 (Gregorian) = 1 Hammer 1492 DR (Faerûn)
$config = new CalendarMappingConfiguration(
    sourceCalendarName: 'gregorian',
    targetCalendarName: 'faerun',
    correlationDate: [
        'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
    ],
);

$mapping = new CalendarMapping($config, $gregorian, $faerun);

// Convert a date
$christmas = new TimePoint($gregorian, 2024, 12, 25, 12, 0, 0);
echo "Gregorian: " . $gregorian->format($christmas, 'F j, Y \a\t H:i') . PHP_EOL;

$faerunDate = $mapping->convert($christmas);
echo "Faerûn:    " . $faerun->format($faerunDate) . PHP_EOL;

// Convert back
$backToGregorian = $mapping->reverseConvert($faerunDate);
echo "Back:      " . $gregorian->format($backToGregorian, 'F j, Y \a\t H:i') . PHP_EOL;
echo PHP_EOL;

// Example 2: Using DateConverter for multiple calendars
echo "Example 2: Converting Between Multiple Calendars" . PHP_EOL;
echo str_repeat('-', 50) . PHP_EOL;

$golarion = Calendar::fromProfile('golarion');
$converter = new DateConverter();

// Register Gregorian <-> Faerûn
$converter->registerMapping($mapping);

// Register Gregorian <-> Golarion
$golarionConfig = new CalendarMappingConfiguration(
    sourceCalendarName: 'gregorian',
    targetCalendarName: 'golarion',
    correlationDate: [
        'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'target' => ['year' => 4724, 'month' => 1, 'day' => 1],
    ],
);
$golarionMapping = new CalendarMapping($golarionConfig, $gregorian, $golarion);
$converter->registerMapping($golarionMapping);

$newYear = new TimePoint($gregorian, 2024, 1, 1);
echo "Gregorian: " . $gregorian->format($newYear, 'F j, Y') . PHP_EOL;

echo "Faerûn:    " . $faerun->format($converter->convert($newYear, $faerun)) . PHP_EOL;
echo "Golarion:  " . $golarion->format($converter->convert($newYear, $golarion)) . PHP_EOL;
echo PHP_EOL;

// Example 3: Calendar information in TimePoint
echo "Example 3: TimePoint Contains Calendar Information" . PHP_EOL;
echo str_repeat('-', 50) . PHP_EOL;

$date1 = new TimePoint($gregorian, 2024, 6, 15);
$date2 = new TimePoint($faerun, 1492, 6, 15);

echo "Date 1 calendar: " . $date1->getCalendar()->getDisplayName() . PHP_EOL;
echo "Date 2 calendar: " . $date2->getCalendar()->getDisplayName() . PHP_EOL;
echo PHP_EOL;

// Example 4: Restricted date range
echo "Example 4: Mapping with Valid Date Range" . PHP_EOL;
echo str_repeat('-', 50) . PHP_EOL;

$restrictedConfig = new CalendarMappingConfiguration(
    sourceCalendarName: 'gregorian',
    targetCalendarName: 'faerun',
    correlationDate: [
        'source' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'target' => ['year' => 1492, 'month' => 1, 'day' => 1],
    ],
    validRange: [
        'min' => ['year' => 2024, 'month' => 1, 'day' => 1],
        'max' => ['year' => 2024, 'month' => 12, 'day' => 31],
    ],
);

$restrictedMapping = new CalendarMapping($restrictedConfig, $gregorian, $faerun);

$validDate = new TimePoint($gregorian, 2024, 6, 15);
echo "Converting date within range (2024-06-15): ";
try {
    $converted = $restrictedMapping->convert($validDate);
    echo "SUCCESS" . PHP_EOL;
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . PHP_EOL;
}

$invalidDate = new TimePoint($gregorian, 2025, 1, 1);
echo "Converting date outside range (2025-01-01): ";
try {
    $converted = $restrictedMapping->convert($invalidDate);
    echo "SUCCESS" . PHP_EOL;
} catch (\Exception $e) {
    echo "FAILED - " . $e->getMessage() . PHP_EOL;
}
echo PHP_EOL;

echo "=== Example Complete ===" . PHP_EOL;
