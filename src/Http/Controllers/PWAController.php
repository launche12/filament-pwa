<?php

namespace TomatoPHP\FilamentPWA\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use TomatoPHP\FilamentPWA\Services\ManifestService;

class PWAController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ManifestService::generate());
    }

    public function offline(): View
    {
        return view('filament-pwa::offline');
    }

    public function serviceWorker(): Response
    {
        $jsPath = __DIR__ . '/../../../resources/js/serviceworker.js';

        if (! File::exists($jsPath)) {
            abort(404, 'Service worker file not found');
        }

        $content = File::get($jsPath);

        $manifest = ManifestService::generate();
        $paths = array_merge(
            config('filament-pwa.precache', []),
            collect($manifest['icons'])->pluck('src')->all(),
        );

        $iconsList = collect($paths)->map(fn (string $path): string => '    "' . $path . '"')->implode(",\n");

        $content = str_replace('ICONS', $iconsList, $content);

        return response($content)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
