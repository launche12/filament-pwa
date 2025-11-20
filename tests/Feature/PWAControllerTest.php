<?php

test('manifest endpoint returns valid JSON', function () {
    $response = $this->get('/manifest.json');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonStructure([
            'name',
            'short_name',
            'start_url',
            'display',
            'theme_color',
            'background_color',
            'icons',
        ]);
});

test('service worker endpoint returns JavaScript', function () {
    $response = $this->get('/serviceworker.js');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/javascript');
});
