<?php

namespace TomatoPHP\FilamentPWA\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\FilamentPWA\Settings\PWASettings;

class FilamentPwaInstall extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'filament-pwa:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install package and publish assets';

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Publish Vendor Assets');
        $this->callSilent('optimize:clear');

        $dbPath = File::files(database_path('migrations'));
        $exists = false;
        foreach ($dbPath as $path){
            if(str($path->getFilename())->contains('_pwa_settings.php')){
                $exists = true;
            }
        }
        //Register migrations
        if (!$exists) {
            $stubPath =  __DIR__ . '/../../database/migrations/pwa_settings.php.stub';
            $databasePath = database_path('migrations/' . date('Y_m_d_His', time()) . '_pwa_settings.php');

            File::copy($stubPath, $databasePath);
        }
        Artisan::call('migrate');
        File::copyDirectory(__DIR__ . '/../../resources/images', public_path('images'));

        $setting = new PWASettings();
        $jsPath = __DIR__ . '/../../resources/js/serviceworker.js';
        $getJsWorkerFile = File::exists($jsPath);
        if($getJsWorkerFile){
            $getJsWorkerFile = File::get($jsPath);
            $sizes = ['72x72', '96x96', '128x128', '144x144', '152x152', '192x192', '384x384', '512x512'];

            $icons = [];
            foreach ($sizes as $size) {
                $custom = $setting->{'pwa_icons_' . $size};

                $icons[] = $custom
                    ? '/storage/' . $custom
                    : '/images/icons/icon-' . $size . '.png';
            }

            $paths = array_merge(config('filament-pwa.precache', []), $icons);

            $value = str($getJsWorkerFile)
                ->replace('ICONS', collect($paths)->map(fn (string $path): string => '    "' . $path . '"')->implode(",\n"))
                ->toString();

            File::put(public_path('serviceworker.js'), $value);
        }

        $this->info('Filament PWA installed successfully.');
    }
}
