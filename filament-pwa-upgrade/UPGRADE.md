# Upgrade Guide

## Upgrading from v1.x to v2.x

### Requirements

Version 2.0 requires:
- PHP 8.2+ (previously 8.1+)
- Laravel 11.28+ (previously 10.x)
- Filament v4.0+ (previously v3.2+)

### Upgrade Steps

1. **Update your composer.json:**

```json
{
    "require": {
        "php": "^8.2",
        "filament/filament": "^4.0",
        "tomatophp/filament-pwa": "^2.0"
    }
}
```

2. **Update dependencies:**

```bash
composer update
```

3. **Clear application caches:**

```bash
php artisan optimize:clear
```

4. **Republish assets (if you've customized views):**

```bash
php artisan vendor:publish --tag="filament-pwa-views" --force
```

### Breaking Changes

#### File Upload Visibility

All PWA icon and splash screen uploads now explicitly use `public` visibility. This is handled automatically by the package, but if you've extended the `PWASettingsPage`, ensure your custom file uploads also set `->visibility('public')`.

#### Settings Page API Changes

If you've extended the `PWASettingsPage` class:

- `getFormSchema()` has been replaced with `form(Form $form): Form`
- `getActions()` has been replaced with `getHeaderActions()`
- Action imports changed from `Filament\Pages\Actions\Action` to `Filament\Actions\Action`

**Before (v1.x):**
```php
protected function getFormSchema(): array
{
    return [/* fields */];
}

protected function getActions(): array
{
    return [Action::make('custom')];
}
```

**After (v2.x):**
```php
use Filament\Forms\Form;

public function form(Form $form): Form
{
    return $form->schema([/* fields */]);
}

protected function getHeaderActions(): array
{
    return [Action::make('custom')];
}
```

### No Code Changes Required

If you're just using the package without customizations, the upgrade is automatic. Simply update your dependencies and clear caches.
