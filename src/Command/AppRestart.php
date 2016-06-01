<?php
namespace Lab123\Odin\Command;

use Illuminate\Console\Command;

class AppRestart extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart Laravel App';

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
            
            $this->info('Restarting App...');
            
            $this->composer_dumpautoload();
            $this->composer_update();
            $this->migrate_reset();
        } catch (\Exception $ex) {
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

    private function composer_update()
    {
        $this->line('> composer update');
        exec('composer update');
    }

    private function composer_dumpautoload()
    {
        $this->line('> composer dumpautoload');
        exec('composer dumpautoload');
    }

    private function migrate_reset()
    {
        $this->line('> php artisan migrate:refresh --seed');
        exec('php artisan migrate:refresh --seed');
    }
}
