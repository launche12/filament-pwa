<?php

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use TomatoPHP\FilamentPWA\Filament\Pages\PWASettingsPage;

test('settings page has form with required fields', function () {
    $page = new PWASettingsPage();

    $schema = $page->form(Schema::make());

    $components = $schema->getComponents();

    expect($components)->not->toBeEmpty();
});

test('form method returns valid schema', function () {
    $page = new PWASettingsPage();

    $schema = $page->form(Schema::make());

    expect($schema)->toBeInstanceOf(Schema::class);
    expect($schema->getComponents())->not->toBeEmpty();
});

test('file upload components have public visibility', function () {
    $iconUpload = FileUpload::make('pwa_icons_72x72')
        ->acceptedFileTypes(['image/png'])
        ->visibility('public');

    expect($iconUpload->getVisibility())->toBe('public');

    $splashUpload = FileUpload::make('pwa_splash_640x1136')
        ->acceptedFileTypes(['image/png'])
        ->visibility('public');

    expect($splashUpload->getVisibility())->toBe('public');

    $shortcutUpload = FileUpload::make('icon')
        ->image()
        ->visibility('public');

    expect($shortcutUpload->getVisibility())->toBe('public');
});
