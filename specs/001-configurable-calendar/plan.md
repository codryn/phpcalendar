# Implementation Plan: Configurable Calendar System

**Branch**: `001-configurable-calendar` | **Date**: 2026-01-08 | **Spec**: [spec.md](spec.md)
**Input**: Feature specification from `/specs/001-configurable-calendar/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

Deliver a professionally maintained PHP Composer package (codryn/phpcalendar) that provides a configurable calendar system supporting both real-world (Gregorian) and fantasy setting calendars (Faerûn, Golarion, DSA, Eberron, Dragonlance, Greyhawk). The system enables date parsing/formatting, temporal arithmetic with TimeSpan calculations, and custom calendar creation. Built with PHP 8.0+ compatibility, strict PSR-12 compliance, comprehensive PHPUnit test coverage following TDD methodology, and strict static analysis (PHPStan/Psalm).

## Technical Context

**Language/Version**: PHP 8.0 minimum, PHP 8.3 development target (maintain backward compatibility to PHP 8.0)
**Primary Dependencies**: None for core library (zero-dependency design), PHPUnit for testing, PHP_CodeSniffer/PHP-CS-Fixer for PSR-12, PHPStan or Psalm for static analysis
**Storage**: N/A (in-memory value objects, no persistence layer)
**Testing**: PHPUnit 9.5+ (compatible with PHP 8.0+) with comprehensive unit and integration test coverage
**Target Platform**: Cross-platform PHP (Linux, macOS, Windows) - library targets any PHP 8.0+ environment
**Project Type**: Single Composer library package (codryn/phpcalendar)
**Performance Goals**: Date arithmetic <10ms for typical operations (spans <1000 years), parsing/formatting <5ms per operation
**Constraints**: PHP 8.0 compatibility (no PHP 8.1+ exclusive features like readonly, enums in implementation), timezone-agnostic design, no external dependencies for core
**Scale/Scope**: Library for 7+ calendar profiles, support dates 10,000 BCE to 10,000 CE, handle RPG campaign timelines and historical date calculations

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

**Initial Check (Pre-Research)**:
- [x] **Composer Package Readiness**: Feature integrates with codryn/phpcalendar package structure - Confirmed: designed as standalone Composer package with proper namespace
- [x] **PSR-12 Compliance**: All code will adhere to PSR-12 Extended Coding Style - Confirmed: PHP-CS-Fixer/PHP_CodeSniffer configured in Phase 1
- [x] **TDD Approach**: Acceptance tests defined first, implementation follows test-first methodology - Confirmed: 22 testable acceptance scenarios defined in spec.md
- [x] **Static Analysis**: PHPStan/Psalm strict level compatibility verified - Confirmed: PHPStan level 9 or Psalm strict mode to be configured
- [x] **Type Safety**: All code uses strict_types=1 and comprehensive type declarations - Confirmed: PHP 8.0+ type system with strict declarations
- [x] **Test Coverage**: PHPUnit tests will provide comprehensive coverage - Confirmed: PHPUnit 9.5+ with coverage reporting configured
- [x] **Documentation**: User-facing documentation requirements identified - Confirmed: README, API docs, calendar profile docs required

**Post-Design Check (After Phase 1)**:
- [x] **Composer Package Structure**: Confirmed PSR-4 autoloading with `Codryn\\PHPCalendar` namespace, composer.json defined
- [x] **PSR-12 Tooling**: .php-cs-fixer.php configuration researched and documented
- [x] **TDD Test Structure**: tests/ organized by Unit/Integration/Acceptance matching user stories
- [x] **Static Analysis Config**: phpstan.neon level 9 configuration researched and documented
- [x] **Type Declarations**: All contracts specify strict types, union types (PHP 8.0), avoid 8.1+ features
- [x] **Test Organization**: 5 acceptance test files mapped to 5 user stories, comprehensive unit tests for all classes
- [x] **Documentation Deliverables**: quickstart.md created, 3 API contracts written (Calendar, TimePoint, TimeSpan), data model documented

**GATE PASSED** ✅ - All constitution requirements met. Ready to proceed to Phase 2 (Tasks).

## Project Structure

### Documentation (this feature)

```text
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)

```text
src/
├── Calendar/
│   ├── Calendar.php                  # Main Calendar class
│   ├── TimePoint.php                 # Immutable time point value object
│   ├── TimeSpan.php                  # Duration value object
│   ├── CalendarProfile.php           # Profile interface/base
│   ├── CalendarConfiguration.php     # Custom calendar config
│   └── Exception/
│       ├── InvalidDateException.php
│       ├── InvalidCalendarConfigException.php
│       └── IncompatibleCalendarException.php
├── Profile/
│   ├── GregorianProfile.php
│   ├── FaerunProfile.php
│   ├── GolarionProfile.php
│   ├── DSAProfile.php
│   ├── EberronProfile.php
│   ├── DragonlanceProfile.php
│   └── GreyhawkProfile.php
├── Parser/
│   ├── DateParser.php                # Date string parsing
│   └── DateFormatter.php             # Date formatting
└── Validator/
    └── CalendarValidator.php         # Configuration validation

tests/
├── Unit/
│   ├── Calendar/
│   ├── Profile/
│   ├── Parser/
│   └── Validator/
├── Integration/
│   ├── DateArithmeticTest.php
│   ├── CrossCalendarTest.php
│   └── ProfileFactoryTest.php
└── Acceptance/
    ├── UserStory1Test.php            # Profile-based calendar creation
    ├── UserStory2Test.php            # Date parsing and formatting
    ├── UserStory3Test.php            # Time difference calculations
    ├── UserStory4Test.php            # Custom calendar creation
    └── UserStory5Test.php            # Extended fantasy profiles

composer.json                          # Package definition
phpunit.xml                            # PHPUnit configuration
phpstan.neon or psalm.xml              # Static analysis config
.php-cs-fixer.php                      # PSR-12 style config
README.md                              # User documentation
CHANGELOG.md                           # Version history
```

**Structure Decision**: Single project structure chosen as this is a standalone PHP library. PSR-4 autoloading with `Codryn\\PHPCalendar` namespace. Core classes in `src/`, comprehensive tests in `tests/` organized by test type (Unit, Integration, Acceptance). Profile classes are separated for modularity and independent testing.

## Complexity Tracking

> No constitution violations - all gates passed. This section intentionally left empty.
