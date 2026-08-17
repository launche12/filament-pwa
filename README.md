![Screenshot](https://raw.githubusercontent.com//tomatophp/filament-pwa/master/arts/3x1io-tomato-pwa.jpg)

# Filament PWA

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-pwa/version.svg)](https://packagist.org/packages/tomatophp/filament-pwa)
[![License](https://poser.pugx.org/tomatophp/filament-pwa/license.svg)](https://packagist.org/packages/tomatophp/filament-pwa)
[![Downloads](https://poser.pugx.org/tomatophp/filament-pwa/d/total.svg)](https://packagist.org/packages/tomatophp/filament-pwa)

get a PWA feature on your FilamentPHP app with settings from panel

## Installation

```bash
composer require tomatophp/filament-pwa
```

now you need to publish and migrate settings table

```bash
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
php artisan filament-settings-hub:install 
```

after install your package please run this command

```bash
php artisan filament-pwa:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentPWA\FilamentPWAPlugin::make())
```

## Screenshots

![Install](https://raw.githubusercontent.com/tomatophp/filament-pwa/master/arts/install.png)
![App](https://raw.githubusercontent.com/tomatophp/filament-pwa/master/arts/app.png)
![Setting Hub](https://raw.githubusercontent.com/tomatophp/filament-pwa/master/arts/setting-hub.png)
![Setting Page](https://raw.githubusercontent.com/tomatophp/filament-pwa/master/arts/setting-page.png)


## Use Directive

you can use directive to allow PWA on none-FilamentPHP pages, just add this directive to your blade file on top of `</head>`

```html
@filamentPWA
```

## Configuration

Publish the config first:

```bash
php artisan vendor:publish --tag="filament-pwa-config"
```

### Panel navigation

The settings page has no sidebar entry of its own — it is reached through the Settings Hub. To
list it in the menu:

```php
"navigation" => [
    "register" => true,
    "group" => "Admin",
    "label" => "PWA Settings",
],
```

`group` and `label` take a literal string or a translation key; values containing `.` or `::` go
through `trans()`. `null` keeps the package default. `label` also sets the page title, but not the
card inside the Settings Hub — that one follows the package translations.

### Page width

```php
"max_content_width" => "screen-2xl",
```

`null` follows the panel (Filament defaults to `7xl`, centered). Any value of
`Filament\Support\Enums\Width` works; `full` goes edge to edge.

### Precache

Extra paths added to the service worker install cache, on top of the PWA icons:

```php
"precache" => ["/css/app.css"],
```

> [!WARNING]
> `cache.addAll()` is atomic — a single URL that responds 404 fails the whole service worker
> install. List only what actually exists.

## Service worker

`filament-pwa:install` writes `public/serviceworker.js`. Files in `public/` are served by the web
server before the request reaches Laravel, so that file **shadows** the `pwa.serviceworker` route.
Both produce the same content, but the static file freezes the icon list as it was at install
time — re-run the installer after changing icons, or delete `public/serviceworker.js` and let the
route answer.

The same shadowing applies to `public/manifest.json`. If the project previously used another PWA
setup (PWABuilder, laravel-pwa), that leftover file answers instead of the package route and
nothing configured in the settings page shows up. Check with:

```bash
php artisan route:list --name=pwa
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-pwa-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-pwa-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-pwa-lang"
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)

