# Tasks: Configurable Calendar System

**Input**: Design documents from `/specs/001-configurable-calendar/`
**Prerequisites**: plan.md (✓), spec.md (✓), research.md (✓), data-model.md (✓), contracts/ (✓)

**Tests**: TDD is mandatory per constitution - all tests MUST be written BEFORE implementation

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story?] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Path Conventions

- **Single project**: `src/`, `tests/` at repository root
- Namespace: `Codryn\PHPCalendar`
- PSR-4 autoloading

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure per constitution requirements

- [ ] T001 Create composer.json with PHP 8.0+ requirement and PSR-4 autoloading for Codryn\\PHPCalendar namespace
- [ ] T002 [P] Create directory structure: src/{Calendar,Profile,Parser,Validator,Exception}, tests/{Unit,Integration,Acceptance}
- [ ] T003 [P] Configure PHPUnit 9.5+ in phpunit.xml with coverage reporting and test suites (Unit, Integration, Acceptance)
- [ ] T004 [P] Configure PHP-CS-Fixer in .php-cs-fixer.php for PSR-12 compliance with strict_types enforcement
- [ ] T005 [P] Configure PHPStan level 9 in phpstan.neon with strictRules for all PHP 8.0 compatible checks
- [ ] T006 [P] Create GitHub Actions workflow .github/workflows/ci.yml for PHP 8.0/8.1/8.2/8.3 matrix testing
- [ ] T007 Create README.md with installation, quickstart, and package badges
- [ ] T008 Create CHANGELOG.md following Keep a Changelog format

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [ ] T009 [P] Create CalendarException base class in src/Exception/CalendarException.php with strict_types=1
- [ ] T010 [P] Create InvalidDateException in src/Exception/InvalidDateException.php
- [ ] T011 [P] Create InvalidCalendarConfigException in src/Exception/InvalidCalendarConfigException.php
- [ ] T012 [P] Create IncompatibleCalendarException in src/Exception/IncompatibleCalendarException.php
- [ ] T013 [P] Create DateArithmeticException in src/Exception/DateArithmeticException.php
- [ ] T014 [P] Create CalendarProfileInterface in src/Calendar/CalendarProfileInterface.php with all required methods
- [ ] T015 Create AbstractCalendarProfile base class in src/Profile/AbstractCalendarProfile.php implementing shared logic
- [ ] T016 Write unit tests for exception hierarchy in tests/Unit/Exception/

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - Create Calendar with Pre-built Profile (Priority: P1) 🎯 MVP

**Goal**: Enable developers to instantiate calendars using pre-built profiles (Gregorian, Faerûn) with immediate usability

**Independent Test**: Creating a calendar with "gregorian" profile returns a fully functional calendar with correct month names, leap year logic, and date operations

### Tests for User Story 1 (TDD - Write FIRST) ⚠️

> **CRITICAL: Write these tests FIRST, ensure they FAIL before implementation**

- [ ] T017 [P] [US1] Write acceptance test UserStory1Test.php: Create Gregorian calendar and verify 12 months, leap year rules
- [ ] T018 [P] [US1] Write acceptance test UserStory1Test.php: Create Faerûn calendar and verify Harptos calendar properties  
- [ ] T019 [P] [US1] Write acceptance test UserStory1Test.php: Query calendar properties (months, days per month, year structure)
- [ ] T020 [P] [US1] Write acceptance test UserStory1Test.php: Invalid profile name throws exception with available profiles list
- [ ] T021 [P] [US1] Write unit tests for GregorianProfile in tests/Unit/Profile/GregorianProfileTest.php
- [ ] T022 [P] [US1] Write unit tests for FaerunProfile in tests/Unit/Profile/FaerunProfileTest.php

### Implementation for User Story 1

- [ ] T023 [P] [US1] Implement GregorianProfile class in src/Profile/GregorianProfile.php with 12 months, correct leap year logic (divisible by 4, not 100, except 400)
- [ ] T024 [P] [US1] Implement FaerunProfile class in src/Profile/FaerunProfile.php with Harptos calendar (12 months x 30 days + 5 festivals, Shieldmeet leap year)
- [ ] T025 [US1] Create ProfileRegistry service in src/Calendar/ProfileRegistry.php to manage profile lookup by name
- [ ] T026 [US1] Implement Calendar class in src/Calendar/Calendar.php with static fromProfile() factory method
- [ ] T027 [US1] Add calendar property getters in Calendar.php: getName(), getDisplayName(), getMonthNames(), getMonthCount(), getDaysInMonth(), isLeapYear(), getEpochNotation()
- [ ] T028 [US1] Write integration test in tests/Integration/ProfileFactoryTest.php verifying Calendar::fromProfile() for both Gregorian and Faerûn

**Checkpoint**: At this point, User Story 1 is fully functional - developers can create calendars from profiles and query their properties

---

## Phase 4: User Story 2 - Parse and Format Dates (Priority: P1) 🎯 MVP

**Goal**: Enable parsing date strings to TimePoint objects and formatting TimePoint back to strings with round-trip consistency

**Independent Test**: Parse "December 25, 2024" to TimePoint, format back, verify round-trip equals original

### Tests for User Story 2 (TDD - Write FIRST) ⚠️

> **CRITICAL: Write these tests FIRST, ensure they FAIL before implementation**

- [ ] T029 [P] [US2] Write acceptance test UserStory2Test.php: Parse Gregorian date string to TimePoint
- [ ] T030 [P] [US2] Write acceptance test UserStory2Test.php: Parse Faerûn date string "15 Mirtul 1492 DR" to TimePoint
- [ ] T031 [P] [US2] Write acceptance test UserStory2Test.php: Format TimePoint to calendar-appropriate string
- [ ] T032 [P] [US2] Write acceptance test UserStory2Test.php: Round-trip parse→format→parse produces equivalent TimePoint
- [ ] T033 [P] [US2] Write acceptance test UserStory2Test.php: Invalid date string throws InvalidDateException
- [ ] T034 [P] [US2] Write acceptance test UserStory2Test.php: Multiple format patterns are tried until one matches
- [ ] T035 [P] [US2] Write unit tests for TimePoint in tests/Unit/Calendar/TimePointTest.php
- [ ] T036 [P] [US2] Write unit tests for DateParser in tests/Unit/Parser/DateParserTest.php
- [ ] T037 [P] [US2] Write unit tests for DateFormatter in tests/Unit/Parser/DateFormatterTest.php

### Implementation for User Story 2

- [ ] T038 [P] [US2] Implement TimePoint value object in src/Calendar/TimePoint.php with year, month, day, hour, minute, second, microsecond properties (all with strict types)
- [ ] T039 [P] [US2] Add TimePoint getters: getCalendar(), getYear(), getMonth(), getDay(), getHour(), getMinute(), getSecond(), getMicrosecond()
- [ ] T040 [P] [US2] Implement DateParser class in src/Parser/DateParser.php with parse() method trying format patterns
- [ ] T041 [P] [US2] Implement DateFormatter class in src/Parser/DateFormatter.php with format() method using profile patterns
- [ ] T042 [US2] Add Calendar::parse() method delegating to DateParser with validation
- [ ] T043 [US2] Add Calendar::format() method delegating to DateFormatter
- [ ] T044 [US2] Add date validation in TimePoint constructor: check month in range, day exists in calendar, hour/minute/second/microsecond in valid ranges
- [ ] T045 [US2] Write integration test in tests/Integration/DateParsingTest.php for end-to-end parse and format scenarios

**Checkpoint**: At this point, User Story 2 is fully functional - developers can parse and format dates with validation

---

## Phase 5: User Story 3 - Calculate Time Differences (Priority: P1) 🎯 MVP

**Goal**: Enable calculating TimeSpan between two TimePoints and performing date arithmetic (add/subtract)

**Independent Test**: Calculate difference between "2024-01-01" and "2024-12-31", verify getTotalDays() returns 365

### Tests for User Story 3 (TDD - Write FIRST) ⚠️

> **CRITICAL: Write these tests FIRST, ensure they FAIL before implementation**

- [ ] T046 [P] [US3] Write acceptance test UserStory3Test.php: Calculate diff between two TimePoints returns TimeSpan with correct day count
- [ ] T047 [P] [US3] Write acceptance test UserStory3Test.php: Diff spanning multiple years accounts for leap years correctly
- [ ] T048 [P] [US3] Write acceptance test UserStory3Test.php: TimeSpan.getTotalDays() returns accurate count
- [ ] T049 [P] [US3] Write acceptance test UserStory3Test.php: TimePoint.add(TimeSpan) returns new future TimePoint
- [ ] T050 [P] [US3] Write acceptance test UserStory3Test.php: TimePoint.subtract(TimeSpan) returns new past TimePoint
- [ ] T051 [P] [US3] Write acceptance test UserStory3Test.php: Diff with end before start returns negative TimeSpan
- [ ] T052 [P] [US3] Write acceptance test UserStory3Test.php: Invalid date arithmetic (Jan 31 + 1 month) throws DateArithmeticException
- [ ] T053 [P] [US3] Write unit tests for TimeSpan in tests/Unit/Calendar/TimeSpanTest.php
- [ ] T054 [P] [US3] Write unit tests for TimePoint arithmetic in tests/Unit/Calendar/TimePointArithmeticTest.php

### Implementation for User Story 3

- [ ] T055 [P] [US3] Implement TimeSpan value object in src/Calendar/TimeSpan.php with seconds and microseconds properties (strict types)
- [ ] T056 [P] [US3] Add TimeSpan factory: static fromSeconds(int $seconds, int $microseconds = 0): self
- [ ] T057 [P] [US3] Add TimeSpan getters: getTotalSeconds(), getMicroseconds(), getTotalDays(), getTotalHours(), getTotalMinutes()
- [ ] T058 [P] [US3] Add TimeSpan helpers: isNegative(), abs(), negate(), add(TimeSpan), subtract(TimeSpan)
- [ ] T059 [US3] Implement Calendar::diff(TimePoint, TimePoint) converting both to seconds since epoch and calculating difference
- [ ] T060 [US3] Add cross-calendar validation in diff() throwing IncompatibleCalendarException if calendars differ
- [ ] T061 [US3] Implement TimePoint::add(TimeSpan) converting to seconds, adding span, converting back with date validation
- [ ] T062 [US3] Implement TimePoint::subtract(TimeSpan) using add() with negated span
- [ ] T063 [US3] Add date validation in arithmetic throwing DateArithmeticException for invalid results (e.g., Feb 31)
- [ ] T064 [US3] Implement TimePoint comparison methods: equals(), isBefore(), isAfter() with cross-calendar validation
- [ ] T065 [US3] Write integration test in tests/Integration/DateArithmeticTest.php for complex multi-calendar scenarios
- [ ] T066 [US3] Write integration test in tests/Integration/CrossCalendarTest.php verifying IncompatibleCalendarException

**Checkpoint**: At this point, User Story 3 is fully functional - COMPLETE MVP with profile-based calendars, parsing/formatting, and date arithmetic

---

## Phase 6: User Story 4 - Create Fully Custom Calendar (Priority: P2)

**Goal**: Enable developers to create custom calendars with user-defined month names, day counts, leap year rules, and epoch notation

**Independent Test**: Create custom calendar with 3 months of 30 days each, verify it works identically to profile-based calendars

### Tests for User Story 4 (TDD - Write FIRST) ⚠️

> **CRITICAL: Write these tests FIRST, ensure they FAIL before implementation**

- [ ] T067 [P] [US4] Write acceptance test UserStory4Test.php: Create custom calendar with configuration, verify properties
- [ ] T068 [P] [US4] Write acceptance test UserStory4Test.php: Custom calendar with irregular month lengths handles date calculations
- [ ] T069 [P] [US4] Write acceptance test UserStory4Test.php: Custom leap year rules (every 5 years) applied correctly
- [ ] T070 [P] [US4] Write acceptance test UserStory4Test.php: Invalid configuration (negative days, empty names) throws InvalidCalendarConfigException
- [ ] T071 [P] [US4] Write acceptance test UserStory4Test.php: Custom calendar behaves identically to profile-based for parsing/formatting
- [ ] T072 [P] [US4] Write unit tests for CalendarConfiguration in tests/Unit/Calendar/CalendarConfigurationTest.php
- [ ] T073 [P] [US4] Write unit tests for CalendarValidator in tests/Unit/Validator/CalendarValidatorTest.php

### Implementation for User Story 4

- [ ] T074 [P] [US4] Implement CalendarConfiguration value object in src/Calendar/CalendarConfiguration.php with all properties (strict types)
- [ ] T075 [P] [US4] Add CalendarConfiguration constructor with named parameters: name, displayName, monthNames, daysPerMonth, leapYearRule, epochNotation, formatPatterns
- [ ] T076 [P] [US4] Implement CalendarValidator in src/Validator/CalendarValidator.php with validate() method checking all rules
- [ ] T077 [US4] Add validation rules: non-empty name, at least 1 month, positive days, matching array lengths, valid epoch notation
- [ ] T078 [US4] Create CustomProfile adapter in src/Profile/CustomProfile.php wrapping CalendarConfiguration as CalendarProfileInterface
- [ ] T079 [US4] Implement Calendar::fromConfiguration(CalendarConfiguration) static factory validating config and wrapping in CustomProfile
- [ ] T080 [US4] Write integration test in tests/Integration/CustomCalendarTest.php for end-to-end custom calendar creation and usage

**Checkpoint**: At this point, User Story 4 is fully functional - developers can create fully custom calendars

---

## Phase 7: User Story 5 - Extended Fantasy Calendar Profiles (Priority: P3)

**Goal**: Provide pre-built profiles for 5 additional fantasy settings: Golarion, DSA, Eberron, Dragonlance, Greyhawk

**Independent Test**: Each profile instantiates correctly and matches published calendar rules for that setting

### Tests for User Story 5 (TDD - Write FIRST) ⚠️

> **CRITICAL: Write these tests FIRST, ensure they FAIL before implementation**

- [ ] T081 [P] [US5] Write acceptance test UserStory5Test.php: Create Golarion calendar, verify Absalom Reckoning properties
- [ ] T082 [P] [US5] Write acceptance test UserStory5Test.php: Create DSA calendar with "dsa" alias, verify Aventurian calendar
- [ ] T083 [P] [US5] Write acceptance test UserStory5Test.php: Create Eberron calendar, verify Galifar Calendar properties
- [ ] T084 [P] [US5] Write acceptance test UserStory5Test.php: Create Dragonlance calendar, verify Krynn calendar
- [ ] T085 [P] [US5] Write acceptance test UserStory5Test.php: Create Greyhawk calendar, verify Common Year properties
- [ ] T086 [P] [US5] Write acceptance test UserStory5Test.php: Query profile metadata returns source and setting information
- [ ] T087 [P] [US5] Write unit tests for GolarionProfile in tests/Unit/Profile/GolarionProfileTest.php
- [ ] T088 [P] [US5] Write unit tests for DSAProfile in tests/Unit/Profile/DSAProfileTest.php
- [ ] T089 [P] [US5] Write unit tests for EberronProfile in tests/Unit/Profile/EberronProfileTest.php
- [ ] T090 [P] [US5] Write unit tests for DragonlanceProfile in tests/Unit/Profile/DragonlanceProfileTest.php
- [ ] T091 [P] [US5] Write unit tests for GreyhawkProfile in tests/Unit/Profile/GreyhawkProfileTest.php

### Implementation for User Story 5

- [ ] T092 [P] [US5] Implement GolarionProfile in src/Profile/GolarionProfile.php: 12 months, leap year every 8 years, Absalom Reckoning epoch
- [ ] T093 [P] [US5] Implement DSAProfile in src/Profile/DSAProfile.php: 12 months x 30 days + 5 nameless days, Bosparans Fall epoch
- [ ] T094 [P] [US5] Implement EberronProfile in src/Profile/EberronProfile.php: 12 months x 28 days, no leap years, Galifar epoch
- [ ] T095 [P] [US5] Implement DragonlanceProfile in src/Profile/DragonlanceProfile.php: varying month lengths, AC/PC epochs
- [ ] T096 [P] [US5] Implement GreyhawkProfile in src/Profile/GreyhawkProfile.php: 12 months x 28 days + 4 festivals, Common Year
- [ ] T097 [US5] Register all 5 new profiles in ProfileRegistry with appropriate aliases (e.g., "dsa", "black-eye")
- [ ] T098 [US5] Add profile metadata support: getMetadata() returning source books, setting name, description
- [ ] T099 [US5] Update README.md with examples for all 7 calendar profiles

**Checkpoint**: At this point, User Story 5 is fully functional - all 7 fantasy/real-world calendar profiles available

---

## Phase 8: Polish & Cross-Cutting Concerns

**Purpose**: Final quality improvements, documentation, and release preparation

- [ ] T100 [P] Add type hints and PHPDoc blocks to all public methods with @param, @return, @throws annotations
- [ ] T101 [P] Run PHP-CS-Fixer and fix all PSR-12 violations across entire codebase
- [ ] T102 [P] Run PHPStan level 9 and resolve all errors/warnings
- [ ] T103 [P] Verify all files have `declare(strict_types=1);` at the top
- [ ] T104 Run full PHPUnit test suite and verify 100% of acceptance tests pass
- [ ] T105 Generate code coverage report and verify >90% coverage for src/
- [ ] T106 [P] Create API documentation in docs/ folder with usage examples for each class
- [ ] T107 [P] Write CONTRIBUTING.md with development setup, testing guidelines, PSR-12 requirements
- [ ] T108 [P] Create LICENSE file (MIT per constitution)
- [ ] T109 Update README.md with installation, quickstart, all 7 calendar examples, links to docs
- [ ] T110 Create .gitattributes for proper language detection and export-ignore for dev files
- [ ] T111 Tag v1.0.0 release and push to GitHub
- [ ] T112 Register package on packagist.org with proper metadata

**Final Checkpoint**: Package is ready for public release - all constitution requirements met

---

## Dependencies & Execution Order

### Critical Path (Must be sequential)

1. **Phase 1 (Setup)** → **Phase 2 (Foundation)** → **Phase 3-7 (User Stories)** → **Phase 8 (Polish)**
2. Within Phase 2: Exceptions must exist before profiles/calendar classes
3. Within each user story: Tests MUST be written before implementation (TDD Red-Green-Refactor)

### User Story Dependencies

```
Phase 3 (US1) ──┐
                ├─→ Phase 4 (US2) ─→ Phase 5 (US3) ─→ Phase 6 (US4) ─→ Phase 7 (US5)
                │      ↓                  ↓
                │   Needs US1         Needs US1+US2
                └──────────────────────────────────────────────────────────────────→ Phase 8

- US1 (Profiles) is foundation for all other stories
- US2 (Parsing) depends on US1 (needs Calendar + TimePoint)
- US3 (Arithmetic) depends on US1+US2 (needs TimePoint from parsing)
- US4 (Custom) depends on US1 (parallel to US2/US3 functionally)
- US5 (Extended Profiles) depends on US1 (just adds more profiles)
```

**Recommended MVP Delivery**: Complete Phase 1 → Phase 2 → Phase 3 (US1) → Phase 4 (US2) → Phase 5 (US3) for working calendar system

### Parallel Execution Opportunities

**Within Phase 1 (Setup)**: T002-T008 can all run in parallel after T001

**Within Phase 2 (Foundation)**: T009-T015 can all run in parallel (different files)

**Within User Stories**: 
- All test writing tasks (e.g., T017-T022 in US1) can run in parallel
- Profile implementation tasks can run in parallel (e.g., T023-T024 in US1)
- All US5 profile implementations (T092-T096) can run in parallel

**Example Parallel Batch for US1**:
```bash
# Batch 1: Write all US1 tests in parallel
T017, T018, T019, T020, T021, T022

# Batch 2: Implement profiles in parallel
T023, T024

# Batch 3: Wire up registry and calendar
T025 → T026 → T027 → T028
```

---

## Task Validation

**Format Compliance**: ✅ All tasks follow `- [ ] T### [P?] [Story?] Description with file path`

**Task Count**: 112 total tasks
- Phase 1 (Setup): 8 tasks
- Phase 2 (Foundation): 8 tasks
- Phase 3 (US1): 12 tasks (6 tests + 6 implementation)
- Phase 4 (US2): 17 tasks (9 tests + 8 implementation)
- Phase 5 (US3): 21 tasks (9 tests + 12 implementation)
- Phase 6 (US4): 14 tasks (7 tests + 7 implementation)
- Phase 7 (US5): 19 tasks (11 tests + 8 implementation)
- Phase 8 (Polish): 13 tasks

**TDD Coverage**: ✅ All user stories have test tasks marked "Write FIRST"

**Parallelization**: ✅ 62 tasks marked [P] for parallel execution (55% of total)

**User Story Coverage**: ✅ All 5 user stories have dedicated phases with complete task breakdown

**Constitution Compliance**:
- ✅ PSR-12: Tasks for .php-cs-fixer.php config (T004) and validation (T101)
- ✅ TDD: All user stories have tests written before implementation
- ✅ Static Analysis: PHPStan level 9 config (T005) and validation (T102)
- ✅ Type Safety: strict_types verification (T103), PHP 8.0 types throughout
- ✅ Composer Package: Package setup (T001), Packagist registration (T112)
- ✅ Documentation: README (T007, T109), API docs (T106), CONTRIBUTING (T107)

---

## Implementation Strategy

**Recommended Approach**: Incremental delivery by user story

1. **Sprint 1 (MVP Core)**: Phase 1 + Phase 2 + Phase 3 (US1)
   - Deliverable: Calendar creation with Gregorian + Faerûn profiles
   - Testable: Can create calendars and query properties

2. **Sprint 2 (Date I/O)**: Phase 4 (US2)
   - Deliverable: Parse and format dates
   - Testable: End-to-end date parsing with validation

3. **Sprint 3 (Arithmetic)**: Phase 5 (US3)
   - Deliverable: Calculate time differences and perform date arithmetic
   - Testable: Complete MVP with all P1 features

4. **Sprint 4 (Flexibility)**: Phase 6 (US4)
   - Deliverable: Custom calendar creation
   - Testable: Homebrew calendars work identically to profiles

5. **Sprint 5 (Extended)**: Phase 7 (US5)
   - Deliverable: 5 additional fantasy calendar profiles
   - Testable: All 7 profiles available

6. **Sprint 6 (Release)**: Phase 8 (Polish)
   - Deliverable: v1.0.0 release to Packagist
   - Testable: Package installable via Composer

**Total Estimated Effort**: ~3-4 weeks for full implementation with comprehensive testing

---

**Next Command**: Begin implementation with `composer init` or review tasks for adjustments
