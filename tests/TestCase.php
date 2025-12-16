<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use TomatoPHP\FilamentPWA\FilamentPwaServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            \Spatie\LaravelSettings\LaravelSettingsServiceProvider::class,
            FilamentPwaServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        config()->set('filesystems.default', 'local');

        config()->set('settings.default_repository', 'database');
        config()->set('settings.repositories', [
            'database' => [
                'type' => \Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository::class,
                'connection' => null,
                'table' => 'settings',
            ],
        ]);
    }

    protected function setUpDatabase(): void
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('group');
            $table->string('name');
            $table->boolean('locked')->default(false);
            $table->json('payload');
            $table->timestamps();
        });

        $this->seedPWASettings();
    }

    protected function seedPWASettings(): void
    {
        $settings = [
            'pwa_app_name' => 'TomatoPHP',
            'pwa_short_name' => 'Tomato',
            'pwa_start_url' => '/',
            'pwa_background_color' => '#ffffff',
            'pwa_theme_color' => '#000000',
            'pwa_display' => 'standalone',
            'pwa_orientation' => 'any',
            'pwa_status_bar' => '#000000',
            'pwa_icons_72x72' => '',
            'pwa_icons_96x96' => '',
            'pwa_icons_128x128' => '',
            'pwa_icons_144x144' => '',
            'pwa_icons_152x152' => '',
            'pwa_icons_192x192' => '',
            'pwa_icons_384x384' => '',
            'pwa_icons_512x512' => '',
            'pwa_splash_640x1136' => '',
            'pwa_splash_750x1334' => '',
            'pwa_splash_828x1792' => '',
            'pwa_splash_1125x2436' => '',
            'pwa_splash_1242x2208' => '',
            'pwa_splash_1242x2688' => '',
            'pwa_splash_1536x2048' => '',
            'pwa_splash_1668x2224' => '',
            'pwa_splash_1668x2388' => '',
            'pwa_splash_2048x2732' => '',
            'pwa_shortcuts' => [],
        ];

        foreach ($settings as $name => $value) {
            DB::table('settings')->insert([
                'group' => 'pwa',
                'name' => $name,
                'locked' => false,
                'payload' => json_encode($value),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
