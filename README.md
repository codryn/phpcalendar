# PHP Calendar

[![CI](https://github.com/codryn/phpcalendar/workflows/CI/badge.svg)](https://github.com/codryn/phpcalendar/actions)
[![Latest Stable Version](https://poser.pugx.org/codryn/phpcalendar/v/stable)](https://packagist.org/packages/codryn/phpcalendar)
[![License](https://poser.pugx.org/codryn/phpcalendar/license)](https://packagist.org/packages/codryn/phpcalendar)

A configurable calendar system for PHP supporting both real-world (Gregorian) and fantasy setting calendars (Faerûn, Golarion, DSA, Eberron, Dragonlance, Greyhawk). Perfect for RPG applications, game development, and historical date calculations.

## Features

- 🗓️ **Multiple Calendar Systems**: Pre-built profiles for Gregorian and 6 fantasy calendars
- 📅 **Date Parsing & Formatting**: Parse date strings and format them according to calendar rules
- ⏱️ **Temporal Arithmetic**: Calculate time differences and perform date arithmetic
- 🎯 **Custom Calendars**: Create fully custom calendars with your own month names and leap year rules
- 🔒 **Type Safe**: Full PHP 8.0+ type declarations with strict_types enabled
- ✅ **Well Tested**: Comprehensive PHPUnit test coverage following TDD methodology
- 📦 **Zero Dependencies**: No external runtime dependencies

## Requirements

- PHP 8.0 or higher

## Installation

```bash
composer require codryn/phpcalendar
```

## Quick Start

### Create a Gregorian Calendar

```php
use Codryn\PHPCalendar\Calendar\Calendar;

$calendar = Calendar::fromProfile('gregorian');
echo $calendar->getDisplayName(); // "Gregorian Calendar"
```

### Parse and Format Dates

```php
$date = $calendar->parse('2024-12-25');
echo $calendar->format($date); // "December 25, 2024"
```

### Calculate Time Differences

```php
$start = $calendar->parse('2024-01-01');
$end = $calendar->parse('2024-12-31');
$span = $calendar->diff($start, $end);
echo $span->getTotalDays(); // 365
```

### Date Arithmetic

```php
$date = $calendar->parse('2024-01-15');
$span = \Codryn\PHPCalendar\Calendar\TimeSpan::fromSeconds(86400 * 30); // 30 days
$futureDate = $date->add($span);
echo $calendar->format($futureDate); // "February 14, 2024"
```

## Supported Calendars

### Real-World Calendars

- **Gregorian** (`gregorian`) - Standard modern calendar with leap year rules

### Fantasy Calendars

- **Faerûn** (`faerun`) - Forgotten Realms / Harptos Calendar (D&D)
- **Golarion** (`golarion`) - Pathfinder's Absalom Reckoning
- **DSA** (`dsa`, `black-eye`) - The Dark Eye / Aventurian Calendar
- **Eberron** (`eberron`) - Galifar Calendar (D&D)
- **Dragonlance** (`dragonlance`) - Krynn Calendar (D&D)
- **Greyhawk** (`greyhawk`) - Common Year Calendar (D&D)

## Custom Calendars

Create your own calendar with custom configuration:

```php
use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarConfiguration;

$config = new CalendarConfiguration(
    name: 'custom',
    displayName: 'My Custom Calendar',
    monthNames: ['Alpha', 'Beta', 'Gamma'],
    daysPerMonth: [30, 30, 30],
    leapYearRule: fn(int $year) => $year % 5 === 0,
    epochNotation: ['before' => 'BE', 'after' => 'AE'],
    formatPatterns: ['%d %B %Y']
);

$calendar = Calendar::fromConfiguration($config);
```

## Documentation

- [API Documentation](docs/)
- [Calendar Profiles](docs/profiles.md)
- [Contributing Guide](CONTRIBUTING.md)
- [Changelog](CHANGELOG.md)

## Development

```bash
# Install dependencies
composer install

# Run tests
composer test

# Check code style
composer cs-heck

# Fix code style
composer cs-fix

# Run static analysis
composer analyse
```

## Testing

The package includes comprehensive test coverage:

```bash
# Run all tests
composer test

# Run specific test suites
vendor/bin/phpunit --testsuite=Unit
vendor/bin/phpunit --testsuite=Integration
vendor/bin/phpunit --testsuite=Acceptance

# Generate coverage report
composer test-coverage
```

## License

MIT License. See [LICENSE](LICENSE) for details.

## Credits

Created by Codryn for the PHP community.
