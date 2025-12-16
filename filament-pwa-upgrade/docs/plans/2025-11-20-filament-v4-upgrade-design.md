# Filament PWA v4 Upgrade Design

**Date:** 2025-11-20
**Status:** Approved
**Type:** Breaking Change - Major Version Upgrade

## Overview

Upgrade the tomatophp/filament-pwa package from Filament v3.2 to v4.0 compatibility. This is a breaking change that will require a major version bump and includes adding a comprehensive test suite using Pest 4.

## Goals

1. Full Filament v4 compatibility
2. Comprehensive test coverage (plugin, settings page, manifest generation, service worker)
3. Clean codebase without v3 compatibility shims
4. Clear migration guide for users

## Migration Strategy

**Approach:** Clean v4-only upgrade with breaking changes.

**Rationale:** Maintaining dual v3/v4 support adds complexity without significant benefit. Users can stay on v1.x for v3 compatibility or upgrade to v2.x for v4.

## Requirements Changes

### Minimum Requirements (Breaking Changes)

- **PHP:** 8.1|8.2 → 8.2+
- **Laravel:** (current) → 11.28+
- **Filament:** 3.2 → 4.0+

### New Dependencies

- `pestphp/pest`: ^4.0
- `orchestra/testbench`: ^10.0
- `filament/filament`: ^4.0
- `filament/notifications`: ^4.0
- `filament/spatie-laravel-settings-plugin`: ^4.0

## API Migrations

### PWASettingsPage.php

**Form Schema Migration:**
```php
// OLD (v3)
protected function getFormSchema(): array
{
    return [/* fields */];
}

// NEW (v4)
public function form(Schema $schema): Schema
{
    return $form->schema([/* fields */]);
}
```

**Actions Migration:**
```php
// OLD (v3)
use Filament\Pages\Actions\Action;

protected function getActions(): array
{
    return [Action::make('back')...];
}

// NEW (v4)
use Filament\Actions\Action;

protected function getHeaderActions(): array
{
    return [Action::make('back')...];
}
```

**FileUpload Visibility:**

All FileUpload components must explicitly set public visibility:

```php
// Apply to ALL icon and splash screen uploads
FileUpload::make('pwa_icons_72x72')
    ->acceptedFileTypes(['image/png'])
    ->visibility('public')  // NEW - required for v4
    ->label(...)
```

**Reasoning:** Filament v4 defaults FileUpload to private visibility. PWA icons and splash screens must be publicly accessible for the PWA to function.

### Grid/Section Layout

Review sections for potential layout issues. Filament v4 changed default spanning behavior:
- Most components no longer span full width by default
- Use `columnSpanFull()` if needed to restore v3 behavior
- Current `Grid::make(['default' => 2])` should work fine

## Test Suite Design

### Framework: Pest 4

**Structure:**
```
tests/
├── Pest.php
├── TestCase.php
├── Feature/
│   ├── PWAPluginTest.php
│   ├── PWASettingsPageTest.php
│   └── PWAControllerTest.php
└── Unit/
    └── ManifestServiceTest.php
```

### Test Coverage

**Unit Tests:**

1. **ManifestServiceTest.php**
   - Manifest JSON structure validation
   - Icon/splash screen URL generation
   - Fallback values when settings empty
   - Color format validation

**Feature Tests:**

1. **PWAPluginTest.php**
   - Plugin registers on panel
   - Render hooks registered correctly
   - Settings hub integration works
   - Plugin can be disabled

2. **PWASettingsPageTest.php**
   - Page renders without errors
   - Form contains all expected fields
   - FileUpload fields have public visibility
   - Save functionality works
   - File uploads persist correctly
   - Back action redirects properly

3. **PWAControllerTest.php**
   - `/manifest.json` returns valid JSON
   - `/serviceworker.js` returns valid JavaScript
   - Routes are registered
   - Content-Type headers are correct

### Testing Infrastructure

**Files to Create:**

1. `phpunit.xml` - PHPUnit configuration for Pest
2. `tests/Pest.php` - Pest configuration and helpers
3. `tests/TestCase.php` - Base test case with Filament setup
4. `testbench.yaml` - Orchestra Testbench configuration

## Implementation Steps

### Phase 1: Setup (TDD Foundation)

1. Fork repository to GitHub account
2. Clone and create branch: `feature/filament-v4-upgrade`
3. Set up git worktree for isolation
4. Create testing infrastructure files
5. Update composer.json dependencies

### Phase 2: Test-Driven Migration

1. **Write failing tests first** for each component
2. Migrate `PWASettingsPage.php`:
   - Update form schema method
   - Update actions method and namespace
   - Add visibility to FileUploads
3. Update `FilamentPWAPlugin.php` if needed
4. Ensure all tests pass green

### Phase 3: Documentation

1. Update README.md:
   - New minimum requirements
   - Updated installation steps
   - Filament v4 notice
2. Create UPGRADE.md:
   - Breaking changes list
   - Step-by-step migration guide
   - API changes reference
3. Update CHANGELOG.md with v2.0.0 entry

### Phase 4: PR Submission

**PR Title:** `[Breaking] Add Filament v4 support with comprehensive tests`

**PR Description Sections:**
- Overview of changes
- Breaking changes with migration steps
- New features (comprehensive test suite with Pest 4)
- Link to Filament v4 upgrade guide

## Breaking Changes Summary

For end users upgrading to this version:

1. **PHP 8.2+ required** (was 8.1+)
2. **Filament v4.0+ required** (was v3.2+)
3. **Laravel 11.28+ required**
4. **SettingsPage API changes** (affects custom extensions only)
5. **File visibility changes** (automatic - files now explicitly public)

## Migration Guide for Users

```bash
# 1. Update composer.json
"require": {
    "php": "^8.2",
    "tomatophp/filament-pwa": "^2.0"
}

# 2. Update dependencies
composer update

# 3. Clear caches
php artisan optimize:clear

# 4. Republish assets if customized
php artisan vendor:publish --tag="filament-pwa-views" --force
```

## Success Criteria

- [ ] All Pest tests pass
- [ ] Package installs cleanly on Filament v4
- [ ] PWA functionality works identically to v3
- [ ] Settings page renders and saves correctly
- [ ] File uploads maintain public visibility
- [ ] Manifest and service worker generate properly
- [ ] PR approved and merged upstream

## Risk Mitigation

**Risk:** FileUpload visibility breaks existing PWAs
**Mitigation:** Explicit public visibility setting ensures compatibility

**Risk:** Layout changes from Grid/Section spanning
**Mitigation:** Test thoroughly, add columnSpanFull() if needed

**Risk:** Users stuck on v3 can't upgrade
**Mitigation:** Comprehensive UPGRADE.md guide, maintain v1.x branch for v3 users

## Notes

- This design follows Filament's official v4 upgrade guide
- TDD approach ensures no regressions
- Clean v4-only implementation simplifies maintenance
- Pest 4 provides modern, expressive testing syntax
