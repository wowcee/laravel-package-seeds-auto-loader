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
                    |-- MyTableSeeder.php
                    |-- ...
            |-- ...


2. Specifies a path to seeds folder in SeedServiceProvider.php

        class SeedServiceProvider extends ServiceProvider
        {
            private $seeds_path = '/../database/seeds';
            
            ...
        }

3. Always using in boot() method of the main package sevice provider:

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

        php artisan db:seed

Or:

        php artisan migrate:refresh --seed
