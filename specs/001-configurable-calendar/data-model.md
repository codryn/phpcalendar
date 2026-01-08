# Data Model: Configurable Calendar System

**Feature**: 001-configurable-calendar  
**Created**: 2026-01-08  
**Purpose**: Define entities, value objects, and relationships for calendar system

## Entity Overview

This is a value object-heavy design with no persistence layer. All classes are immutable where possible.

## Core Entities

### 1. Calendar

**Type**: Aggregate Root / Factory  
**Mutability**: Immutable after construction  
**Purpose**: Represents a calendar system and produces TimePoint objects

**Properties**:
- `profile`: CalendarProfileInterface - The calendar rules and metadata
- `name`: string - Human-readable calendar name

**Key Methods**:
- `fromProfile(string $profileName): self` - Static factory for profile-based calendars
- `fromConfiguration(CalendarConfiguration $config): self` - Static factory for custom calendars
- `parse(string $dateString): TimePoint` - Parse date string to TimePoint
- `format(TimePoint $point, ?string $pattern = null): string` - Format TimePoint to string
- `diff(TimePoint $start, TimePoint $end): TimeSpan` - Calculate difference between points
- `getName(): string` - Get calendar name

**Validation Rules**:
- Profile must be valid and registered
- Configuration must pass CalendarValidator checks
- TimePoints must belong to this calendar for diff() operation

**Relationships**:
- Owns CalendarProfileInterface (composition)
- Creates TimePoint instances (factory)
- Creates TimeSpan instances (factory)

---

### 2. TimePoint

**Type**: Value Object  
**Mutability**: Immutable  
**Purpose**: Represents a specific moment in time within a calendar

**Properties**:
- `calendar`: Calendar - The parent calendar (for validation)
- `year`: int - Year (negative for BCE/pre-epoch)
- `month`: int - Month (1-based)
- `day`: int - Day of month (1-based)
- `hour`: int - Hour (0-23, default 0)
- `minute`: int - Minute (0-59, default 0)
- `second`: int - Second (0-59, default 0)
- `microsecond`: int - Microsecond (0-999999, default 0)

**Key Methods**:
- `getCalendar(): Calendar`
- `getYear(): int`
- `getMonth(): int`
- `getDay(): int`
- `getHour(): int`
- `getMinute(): int`
- `getSecond(): int`
- `getMicrosecond(): int`
- `add(TimeSpan $span): TimePoint` - Add duration (returns new TimePoint)
- `subtract(TimeSpan $span): TimePoint` - Subtract duration (returns new TimePoint)
- `equals(TimePoint $other): bool` - Equality check (must be same calendar)
- `isBefore(TimePoint $other): bool` - Temporal comparison (must be same calendar)
- `isAfter(TimePoint $other): bool` - Temporal comparison (must be same calendar)

**Validation Rules**:
- Year: any int (including negative)
- Month: 1 to calendar's max months
- Day: 1 to getDaysInMonth(month, year) from profile
- Hour: 0-23
- Minute: 0-59
- Second: 0-59
- Microsecond: 0-999999

**Invariants**:
- TimePoint is immutable - all operations return new instances
- Cannot compare/combine TimePoints from different calendars (throws IncompatibleCalendarException)
- Date must exist in calendar (no Feb 30 in Gregorian, etc.)

**Relationships**:
- Belongs to Calendar (composition - holds reference for validation)
- Can produce TimeSpan via Calendar::diff()

---

### 3. TimeSpan

**Type**: Value Object  
**Mutability**: Immutable  
**Purpose**: Represents duration between two TimePoints

**Properties**:
- `seconds`: int - Total seconds in span (can be negative for past direction)
- `microseconds`: int - Additional microseconds (0-999999 or negative)

**Key Methods**:
- `fromSeconds(int $seconds, int $microseconds = 0): self` - Static factory
- `getTotalSeconds(): int` - Get total seconds
- `getMicroseconds(): int` - Get microsecond component
- `getTotalDays(): int` - Calculate days (seconds / 86400)
- `getTotalHours(): int` - Calculate hours (seconds / 3600)
- `getTotalMinutes(): int` - Calculate minutes (seconds / 60)
- `isNegative(): bool` - Check if span represents past
- `abs(): self` - Get absolute value span
- `negate(): self` - Reverse direction
- `add(TimeSpan $other): self` - Add spans
- `subtract(TimeSpan $other): self` - Subtract spans

**Derived Calculations**:
```php
Days:         intdiv($seconds, 86400)
Hours:        intdiv($seconds, 3600)
Minutes:      intdiv($seconds, 60)
Weeks:        intdiv($seconds, 604800)
```

**Invariants**:
- Immutable - all operations return new instances
- Seconds and microseconds must have consistent sign (both positive or both negative)
- Microseconds normalized: abs($microseconds) < 1,000,000

**Relationships**:
- Created by Calendar::diff() or TimePoint arithmetic
- Used by TimePoint::add() and TimePoint::subtract()

---

### 4. CalendarProfileInterface

**Type**: Interface  
**Purpose**: Defines contract for calendar rules and metadata

**Required Methods**:
```php
getName(): string                              // Profile identifier (e.g., "gregorian", "faerun")
getDisplayName(): string                       // Human-readable name
getMonthNames(): array                         // ['January', 'February', ...]
getMonthCount(): int                           // Number of months
getDaysInMonth(int $month, int $year): int     // Days in specific month/year
isLeapYear(int $year): bool                    // Leap year determination
getEpochNotation(): array                      // ['before' => 'BCE', 'after' => 'CE']
getFormatPatterns(): array                     // Acceptable date format patterns
getMetadata(): array                           // Setting info, source references
```

**Implementations**:
- AbstractCalendarProfile (base class with shared logic)
- GregorianProfile
- FaerunProfile
- GolarionProfile
- DSAProfile
- EberronProfile
- DragonlanceProfile
- GreyhawkProfile

---

### 5. CalendarConfiguration

**Type**: Value Object / DTO  
**Mutability**: Immutable after construction  
**Purpose**: Defines custom calendar parameters

**Properties**:
- `name`: string - Calendar identifier
- `displayName`: string - Human-readable name
- `monthNames`: array<string> - Month names in order
- `daysPerMonth`: array<int> - Days in each month (can be callable for leap year logic)
- `leapYearRule`: ?callable(int): bool - Function to determine leap years
- `epochNotation`: array{before: string, after: string} - Era labels
- `formatPatterns`: array<string> - Default date format patterns

**Key Methods**:
- Constructor with named parameters
- Getters for all properties
- `validate(): void` - Throws InvalidCalendarConfigException if invalid

**Validation Rules**:
- name: non-empty string, alphanumeric + hyphens
- displayName: non-empty string
- monthNames: at least 1 month, all non-empty strings
- daysPerMonth: positive integers, same count as monthNames
- leapYearRule: nullable callable returning bool
- epochNotation: array with 'before' and 'after' keys, non-empty strings
- formatPatterns: array of non-empty strings

---

## Supporting Components

### CalendarValidator

**Purpose**: Validates CalendarConfiguration objects

**Methods**:
- `validate(CalendarConfiguration $config): void` - Throws on invalid config
- `validateMonthNames(array $names): void`
- `validateDaysPerMonth(array $days): void`
- `validateEpochNotation(array $notation): void`

---

### DateParser

**Purpose**: Parse date strings into TimePoint objects

**Methods**:
- `parse(string $dateString, CalendarProfileInterface $profile, Calendar $calendar): TimePoint`
- Pattern matching logic with fallback chain

---

### DateFormatter

**Purpose**: Format TimePoint objects into date strings

**Methods**:
- `format(TimePoint $point, string $pattern, CalendarProfileInterface $profile): string`
- Support for standard format tokens (Y, M, d, etc.)

---

## Exception Hierarchy

```
CalendarException (abstract)
├── InvalidDateException
│   └── Thrown when: parsing invalid dates, creating impossible TimePoints
├── InvalidCalendarConfigException
│   └── Thrown when: custom calendar config fails validation
├── IncompatibleCalendarException
│   └── Thrown when: operations between different calendars attempted
└── DateArithmeticException
    └── Thrown when: date arithmetic produces invalid result (e.g., Jan 31 + 1 month)
```

---

## Object Lifecycle

### Creating a Calendar from Profile
```
User calls Calendar::fromProfile('gregorian')
  → ProfileRegistry resolves 'gregorian' to GregorianProfile instance
  → Calendar wraps profile
  → Returns Calendar instance
```

### Creating a Custom Calendar
```
User creates CalendarConfiguration with parameters
  → CalendarValidator::validate() checks config
  → Calendar::fromConfiguration() wraps config in CustomProfile adapter
  → Returns Calendar instance
```

### Parsing a Date
```
User calls Calendar::parse('15 Mirtul 1492 DR')
  → Calendar delegates to DateParser
  → Parser tries each format pattern from profile
  → On match: extracts year/month/day/time components
  → Validates components against calendar rules
  → Creates TimePoint instance
  → Returns TimePoint
```

### Calculating Difference
```
User calls Calendar::diff($point1, $point2)
  → Validates both points belong to this calendar
  → Converts each TimePoint to total seconds since epoch
  → Calculates difference in seconds
  → Creates TimeSpan with seconds difference
  → Returns TimeSpan
```

### Adding Duration to TimePoint
```
User calls $point->add($span)
  → Converts TimePoint to total seconds
  → Adds TimeSpan seconds
  → Converts back to year/month/day components
  → Validates resulting date exists in calendar
  → If invalid: throws DateArithmeticException
  → If valid: creates new TimePoint
  → Returns new TimePoint
```

---

## State Transitions

All objects are immutable - no state transitions. Operations return new instances.

---

## Relationships Diagram

```
Calendar (aggregate root)
  ├─ owns → CalendarProfileInterface (composition)
  ├─ creates → TimePoint (factory)
  └─ creates → TimeSpan (factory)

TimePoint (value object)
  ├─ references → Calendar (for validation)
  ├─ uses → TimeSpan (for add/subtract)
  └─ compared with → TimePoint (same calendar only)

TimeSpan (value object)
  └─ combined with → TimeSpan (add/subtract)

CalendarConfiguration (value object)
  └─ validated by → CalendarValidator

ProfileRegistry (service)
  └─ manages → CalendarProfileInterface implementations
```

---

## Validation Summary

| Entity | Validation Point | Exception |
|--------|------------------|-----------|
| Calendar | Profile exists | InvalidArgumentException |
| Calendar | Configuration valid | InvalidCalendarConfigException |
| TimePoint | Date exists in calendar | InvalidDateException |
| TimePoint | Time components in range | InvalidDateException |
| TimePoint | Calendar compatibility | IncompatibleCalendarException |
| TimeSpan | Microseconds normalized | InvalidArgumentException |
| Date arithmetic | Result exists | DateArithmeticException |
| CalendarConfiguration | All rules pass | InvalidCalendarConfigException |

---

## Design Decisions

1. **Immutability**: All core classes immutable for thread-safety and predictability
2. **Value Objects**: TimePoint and TimeSpan are value objects with equality by value
3. **Factory Pattern**: Calendar acts as factory for TimePoint/TimeSpan creation
4. **Strategy Pattern**: CalendarProfileInterface enables pluggable calendar systems
5. **Fail-Fast**: Validation at construction time, explicit exceptions for invalid operations
6. **No Persistence**: Pure domain logic, no database/file I/O in core entities
