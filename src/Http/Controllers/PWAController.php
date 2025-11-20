<?php

namespace TomatoPHP\FilamentPWA\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use TomatoPHP\FilamentPWA\Services\ManifestService;

class PWAController extends Controller
{
    public function index()
    {
        return response()->json(ManifestService::generate());
    }

    public function offline()
    {
        return view('filament-pwa::offline');
    }

    public function serviceWorker()
    {
        $jsPath = __DIR__ . '/../../../resources/js/serviceworker.js';
        $content = File::get($jsPath);

        $icons = config('filament-pwa.icons', []);
        $iconsList = collect($icons)->map(function ($icon) {
            return "'{$icon['src']}'";
        })->implode(",\n    ");

        $content = str_replace('ICONS', $iconsList, $content);

        return response($content)
            ->header('Content-Type', 'application/javascript');
    }
}
