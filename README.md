# Seed Service Provider for Laravel Package

A Laravel service provider used for automatically register seeds in a package when call a seeding command in console. This work with all artisan seeding commands (e.g. db:seed, migrate:refresh --seed, ...)

## Install:

1. Download and copy SeedServiceProvider.php file in to your package project.
   Assume your package directory structure like this:

        MyPackage
        |-- src
            |-- Providers
                |-- MyPackageServiceProvider.php
                |-- SeedServiceProvider.php
                |-- ...
            |-- database
                |-- migrates
                    |-- ...
                |-- seeds
                    |-- FooSeeder.php
                    |-- BarSeeder.php
                    |-- ...
            |-- ...


2. With above directory structure, your path of seeds will like this:

        "/../database/seeds"
         
   You need to set this path in the SeedServiceProvider.php file:

        class SeedServiceProvider extends ServiceProvider
        {
            protected $seeds_path = '/../database/seeds';
            
            ...
        }

3. Register SeedServiceProvider class in the boot() method of your package sevice provider:

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


## Usage:
All seed files in your $seed_path will autoload with this command:

        php artisan db:seed

Or with '--seed' option:

        php artisan migrate:refresh --seed

## Note:
For favourable, the SeedServiceProvider will be ignored when execute "db:seed" command with "--class" option. This allow you specifies which one of seed you want to use insteads of all package's seeds could be automatically added also.
