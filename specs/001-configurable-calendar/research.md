# Research: Configurable Calendar System

**Feature**: 001-configurable-calendar  
**Created**: 2026-01-08  
**Purpose**: Resolve technical unknowns and establish best practices for PHP calendar library implementation

## Research Tasks

### 1. PHP 8.0 Compatibility Strategy

**Question**: How to maintain PHP 8.0 compatibility while developing in PHP 8.3?

**Decision**: Use PHP 8.0 baseline features with conditional feature detection for development tooling

**Rationale**: 
- PHP 8.0 introduced named arguments, union types, attributes, match expressions - sufficient for value objects
- Avoid PHP 8.1+ exclusive features: readonly properties (use private with getters), enums (use constants/classes), never return type
- Development tools (PHPUnit, PHPStan) can use PHP 8.3 while library code targets PHP 8.0

**Alternatives considered**:
- Target PHP 8.1+: Rejected - excludes users on LTS systems (Ubuntu 20.04 has PHP 7.4/8.0)
- Use polyfills for 8.1 features: Rejected - adds complexity and dependencies

**Implementation approach**:
- composer.json: `"require": { "php": "^8.0" }`
- Use union types and mixed type (PHP 8.0)
- Avoid: readonly, enums, never, intersection types (PHP 8.1+)
- CI tests against PHP 8.0, 8.1, 8.2, 8.3

---

### 2. TimeSpan Internal Representation (Seconds vs Days)

**Question**: Should TimeSpan store total seconds/milliseconds or just days?

**Decision**: Store total seconds (int) with microseconds (int) separately for precision

**Rationale** (from spec clarification):
- User requested seconds/milliseconds for time-of-day handling
- Seconds provide unambiguous duration representation across all calendars
- Microseconds separate storage prevents float precision issues
- Days can be calculated on-demand: `$seconds / 86400`

**Alternatives considered**:
- Float seconds with decimals: Rejected - PHP float precision issues (~15 digits)
- Days only: Rejected - loses time-of-day information per spec clarification
- Years/months/days: Rejected - ambiguous across calendar systems

**Implementation approach**:
```php
final class TimeSpan
{
    private int $seconds;
    private int $microseconds;  // 0-999999
    
    public function getTotalDays(): int { return intdiv($this->seconds, 86400); }
    public function getTotalHours(): int { return intdiv($this->seconds, 3600); }
    public function getTotalMinutes(): int { return intdiv($this->seconds, 60); }
}
```

---

### 3. Calendar Profile Architecture

**Question**: How should calendar profiles be structured for extensibility?

**Decision**: Interface-based design with abstract base class for shared behavior

**Rationale**:
- Interface (CalendarProfileInterface) defines contract
- Abstract base class (AbstractCalendarProfile) provides common functionality
- Each profile extends base, overrides specific behavior (leap years, month names, etc.)
- Enables third-party profile creation via same interface

**Alternatives considered**:
- Data-only JSON profiles: Rejected - insufficient for complex leap year rules (e.g., Gregorian divisible by 400)
- Single class with strategy pattern: Rejected - less clear, harder to test profiles individually
- Hardcoded profiles without interface: Rejected - not extensible for custom calendars

**Implementation approach**:
```php
interface CalendarProfileInterface {
    public function getName(): string;
    public function getMonths(): array;
    public function getDaysInMonth(int $month, int $year): int;
    public function isLeapYear(int $year): bool;
    public function getEpochNotation(): array; // ['before' => 'BCE', 'after' => 'CE']
}

abstract class AbstractCalendarProfile implements CalendarProfileInterface {
    // Common parsing/formatting logic
}

final class GregorianProfile extends AbstractCalendarProfile {
    public function isLeapYear(int $year): bool {
        return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
    }
}
```

---

### 4. Date Parsing Strategy

**Question**: How to handle flexible date parsing across multiple formats?

**Decision**: Profile-defined format patterns with fallback chain

**Rationale**:
- Each profile defines acceptable format patterns (e.g., "d M Y", "M d, Y")
- Parser tries patterns in order, throws exception if all fail
- Profiles can override parser behavior for setting-specific formats
- Avoids complex regex in favor of structured pattern matching

**Alternatives considered**:
- Single strptime-style parser: Rejected - insufficient for fantasy calendar variations
- Regex-only parsing: Rejected - hard to maintain, error-prone
- Natural language parsing: Rejected - overly complex, out of scope

**Implementation approach**:
```php
interface DateParserInterface {
    public function parse(string $dateString, CalendarProfileInterface $profile): TimePoint;
}

final class DateParser implements DateParserInterface {
    public function parse(string $dateString, CalendarProfileInterface $profile): TimePoint {
        foreach ($profile->getFormatPatterns() as $pattern) {
            try {
                return $this->parseWithPattern($dateString, $pattern, $profile);
            } catch (ParseException $e) {
                continue; // Try next pattern
            }
        }
        throw new InvalidDateException("Unable to parse date: $dateString");
    }
}
```

---

### 5. Exception Strategy

**Question**: What exception hierarchy best supports the fail-fast approach?

**Decision**: Specific exception classes extending base CalendarException

**Rationale** (per spec clarifications):
- Spec requires immediate exceptions for invalid dates, invalid arithmetic, cross-calendar operations
- Specific exceptions enable targeted catch blocks
- Base exception allows catch-all for calendar-related errors
- Exception messages must be clear and actionable

**Alternatives considered**:
- Generic \RuntimeException: Rejected - doesn't distinguish calendar-specific errors
- Single CalendarException with error codes: Rejected - less type-safe, harder to catch specific errors
- Result objects (Success/Error): Rejected - not idiomatic PHP, verbose

**Implementation approach**:
```php
abstract class CalendarException extends \Exception {}

final class InvalidDateException extends CalendarException {}
final class InvalidCalendarConfigException extends CalendarException {}
final class IncompatibleCalendarException extends CalendarException {}
final class DateArithmeticException extends CalendarException {}
```

Usage:
```php
// Invalid date
throw new InvalidDateException("February 30 does not exist in Gregorian calendar");

// Cross-calendar operation
throw new IncompatibleCalendarException(
    "Cannot compare TimePoint from Gregorian calendar with TimePoint from Faerun calendar"
);
```

---

### 6. PSR-12 and Static Analysis Configuration

**Question**: How to configure tooling for strict compliance?

**Decision**: PHP-CS-Fixer for PSR-12, PHPStan level 9 for static analysis

**Rationale**:
- PHP-CS-Fixer: Actively maintained, auto-fixes PSR-12 violations, configurable
- PHPStan level 9: Maximum strictness without experimental features, widely adopted
- Both integrate with CI/CD pipelines easily

**Alternatives considered**:
- PHP_CodeSniffer only: Rejected - reports but doesn't auto-fix
- Psalm instead of PHPStan: Considered equivalent, PHPStan chosen for wider adoption
- PHPStan level 8: Rejected - level 9 catches more edge cases

**Configuration**:

`.php-cs-fixer.php`:
```php
<?php
return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
    );
```

`phpstan.neon`:
```neon
parameters:
    level: 9
    paths:
        - src
        - tests
    strictRules:
        allRules: true
```

---

### 7. Fantasy Calendar Research

**Question**: What are the canonical calendar systems for each fantasy setting?

**Findings**:

**Faerûn (Forgotten Realms)**:
- Calendar: Harptos (Calendar of Harptos)
- 12 months of 30 days each + 5 annual festivals = 365 days
- Leap year: Shieldmeet every 4 years (366 days)
- Month names: Hammer, Alturiak, Ches, Tarsakh, Mirtul, Kythorn, Flamerule, Eleasis, Eleint, Marpenoth, Uktar, Nightal
- Epoch: Dale Reckoning (DR), epoch = founding of Dalelands (~-700 DR to present ~1492+ DR)

**Golarion (Pathfinder)**:
- Calendar: Absalom Reckoning (AR)
- 12 months of 28-31 days = 365 days
- Month names: Abadius (31), Calistril (28), Pharast (31), Gozran (30), Desnus (31), Sarenith (30), Erastus (31), Arodus (31), Rova (30), Lamashan (31), Neth (30), Kuthona (31)
- Leap year: Every 8 years
- Epoch: 1 AR = founding of Absalom (4606 IC in old Imperial Calendar)

**Das Schwarze Auge / The Dark Eye (Aventuria)**:
- Calendar: Bosparans Fall (BF) reckoning
- 12 months of 30 days + 5 nameless days = 365 days
- Month names: Praios, Rondra, Efferd, Travia, Boron, Hesinde, Firun, Tsa, Phex, Peraine, Ingerimm, Rahja
- Leap year: None in standard calendar
- Epoch: 0 BF = fall of Bosparan Empire

**Eberron**:
- Calendar: Galifar Calendar
- 12 months of 28 days + 4 weeks = 336 days
- Month names: Zarantyr, Olarune, Therendor, Eyre, Dravago, Nymm, Lharvion, Barrakas, Rhaan, Sypheros, Aryth, Vult
- No leap years
- Epoch: Years since founding of Kingdom of Galifar (YK)

**Dragonlance (Krynn)**:
- Calendar: Astinus Calendar / King's Calendar
- 12 months of varying days (28-31) ≈ 365 days
- Month names follow D&D standard (varies by region)
- Alt-Catacl reckoning: AC (After Cataclysm), PC (Pre-Cataclysm)

**Greyhawk (Oerth)**:
- Calendar: Common Year (CY) / Greyhawk Calendar
- 12 months of 28 days + 4 festivals = 364 days
- Month names: Needfest, Fireseek, Readying, Coldeven, Growfest, Planting, Flocktime, Wealsun, Richfest, Reaping, Goodmonth, Harvester, Brewfest, Patchwall, Ready'reat, Sunsebb
- Leap year: Varies by source
- Epoch: CY 1 = Greyhawk campaign start (no specific in-world event)

---

## Summary

All technical unknowns resolved:

1. ✅ **PHP 8.0 Compatibility**: Target 8.0 features, test across 8.0-8.3
2. ✅ **TimeSpan Storage**: Total seconds + microseconds (int + int)
3. ✅ **Profile Architecture**: Interface + abstract base + concrete profiles
4. ✅ **Date Parsing**: Profile-defined patterns with fallback chain
5. ✅ **Exception Strategy**: Specific exception classes with clear messages
6. ✅ **Tooling**: PHP-CS-Fixer + PHPStan level 9
7. ✅ **Fantasy Calendars**: Researched canonical systems for all 6 settings

Ready to proceed to Phase 1 (Design).
