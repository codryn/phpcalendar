# Feature Specification: Configurable Calendar System

**Feature Branch**: `001-configurable-calendar`  
**Created**: 2026-01-08  
**Status**: Draft  
**Input**: User description: "build a configurable calendar. A calendar instance can be created using a provided profile for a fantasy or real world calendar or using a fully customizable calendar. A calendar shall allow to convert a date and time string to a time point and vice versa. It shall allow to calculate the number of days between two time points and provide a time span object type. Profiles shall be provided for: real world (gregorian), fearun, golarion, black eye (das schwarze auge), other widely used fantasy settings such as eberron, dragonlance, greyhawk"

## Clarifications

### Session 2026-01-08

- Q: When adding a TimeSpan to a date that results in an invalid date (e.g., adding 1 month to January 31st), how should the system behave? → A: Throw an exception requiring explicit handling by the developer
- Q: How should TimeSpan represent durations internally for maximum accuracy? → A: Total seconds/milliseconds (handles time-of-day, very precise)
- Q: When parsing a date string that doesn't exist in a calendar (e.g., "February 30th" in Gregorian), what should happen? → A: Throw exception immediately with clear error message
- Q: How should the system handle dates before the calendar epoch (negative years, BCE dates)? → A: Allow epoch/age config for calendars. Gregorian shall use CE/BCE with option to use old style BC/AD. Other according to fantasy world.
- Q: Can TimePoint objects from different calendar systems be compared or used together? → A: Throw exception - incompatible calendar systems

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Create Calendar with Pre-built Profile (Priority: P1)

A developer wants to use a standard calendar system (Gregorian or popular fantasy setting) without needing to define calendar rules manually. They instantiate a calendar using a profile name, and the calendar is immediately ready for date operations.

**Why this priority**: This is the most common use case and provides immediate value. Users can start working with calendars without configuration overhead. This establishes the core calendar functionality and profile system that all other features depend on.

**Independent Test**: Can be fully tested by creating a calendar with a Gregorian profile, verifying it produces correct date values, and confirming the calendar responds to basic date queries. Delivers a working calendar system for the most common real-world use case.

**Acceptance Scenarios**:

1. **Given** a developer needs a Gregorian calendar, **When** they create a calendar instance with the "gregorian" profile, **Then** the calendar is instantiated with correct Gregorian rules (12 months, leap years, standard day counts)
2. **Given** a developer needs a fantasy calendar, **When** they create a calendar instance with the "faerun" profile, **Then** the calendar is instantiated with Forgotten Realms calendar rules (Harptos calendar)
3. **Given** a calendar instance exists, **When** querying for calendar properties (months, days per month, year structure), **Then** the system returns the correct values defined by the profile
4. **Given** an invalid profile name, **When** attempting to create a calendar, **Then** the system throws a clear error indicating available profiles

---

### User Story 2 - Parse and Format Dates (Priority: P1)

A developer needs to convert date/time strings into internal time points and convert time points back into formatted date strings. This enables reading user input and displaying dates in calendar-specific formats.

**Why this priority**: Date parsing and formatting are fundamental operations required by virtually all calendar use cases. Without this, the calendar cannot interact with external data or present results to users.

**Independent Test**: Can be fully tested by parsing various date strings into time points, formatting time points back to strings, and verifying round-trip consistency. Delivers practical date I/O capabilities.

**Acceptance Scenarios**:

1. **Given** a calendar instance and a date string (e.g., "15 Mirtul 1492 DR" for Faerûn), **When** parsing the date string, **Then** the system returns a time point object representing that date
2. **Given** a time point object, **When** formatting it to a string, **Then** the system returns a calendar-appropriate date string
3. **Given** a parsed time point, **When** formatting it and parsing it again, **Then** the round-trip produces an equivalent time point
4. **Given** an invalid date string, **When** parsing, **Then** the system throws a clear validation error
5. **Given** a calendar instance and multiple date format patterns, **When** parsing with different patterns, **Then** the system correctly handles various input formats (flexible parsing)

---

### User Story 3 - Calculate Time Differences (Priority: P1)

A developer needs to calculate the duration between two dates, expressed as a time span with calendar-aware units (days, months, years). This enables age calculations, event scheduling, and timeline management.

**Why this priority**: Time difference calculations are essential for practical calendar applications - from calculating character ages in RPGs to scheduling events. This completes the core MVP by adding temporal arithmetic.

**Independent Test**: Can be fully tested by creating two time points, calculating the difference, and verifying the resulting time span object contains correct day counts and duration values. Delivers practical calendar arithmetic capabilities.

**Acceptance Scenarios**:

1. **Given** two time points in the same calendar, **When** calculating the difference, **Then** the system returns a time span object with the number of days between them
2. **Given** two time points spanning multiple years, **When** calculating the difference, **Then** the time span correctly accounts for leap years and varying month lengths
3. **Given** a time span object, **When** querying for total days, **Then** the system returns an accurate day count
4. **Given** a time point and a time span, **When** adding the time span to the time point, **Then** the system returns a new time point representing the future date
5. **Given** a time point and a time span, **When** subtracting the time span from the time point, **Then** the system returns a new time point representing the past date
6. **Given** two time points where the first is later than the second, **When** calculating the difference, **Then** the time span has a negative or distinct directional indicator

---

### User Story 4 - Create Fully Custom Calendar (Priority: P2)

A developer wants to define a completely custom calendar system with specific month names, day counts, leap year rules, and other calendar properties. This enables support for homebrew game settings or specialized calendars not included in the pre-built profiles.

**Why this priority**: While important for flexibility and completeness, custom calendar creation is less common than using pre-built profiles. Most users will rely on standard profiles. This extends the library's applicability to niche use cases.

**Independent Test**: Can be fully tested by creating a calendar from scratch with custom parameters, verifying it produces correct date calculations according to the custom rules, and confirming it operates identically to profile-based calendars once created.

**Acceptance Scenarios**:

1. **Given** custom calendar configuration (month names, day counts, week structure, leap year rules), **When** creating a calendar instance, **Then** the calendar is instantiated with the specified custom rules
2. **Given** a custom calendar with irregular month lengths, **When** performing date calculations, **Then** the system correctly accounts for the custom month structure
3. **Given** custom leap year rules (e.g., every 5 years), **When** calculating dates across leap years, **Then** the system applies the custom leap year logic correctly
4. **Given** a custom calendar configuration, **When** validating the configuration, **Then** the system rejects logically impossible configurations (e.g., negative day counts, empty month names)
5. **Given** a custom calendar instance, **When** using it for parsing and formatting, **Then** it behaves identically to a profile-based calendar

---

### User Story 5 - Extended Fantasy Calendar Profiles (Priority: P3)

A developer working with various fantasy settings can select from an expanded library of pre-built profiles including Golarion (Pathfinder), Das Schwarze Auge (The Dark Eye), Eberron, Dragonlance, and Greyhawk. Each profile provides the accurate calendar system for that setting.

**Why this priority**: These additional profiles enhance the library's utility for RPG communities but are not essential for the core functionality. They can be added incrementally after the core system is proven.

**Independent Test**: Can be fully tested by instantiating each profile and verifying it matches the published calendar rules for that fantasy setting. Each profile can be added and tested independently.

**Acceptance Scenarios**:

1. **Given** a developer needs a Golarion calendar, **When** creating a calendar with the "golarion" profile, **Then** the calendar uses Golarion's Absalom Reckoning calendar
2. **Given** a developer needs a Das Schwarze Auge calendar, **When** creating a calendar with the "dsa" or "black-eye" profile, **Then** the calendar uses the Aventurian calendar (Bosparans Fall reckoning)
3. **Given** a developer needs an Eberron calendar, **When** creating a calendar with the "eberron" profile, **Then** the calendar uses the Eberron calendar system
4. **Given** a developer needs a Dragonlance calendar, **When** creating a calendar with the "dragonlance" profile, **Then** the calendar uses the Krynn calendar
5. **Given** a developer needs a Greyhawk calendar, **When** creating a calendar with the "greyhawk" profile, **Then** the calendar uses the Oerth Common Year calendar
6. **Given** any fantasy profile, **When** querying available profile metadata, **Then** the system provides source information and setting details

---

### Edge Cases

- **Non-existent dates**: When parsing dates that don't exist in a calendar (e.g., February 30th in Gregorian), the system MUST throw an exception immediately with a clear error message indicating the specific validation failure. This prevents silent data corruption.
- **Epoch and historical dates**: The system MUST support dates before the calendar epoch with configurable epoch notation. Gregorian calendar MUST use CE/BCE notation by default with an option to use BC/AD notation. Fantasy calendars MUST use epoch notation appropriate to their world (e.g., "DR" for Dale Reckoning in Faerûn, "AR" for Absalom Reckoning in Golarion). Negative years and proper date arithmetic MUST be supported for all calendars.
- What happens when calculating time spans that exceed integer limits (millions of years)?
- How does the system handle calendars with no leap year rules versus complex leap year patterns?
- What happens when formatting dates with insufficient calendar information (e.g., missing month names)?
- How does the system handle time zones or should it remain timezone-agnostic?
- **Invalid date arithmetic**: When adding a time span to a date that results in an invalid date (e.g., Jan 31 + 1 month = Feb 31), the system MUST throw an exception requiring explicit handling by the developer. This prevents silent data loss and ensures predictable behavior.
- **Cross-calendar operations**: TimePoint objects from different calendar systems MUST NOT be directly compared or used in arithmetic operations together. The system MUST throw an exception when attempting such operations, clearly indicating the incompatible calendars. Since calendar conversion is out of scope, cross-calendar operations would produce meaningless results.
- How does the system handle calendar conversion between different calendar systems?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a Calendar class that can be instantiated with either a profile name or custom configuration
- **FR-002**: System MUST include pre-built profiles for: Gregorian, Faerûn (Harptos), Golarion (Absalom Reckoning), Das Schwarze Auge (Aventurian), Eberron, Dragonlance (Krynn), and Greyhawk (Oerth)
- **FR-003**: System MUST allow users to create custom calendars by specifying month names, day counts per month, week structure, leap year rules, epoch information, and epoch notation (e.g., CE/BCE, BC/AD, or custom era names)
- **FR-004**: System MUST provide a TimePoint class representing a specific moment in time within a calendar
- **FR-005**: System MUST provide a TimeSpan class representing a duration between two time points
- **FR-006**: Calendar instances MUST be able to parse date/time strings into TimePoint objects according to calendar rules
- **FR-007**: Calendar instances MUST be able to format TimePoint objects into date/time strings according to calendar conventions
- **FR-008**: System MUST calculate the difference between two TimePoint objects and return a TimeSpan object
- **FR-009**: TimeSpan objects MUST internally store durations as total seconds/milliseconds and support querying total days, hours, minutes, seconds, and provide methods for adding/subtracting spans from time points
- **FR-010**: System MUST validate date strings during parsing and throw exceptions immediately with clear error messages for invalid dates (non-existent dates, malformed strings, out-of-range values)
- **FR-011**: System MUST validate custom calendar configurations and reject logically impossible configurations
- **FR-012**: System MUST correctly handle leap year calculations according to each calendar's specific leap year rules
- **FR-013**: System MUST support date arithmetic (adding/subtracting time spans to/from time points)
- **FR-014**: System MUST handle dates before and after the calendar epoch (negative years or BCE/BC dates) with full arithmetic support and configurable epoch notation per calendar
- **FR-015**: Profile metadata MUST be queryable, including available profile names, calendar properties, and setting information
- **FR-016**: System MUST prevent operations between TimePoint objects from different calendar systems and throw clear exceptions indicating calendar incompatibility

### Key Entities

- **Calendar**: Represents a calendar system with specific rules. Contains configuration for months, days, weeks, leap years, and formatting patterns. Can be instantiated from profiles or custom configuration.

- **TimePoint**: Represents a specific point in time within a calendar system. Immutable value object containing year, month, day, and optional time components. Tied to the calendar that created it and cannot be mixed with TimePoints from other calendars in comparisons or arithmetic operations.

- **TimeSpan**: Represents a duration between two points in time. Internally represented as total seconds/milliseconds for maximum precision and time-of-day handling. Provides methods for duration queries (total days, hours, minutes, seconds). Supports positive and negative spans for past/future direction.

- **CalendarProfile**: Represents a pre-configured calendar template. Contains calendar rules, month names, day counts, leap year patterns, epoch information with configurable notation (CE/BCE, BC/AD, or fantasy-world-specific), and metadata about the calendar's source (game setting, real-world calendar, etc.).

- **CalendarConfiguration**: Represents custom calendar parameters for creating non-profile calendars. Includes all necessary parameters to define calendar structure and behavior.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Developers can create a working calendar instance with a pre-built profile in a single line of code
- **SC-002**: The library correctly calculates date differences across leap years with 100% accuracy for all supported profiles
- **SC-003**: Date parsing and formatting achieve round-trip consistency (parse → format → parse produces equivalent results) for all supported date formats
- **SC-004**: The library supports at least 7 calendar profiles at initial release (Gregorian + 6 fantasy settings)
- **SC-005**: Custom calendar creation succeeds with full validation feedback when calendar parameters are valid
- **SC-006**: Invalid date operations (parsing bad dates, invalid configurations) produce clear, actionable error messages within 100ms
- **SC-007**: Date arithmetic operations (add/subtract time spans) complete in under 10ms for typical use cases (spans under 1000 years)
- **SC-008**: The library handles dates from 10,000 BCE to 10,000 CE without integer overflow or precision loss

## Assumptions

- **Time Zone Handling**: The calendar system is timezone-agnostic. Time points represent calendar dates without timezone conversion. Users requiring timezone support should use this library in combination with timezone libraries.

- **Time Components**: While the focus is on dates (year/month/day), time-of-day components (hours/minutes/seconds) are supported as optional properties of TimePoint. If not specified, they default to midnight.

- **Calendar Epochs**: Each calendar profile defines its own epoch (year 0 or equivalent) and epoch notation. Dates before the epoch are represented with negative years or era-specific notation (CE/BCE for Gregorian with optional BC/AD, DR for Faerûn, AR for Golarion, etc.) according to calendar convention. Full date arithmetic is supported across the epoch boundary.

- **Date Format Patterns**: Each calendar profile provides default formatting patterns. The system supports flexible parsing with multiple format patterns but defaults to calendar-specific conventions for formatting output.

- **Leap Year Rules**: Leap year rules are defined per calendar. For calendars without explicit leap years in their lore, the system uses the most commonly accepted interpretation or published game rules.

- **Fantasy Calendar Accuracy**: Fantasy calendar profiles are based on published game setting materials. Where official sources conflict or are ambiguous, the most recent or widely-accepted interpretation is used.

- **Performance Target**: The library is optimized for typical RPG and application use cases (date ranges within ~10,000 years, operations on single dates or small sets). It is not optimized for bulk astronomical calculations or database-scale date processing.

- **Internationalization**: Month and day names are provided in English by default. The architecture allows for future localization support but i18n is not included in the initial release.

## Dependencies

- **PHP Version**: PHP 8.1 or higher (required for readonly properties, enums, and modern type features)
- **Composer**: Package management and distribution
- **PHPUnit**: Testing framework for TDD implementation
- **PSR-12 Compliance Tools**: PHP_CodeSniffer or PHP-CS-Fixer for style checking
- **Static Analysis**: PHPStan or Psalm at strict level

## Out of Scope

The following are explicitly excluded from this feature:

- **Timezone Conversion**: Converting dates between different timezones
- **Calendar Conversion**: Converting dates from one calendar system to another (e.g., Gregorian to Faerûn)
- **Astronomical Calculations**: Moon phases, solar events, planetary positions
- **Internationalization**: Translations of month/day names, localized formatting
- **Recurring Events**: Calculating repeated dates (e.g., "every 3rd Thursday")
- **Business Day Calculations**: Excluding weekends/holidays from spans
- **Database Integration**: ORM mappings, persistence layer, database types
- **Serialization Formats**: Beyond basic string formatting (no iCal, JSON, XML export)
