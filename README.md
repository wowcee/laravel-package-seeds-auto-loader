# Seed Service Provider for Laravel Package

A Laravel service provider used for automatically register seeds in a package when call a seeding command in console (e.g. db:seed, migrate:refresh --seed, ...)

## Usage:

Always using in boot() method of the main sevice provider of package:


    class MyPackageServiceProvider extends ServiceProvider
    {
        /**
         * Bootstrap services.
         *
         * @return void
         */
        public function boot()
        {
            /*
            |--------------------------------------------------------------------------
            | Seed Service Provider need on boot() method
            |--------------------------------------------------------------------------
            */
            $this->app->register(SeedServiceProvider::class);
        }

        /**
         * Register services.
         *
         * @return void
         */
        public function register()
        {
            //
        }
    }


Work with all seeding commands:

        php artisan db:seed

Or:

        php artisan migrate:refresh --seed
