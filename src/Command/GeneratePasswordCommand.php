<?php
namespace Lab123\Odin\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\InputArgument;

class GeneratePasswordCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'password:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a password hashed';

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
        $passwordHashed = Hash::make($this->argument('password'));
        
        $this->info($passwordHashed);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [
                'password',
                InputArgument::REQUIRED,
                'The password to hashed.'
            ]
        ];
    }
}