<?php

namespace Core\Providers;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;

class SeedServiceProvider extends ServiceProvider
{
    protected $seeds_path = '/../database/seeds';


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if ($this->isConsoleCommandContains([ 'db:seed', '--seed' ], [ '--class', 'help', '-h' ])) {
                $this->addSeedsAfterConsoleCommandFinished();
            }
        }
    }

    /**
     * Get a value that indicates whether the current command in console
     * contains a string in the specified $fields.
     *
     * @param string|array $contain_options
     * @param string|array $exclude_options
     *
     * @return bool
     */
    protected function isConsoleCommandContains($contain_options, $exclude_options = null) : bool
    {
        $args = Request::server('argv', null);
        if (is_array($args)) {
            $command = implode(' ', $args);
            if (str_contains($command, $contain_options) && ($exclude_options == null || !str_contains($command, $exclude_options))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add seeds from the $seed_path after the current command in console finished.
     */
    protected function addSeedsAfterConsoleCommandFinished()
    {
        Event::listen(CommandFinished::class, function(CommandFinished $event) {
            // Accept command in console only,
            // exclude all commands from Artisan::call() method.
            if ($event->output instanceof ConsoleOutput) {
                $this->addSeedsFrom(__DIR__ . $this->seeds_path);
            }
        });
    }

    /**
     * Register seeds.
     *
     * @param  string  $seeds_path
     * @return void
     */
    protected function addSeedsFrom($seeds_path)
    {
        $file_names = glob("$seeds_path/*.php");
        foreach ($file_names as $filename)
        {
            $php_code = file_get_contents($filename);

            // Get name of all class has in the file.
            $classes = array();
            $tokens = token_get_all($php_code);
            $count = count($tokens);
            for ($i = 2; $i < $count; $i++) {
                if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                    $class_name = $tokens[$i][1];
                    $classes[] = $class_name;
                }
            }

            foreach ($classes as $class) {
                Artisan::call('db:seed', [ '--class' => "Core\\database\\seeds\\$class", '--force' => true ]);
            }
        }
    }
}
