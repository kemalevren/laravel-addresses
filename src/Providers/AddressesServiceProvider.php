<?php

declare(strict_types=1);

namespace kemalevren\Addresses\Providers;

use kemalevren\Addresses\Models\Address;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use kemalevren\Addresses\Console\Commands\MigrateCommand;
use kemalevren\Addresses\Console\Commands\PublishCommand;
use kemalevren\Addresses\Console\Commands\RollbackCommand;

class AddressesServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.addresses.migrate',
        PublishCommand::class => 'command.rinvex.addresses.publish',
        RollbackCommand::class => 'command.rinvex.addresses.rollback',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.addresses');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'rinvex.addresses.address' => Address::class,
        ]);

        // Register console commands
        //$this->registerCommands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish Resources
        $this->publishesConfig('rinvex/laravel-addresses');
        $this->publishesMigrations('rinvex/laravel-addresses');
        ! $this->autoloadMigrations('rinvex/laravel-addresses') || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
