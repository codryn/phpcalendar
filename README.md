# PHP Calendar

[![PHP Version](https://img.shields.io/badge/PHP-8.1--8.5-blue.svg)](https://www.php.net/)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg)](https://phpstan.org/)
[![CI](https://github.com/codryn/phpcalendar/workflows/CI/badge.svg)](https://github.com/codryn/phpcalendar/actions)
[![Latest Stable Version](https://poser.pugx.org/codryn/phpcalendar/v/stable)](https://packagist.org/packages/codryn/phpcalendar)
[![License](https://poser.pugx.org/codryn/phpcalendar/license)](https://packagist.org/packages/codryn/phpcalendar)

A powerful, type-safe calendar system for PHP supporting both real-world (Gregorian) and fantasy setting calendars. Perfect for RPG applications, game development, and custom calendar implementations.

## Features

- 🗓️ **7 Built-in Calendar Profiles**: Gregorian + 6 fantasy RPG calendars (Faerûn, Golarion, DSA, Eberron, Dragonlance, Greyhawk)
- 🌍 **Internationalization**: Support for 5 languages (English, German, French, Spanish, Italian) for month names, epochs, and calendar names
- 📅 **Flexible Date Parsing**: Parse dates from natural language and various formats
- 🎨 **Customizable Formatting**: Format dates with custom patterns
- ⏱️ **Date Arithmetic**: Add/subtract time spans, calculate differences between dates
- 🎯 **Custom Calendar Support**: Create your own calendars with custom months, days, and leap year rules
- 🔒 **Type Safe**: PHP 8.0+ with strict types and PHPStan level 9 compliance
- ✅ **Thoroughly Tested**: 65+ tests with comprehensive acceptance, unit, and integration coverage
- 📦 **Zero Dependencies**: No external runtime requirements
- 🛡️ **PSR-12 Compliant**: Clean, maintainable code following PHP standards

## Requirements

- PHP 8.0 or higher

## Installation

```bash
composer require codryn/phpcalendar
```

## Quick Start

```php
use Codryn\PHPCalendar\Calendar\Calendar;

// Create a calendar from built-in profile
$calendar = Calendar::fromProfile('gregorian');

// Parse a date
$date = $calendar->parse('December 25, 2024');

// Format the date
echo $calendar->format($date, 'F d, Y'); // December 25, 2024

// Add 7 days
use Codryn\PHPCalendar\Calendar\TimeSpan;
$future = $date->add(TimeSpan::fromSeconds(86400 * 7));
echo $calendar->format($future, 'F d, Y'); // January 1, 2025

// Calculate difference
$start = $calendar->parse('2024-01-01');
$end = $calendar->parse('2024-12-31');
$span = $calendar->diff($start, $end);
echo $span->getTotalDays(); // 365
```

## Internationalization

PHPCalendar supports internationalization for all built-in calendar profiles. Calendar names, month names, epoch notations, and nameless day names can be displayed in 5 languages:

- **en-us** (English - default)
- **de** (German)
- **fr** (French)
- **es** (Spanish)
- **it** (Italian)

### Usage

```php
use Codryn\PHPCalendar\Calendar\Calendar;

// Create calendar with German locale
$calendar = Calendar::fromProfile('gregorian', 'de');
echo $calendar->getDisplayName(); // "Gregorianischer Kalender"

// Create calendar with French locale
$calendar = Calendar::fromProfile('gregorian', 'fr');
echo $calendar->getDisplayName(); // "Calendrier Grégorien"

// Get localized month names
$profile = new \Codryn\PHPCalendar\Profile\GregorianProfile();
$profile->setLocale('es');
$months = $profile->getMonthNames();
echo $months[1]; // "Enero" (January in Spanish)

// Get localized epoch notation
$epoch = $profile->getEpochNotation();
echo $epoch['after']; // "d.C." (Spanish for AD/CE)
```

### Supported Translations

| Calendar | Month Names | Display Name | Epoch Notation | Nameless Days |
|----------|-------------|--------------|----------------|---------------|
| Gregorian | ✅ All 5 | ✅ All 5 | ✅ All 5 | N/A |
| Faerûn | Proper nouns* | ✅ All 5 | ✅ All 5 | ✅ All 5 |
| DSA | Proper nouns* | ✅ All 5 | ✅ All 5 | ✅ All 5 |
| Dragonlance | ✅ All 5 | ✅ All 5 | ✅ All 5 | N/A |
| Eberron | Proper nouns* | ✅ All 5 | ✅ All 5 | N/A |
| Golarion | Proper nouns* | ✅ All 5 | ✅ All 5 | N/A |
| Greyhawk | ✅ All 5 | ✅ All 5 | ✅ All 5 | N/A |

*Proper nouns (fantasy deity names, dragonmarks, etc.) remain untranslated across all languages.

### Locale Fallback

If an unsupported or invalid locale is provided, the system automatically falls back to English (en-us):

```php
$calendar = Calendar::fromProfile('gregorian', 'unsupported-locale');
echo $calendar->getDisplayName(); // "Gregorian Calendar" (English fallback)
```

## Calendar Profiles

### Gregorian Calendar

Standard international calendar with proper leap year rules.

```php
$calendar = Calendar::fromProfile('gregorian');

$christmas = $calendar->parse('2024-12-25');
echo $calendar->format($christmas, 'l, F j, Y'); 
// Wednesday, December 25, 2024

echo $calendar->isLeapYear(2024) ? 'Leap' : 'Normal'; // Leap
```

**Specifications:**
- 12 months with varying days (28-31)
- Leap year: Divisible by 4, except centuries unless divisible by 400
- Epoch: BCE/CE

### Faerûn (Forgotten Realms)

Harptos calendar from D&D's Forgotten Realms setting.

```php
$calendar = Calendar::fromProfile('faerun');

$date = $calendar->parse('1 Hammer 1492 DR');
echo $calendar->format($date, 'd F Y'); // 1 Hammer 1492

// 12 months of 30 days + 5 festival days
echo $calendar->getMonthCount(); // 17
```

**Specifications:**
- 12 regular months (30 days each) + 5 festival days
- Leap year: Every 4 years (Shieldmeet)
- Epoch: DR (Dale Reckoning)
- Total: 365 days (366 with Shieldmeet)

### Golarion (Pathfinder)

Absalom Reckoning calendar from Pathfinder RPG.

```php
$calendar = Calendar::fromProfile('golarion');

$date = $calendar->parse('15 Rova 4724 AR');
echo $calendar->format($date, 'd F Y'); // 15 Rova 4724

echo $calendar->isLeapYear(4720) ? 'Leap' : 'Normal'; // Leap
```

**Specifications:**
- 12 months with varying days (28-31)
- Leap year: Every 8 years
- Epoch: AR (Absalom Reckoning)
- Total: 365 days (366 in leap years)

### Das Schwarze Auge

Aventurian calendar from The Dark Eye RPG.

```php
$calendar = Calendar::fromProfile('dsa');

$date = $calendar->parse('12 Praios 1045 BF');
echo $calendar->format($date, 'd F Y'); // 12 Praios 1045

// Always 365 days - no leap years
echo $calendar->isLeapYear(1045) ? 'Leap' : 'Normal'; // Normal
```

**Specifications:**
- 12 months (30 days each) + 5 nameless days
- No leap years
- Epoch: BF (Bosparans Fall)
- Total: 365 days always

### Eberron

Galifar Calendar from D&D's Eberron setting.

```php
$calendar = Calendar::fromProfile('eberron');

$date = $calendar->parse('10 Olarune 998 YK');
echo $calendar->format($date, 'd F Y'); // 10 Olarune 998

// Perfect 28-day months
echo $calendar->getDaysInMonth(1, 998); // 28 (always)
```

**Specifications:**
- 12 months of exactly 28 days each
- No leap years
- Epoch: YK (Years of Kingdom)
- Total: 336 days

### Dragonlance

Krynn calendar from D&D's Dragonlance setting.

```php
$calendar = Calendar::fromProfile('dragonlance');

$preCataclysm = $calendar->parse('100 Phoenix 0 PC');
$postCataclysm = $calendar->parse('1 Phoenix 1 AC');

echo $calendar->format($preCataclysm, 'd F Y'); // 100 Phoenix 0
echo $calendar->format($postCataclysm, 'd F Y'); // 1 Phoenix 1
```

**Specifications:**
- 12 months with varying days (28-31)
- Leap year: Gregorian rules
- Epoch: PC/AC (Pre/After Cataclysm)
- Total: 365 days (366 in leap years)

### Greyhawk

Common Year calendar from D&D's World of Greyhawk.

```php
$calendar = Calendar::fromProfile('greyhawk');

$date = $calendar->parse('1 Needfest 591 CY');
echo $calendar->format($date, 'd F Y'); // 1 Needfest 591

// 12 regular months + 4 festival weeks
echo $calendar->getMonthCount(); // 16
```

**Specifications:**
- 12 months (28 days each) + 4 festival weeks (7 days each)
- No leap years
- Epoch: CY (Common Year)
- Total: 364 days

## Custom Calendars

Create your own calendar with custom configuration:

```php
use Codryn\PHPCalendar\Calendar\Calendar;
use Codryn\PHPCalendar\Calendar\CalendarConfiguration;

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

## API Reference

PHPCalendar provides a clean, fluent API for calendar operations:

```php
// Create calendar
$calendar = Calendar::fromProfile('gregorian');
$calendar = Calendar::fromProfile('gregorian', 'de'); // With locale
$calendar = Calendar::fromConfiguration($config);

// Set locale on profile directly
$profile = new GregorianProfile();
$profile->setLocale('fr');
$locale = $profile->getLocale(); // 'fr'

// Parse dates
$date = $calendar->parse('December 25, 2024');
$date = $calendar->parse('2024-12-25');

// Format dates
$formatted = $calendar->format($date, 'F d, Y');
$formatted = $calendar->format($date, 'Y-m-d H:i:s');

// Date arithmetic
$future = $date->add(TimeSpan::fromSeconds(86400 * 7)); // +7 days
$past = $date->subtract(TimeSpan::fromSeconds(3600));   // -1 hour

// Calculate differences
$span = $calendar->diff($startDate, $endDate);
$days = $span->getTotalDays();
$hours = $span->getTotalHours();

// Calendar metadata
$calendar->getName();              // 'gregorian'
$calendar->getDisplayName();       // 'Gregorian Calendar'
$calendar->getMonthNames();        // [1 => 'January', ...]
$calendar->getDaysInMonth(2, 2024); // 29
$calendar->isLeapYear(2024);       // true
```

## Documentation

Comprehensive documentation is available:

- **[API Documentation](docs/API.md)** - Complete class and method reference
- **[Usage Guide](docs/USAGE.md)** - Common patterns and examples
- **[Calendar Profiles](docs/PROFILES.md)** - Details on all 7 built-in profiles
- **[Custom Calendars](docs/CUSTOM_CALENDARS.md)** - Guide to creating custom calendars
- **[Contributing Guide](CONTRIBUTING.md)** - Development workflow and standards

## Development

### Setup

```bash
# Clone the repository
git clone https://github.com/codryn/phpcalendar.git
cd phpcalendar

# Install dependencies
composer install
```

### Running Tests

```bash
# Run full test suite (65 tests, 290 assertions)
composer test

# Run specific test suites
composer test -- --testsuite=Acceptance
composer test -- --testsuite=Unit
composer test -- --testsuite=Integration

# Generate code coverage report
composer test-coverage
```

### Code Quality

```bash
# Run PHPStan (level 9 - strictest)
composer analyse

# Check PSR-12 compliance
composer cs-check

# Auto-fix code style issues
composer cs-fix
```

### Quality Standards

- ✅ **PHPStan Level 9**: Strictest static analysis
- ✅ **PSR-12**: PHP coding standards compliance
- ✅ **Strict Types**: `declare(strict_types=1)` in all files
- ✅ **TDD**: Test-driven development methodology
- ✅ **Type Hints**: Full type declarations on all methods
- ✅ **PHPDoc**: Complete documentation blocks

## Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) for details on:

- Development setup
- Coding standards (PSR-12, PHPStan level 9)
- Testing requirements (TDD, >90% coverage)
- Pull request process
- Adding new calendar profiles

## License

MIT License. See [LICENSE](LICENSE) for details.

## Credits

Created and maintained by Codryn.

Special thanks to:
- The PHP community
- Contributors to fantasy RPG calendar systems
- PHPUnit, PHPStan, and PHP-CS-Fixer maintainers

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and updates.

## Support

- 📖 [Documentation](docs/)
- 🐛 [Issue Tracker](https://github.com/codryn/phpcalendar/issues)
- 💬 [Discussions](https://github.com/codryn/phpcalendar/discussions)
- 📧 [Email](mailto:codryn@example.com)
