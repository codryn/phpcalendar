# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

Nothing yet.

## [1.0.0] - 2025-01-11

Initial public release of PHPCalendar - a type-safe, configurable calendar system for PHP.

### Added

**Core Features**
- Complete calendar system with support for both real-world and fantasy calendars
- `Calendar` class with fluent API for calendar operations
- `TimePoint` immutable value object for representing dates and times
- `TimeSpan` value object for representing durations
- `CalendarConfiguration` for creating custom calendars
- Date parsing with flexible format support
- Date formatting with customizable patterns
- Temporal arithmetic (add/subtract time spans, calculate differences)

**Calendar Profiles**
- **Gregorian**: Standard international calendar with proper leap year rules (BCE/CE)
- **Faerûn**: Harptos calendar from Forgotten Realms (Dale Reckoning)
- **Golarion**: Absalom Reckoning from Pathfinder (8-year leap cycle)
- **Das Schwarze Auge**: Aventurian calendar from The Dark Eye (Bosparans Fall)
- **Eberron**: Galifar Calendar from D&D Eberron (Years of Kingdom)
- **Dragonlance**: Krynn calendar from D&D Dragonlance (PC/AC epochs)
- **Greyhawk**: Common Year calendar from D&D Greyhawk (364-day year)

**Developer Experience**
- Full PHP 8.0+ type declarations with `strict_types` enabled
- PHPStan level 9 compliance (strictest static analysis)
- PSR-12 code style compliance
- Zero runtime dependencies
- Comprehensive documentation (API, usage guide, profile reference, custom calendar guide)
- 65 tests with 290 assertions (acceptance, unit, and integration suites)
- 72.91% code coverage
- GitHub Actions CI pipeline for PHP 8.0-8.3

**Documentation**
- Complete API documentation with method references and examples
- Usage guide with common patterns and real-world examples
- Calendar profiles reference with specifications for all 7 calendars
- Custom calendar creation guide with validation rules and best practices
- Contributing guide with development workflow and quality standards
- Comprehensive README with quickstart and examples for all calendars

**Infrastructure**
- Composer package configuration with PSR-4 autoloading
- PHPUnit 9.5+ test framework configuration
- PHP-CS-Fixer for automated PSR-12 enforcement
- PHPStan for static analysis
- GitHub Actions CI/CD pipeline
- MIT License
