<?php
namespace Lab123\Odin\Command;

use Illuminate\Console\Command;

class AppStart extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Laravel App';

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
     * @return mixed
     */
    public function handle()
    {
        $this->validate();
        
        try {
            $this->info('Starting App...');
            
            $this->composer_install();
            $this->vendor_publish();
            $this->migrate();
            $this->seed();
        } catch (\Exception $ex) {
            // $this->migrate_reset();
            $this->error($ex);
        }
    }

    private function validate()
    {
        if (! env('DB_HOST')) {
            $this->error('Verify .env to connect database');
            exit();
        }
    }

    private function composer_install()
    {
        $this->line('> composer install');
        exec('composer install');
    }

    private function vendor_publish()
    {
        $this->line('> php artisan vendor:publish');
        exec('php artisan vendor:publish');
    }

    private function migrate()
    {
        $this->line('> php artisan migrate');
        exec('php artisan migrate');
    }

    private function migrate_reset()
    {
        $this->line('> php artisan migrate:refresh --seed');
        exec('php artisan migrate:refresh --seed');
    }

    private function seed()
    {
        $this->line('> php artisan db:seed');
        exec('php artisan db:seed');
    }
}