<?php

use Filament\Panel;
use TomatoPHP\FilamentPWA\FilamentPWAPlugin;

test('plugin can be registered on panel', function () {
    $panel = Panel::make()->id('admin');

    $plugin = FilamentPWAPlugin::make();

    expect($plugin->getId())->toBe('filament-pwa');

    $plugin->register($panel);

    expect($panel->getPages())->toContain(\TomatoPHP\FilamentPWA\Filament\Pages\PWASettingsPage::class);
});

test('plugin can disable PWA settings', function () {
    $panel = Panel::make()->id('admin');

    $plugin = FilamentPWAPlugin::make()
        ->allowPWASettings(false);

    $plugin->register($panel);

    expect($panel->getPages())->not->toContain(\TomatoPHP\FilamentPWA\Filament\Pages\PWASettingsPage::class);
});
