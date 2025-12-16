<?php

use TomatoPHP\FilamentPWA\Services\ManifestService;

test('manifest service generates valid JSON structure', function () {
    $manifest = ManifestService::generate();

    expect($manifest)->toBeArray()
        ->and($manifest)->toHaveKeys(['name', 'short_name', 'start_url', 'display', 'theme_color', 'background_color', 'icons']);
});

test('manifest includes all icon sizes', function () {
    $manifest = ManifestService::generate();

    $iconSizes = collect($manifest['icons'])->pluck('sizes')->toArray();

    expect($iconSizes)->toContain('72x72', '96x96', '128x128', '144x144', '152x152', '192x192', '384x384', '512x512');
});

test('manifest uses fallback values when settings are empty', function () {
    $manifest = ManifestService::generate();

    expect($manifest['name'])->not->toBeEmpty()
        ->and($manifest['short_name'])->not->toBeEmpty()
        ->and($manifest['start_url'])->not->toBeEmpty();
});
