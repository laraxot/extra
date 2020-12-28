<?php

namespace Laraxot\Extra\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Client as GuzzleClient;

class ModuleInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modulex:install {vendor_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the specified module by given package name (vendor/name).';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $vendor_name=$this->argument('vendor_name');
        $version='dev-master';
        $json_url='https://repo.packagist.org/p/'.$vendor_name.'.json';
        $content=file_get_contents($json_url);
        $json=json_decode($content);
        //echo '<pre>'.print_r($json,1).'</pre>';
        //return ;
        $zip_url=$json->packages->{$vendor_name}->{$version}->dist->url;
        //ddd(pathinfo($zip_url));
        $client=new GuzzleClient();
        $zip_path=Storage::disk('local')->path('tmp.zip');
        $client->request('GET', $zip_url, ['sink'=>$zip_path]);
        $zip = new \ZipArchive;
        $res = $zip->open($zip_path);
        if ($res === true) {
            print_r($zip->extractTo(base_path('Modules')));
            $zip->close();
            echo 'SI';
        }else{
            echo 'NO';
        }
        $old_dir=str_replace('/', '-', $vendor_name).'-'.substr(basename($zip_url), 0, 7);
        $module_json=File::get(base_path('Modules/'.$old_dir.'/module.json'));
        $module_json=json_decode($module_json);
        $new_dir=$module_json->name;
        rename(base_path('Modules/'.$old_dir),base_path('Modules/'.$new_dir));
    }
}
