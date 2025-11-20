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
