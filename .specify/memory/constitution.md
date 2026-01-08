<!--
═══════════════════════════════════════════════════════════════════════════
SYNC IMPACT REPORT
═══════════════════════════════════════════════════════════════════════════
Version: (new) → 1.0.0
Change Type: Initial constitution ratification

Principles Added:
- I. Professional Composer Package Distribution
- II. PSR-12 Extended Coding Style (NON-NEGOTIABLE)
- III. Test-Driven Development (NON-NEGOTIABLE)
- IV. Static Analysis & Type Safety
- V. Code Review & Compliance Verification
- VI. Automated CI/CD Pipeline
- VII. Open Source Distribution Standards

Templates Status:
✅ plan-template.md - Constitution Check section will enforce new gates
✅ spec-template.md - Acceptance scenarios align with TDD requirements
✅ tasks-template.md - Task structure supports TDD and CI requirements

Follow-up Actions:
- None - all placeholders filled

Commit Message: docs: ratify phpcalendar constitution v1.0.0 (composer package + TDD + PSR-12 compliance)
═══════════════════════════════════════════════════════════════════════════
-->

# PHPCalendar Constitution

## Core Principles

### I. Professional Composer Package Distribution

PHPCalendar MUST be developed and distributed as a professionally maintained Composer package under the namespace `codryn/phpcalendar`.

**Rationale**: Professional package distribution ensures accessibility, version management, and integration with the PHP ecosystem. This principle establishes PHPCalendar as a reusable component rather than an application-specific library.

**Requirements**:
- Package MUST be registered on packagist.org
- Package metadata (composer.json) MUST be accurate and complete at all times
- Version updates MUST be automatically synchronized with Packagist
- Package MUST follow Composer best practices for autoloading and dependency management

### II. PSR-12 Extended Coding Style (NON-NEGOTIABLE)

All code MUST strictly adhere to PSR-12 Extended Coding Style without exception.

**Rationale**: PSR-12 compliance ensures code consistency, readability, and interoperability with the broader PHP ecosystem. This is non-negotiable to maintain professional quality standards.

**Requirements**:
- All PHP files MUST follow PSR-12 formatting rules
- Automated style checks MUST be integrated into the development workflow
- PSR-12 violations MUST block merge
- Code reviews MUST explicitly verify PSR-12 compliance

### III. Test-Driven Development (NON-NEGOTIABLE)

TDD is mandatory for all feature development. Tests MUST be written first, approved by stakeholders, and initially fail before implementation begins.

**Rationale**: TDD ensures that features are designed around requirements, provides living documentation, and prevents regression. The Red-Green-Refactor cycle enforces quality at the design stage.

**Requirements**:
- Comprehensive PHPUnit test coverage is required for all features
- Tests MUST be written BEFORE implementation (Red phase)
- Tests MUST fail initially to verify they test the correct behavior
- Implementation proceeds only after tests are written and approved (Green phase)
- Refactoring occurs only with passing tests (Refactor phase)
- Test coverage MUST be tracked and reported in CI

### IV. Static Analysis & Type Safety

Static analysis tools MUST be used at strict level, and type declarations MUST be used wherever possible.

**Rationale**: Static analysis catches errors at development time, improves code quality, and enhances IDE support. Strict typing provides clear contracts and prevents type-related bugs.

**Requirements**:
- PHPStan or Psalm MUST be configured at strict level
- All files MUST declare `strict_types=1`
- Type hints MUST be used for all parameters, return types, and properties where possible
- Static analysis MUST pass before merge is allowed
- Baseline violations are not permitted for new code

### V. Code Review & Compliance Verification

All changes MUST undergo code review with explicit verification of constitution compliance.

**Rationale**: Code review provides quality gates, knowledge sharing, and ensures adherence to project standards. Constitution compliance must be an explicit checkpoint.

**Requirements**:
- All changes MUST be reviewed before merge
- Reviewer MUST explicitly verify:
  - PSR-12 compliance
  - Test coverage and TDD adherence
  - Static analysis passing
  - Constitution compliance
- Pull request template MUST include constitution compliance checklist
- Review comments MUST reference specific principles when raising concerns

### VI. Automated CI/CD Pipeline

A comprehensive automated CI pipeline MUST run on all changes and all checks MUST pass before merge is allowed.

**Rationale**: Automation ensures consistent quality gates, prevents human error, and provides fast feedback. Requiring passing checks before merge maintains main branch stability.

**Requirements**:
- CI pipeline MUST run on every push and pull request
- Pipeline MUST include:
  - PHPUnit test suite execution (all tests must pass)
  - PSR-12 style checks (must pass with zero violations)
  - Static analysis (PHPStan/Psalm at strict level, must pass)
  - Code coverage reporting (must meet minimum threshold)
- Merge MUST be blocked if any check fails
- Status checks MUST be required in branch protection rules

### VII. Open Source Distribution Standards

Source code MUST be hosted on GitHub with proper licensing, semantic versioning, and tagged releases.

**Rationale**: Open source distribution requires transparency, clear licensing, and predictable versioning to build trust and enable community contributions.

**Requirements**:
- Source code MUST be hosted at github.com/codryn/phpcalendar
- Repository MUST include MIT License file (already present)
- Project MUST maintain semantic versioning (MAJOR.MINOR.PATCH)
- Tagged releases MUST be created for all versions
- Release notes MUST document changes following semantic versioning principles:
  - MAJOR: Breaking changes (BC breaks in public API)
  - MINOR: New features (backward compatible additions)
  - PATCH: Bug fixes (backward compatible fixes)
- User-facing documentation MUST be comprehensive and maintained for each release

## Quality Gates

All features MUST pass through the following quality gates before being considered complete:

1. **Design Gate**: Acceptance tests written and approved
2. **Implementation Gate**: Tests pass, PSR-12 compliant, static analysis passes
3. **Review Gate**: Code review approved with explicit constitution compliance check
4. **CI Gate**: All automated checks pass
5. **Documentation Gate**: User-facing documentation updated

## Governance

This constitution supersedes all other development practices and conventions. It establishes the foundational principles that define PHPCalendar's development philosophy.

**Amendment Process**:
- Amendments MUST be proposed with clear rationale
- Amendments MUST be documented in this file with version increment
- Breaking changes to principles require MAJOR version bump
- New principles require MINOR version bump
- Clarifications require PATCH version bump

**Compliance Verification**:
- All pull requests MUST include constitution compliance checklist
- Code reviews MUST explicitly verify compliance with relevant principles
- CI pipeline MUST enforce automated compliance checks
- Constitution violations MUST be documented and justified if accepted (discouraged)

**Versioning Policy**:
- Constitution follows semantic versioning
- Version increments reflect impact on development workflow:
  - MAJOR: Backward incompatible principle changes (e.g., removing TDD requirement)
  - MINOR: New principles or significant expansions (e.g., adding security principle)
  - PATCH: Clarifications, typo fixes, non-semantic improvements

**Version**: 1.0.0 | **Ratified**: 2026-01-08 | **Last Amended**: 2026-01-08
