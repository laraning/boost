<?php

namespace Laraning\Boost\Traits;

/**
 * Trait that can be added to the Service Provider in order to dynamically publish the
 * migration files that aren't yet published.
 *
 * REQUIREMENTS:
 *
 * On your Service Provider, please add:
 * use Migrateable;
 * protected $migrationPath = __DIR__ . '/../database/migrations/';
 * protected $migrations = ['laraning', 'version_0_1_0']; // No need to put "*"!
 *
 * On your boot() please add the line:
 * $this->publishMigrations();
 *
 * RETURNS:
 * Creates a tag called <app.name>-schema-updates on your Service Provider tags
 * with only the migration files missing!
 * If there aren't new migration files, then it don't generate a tag.
 */
trait Migrateable
{
    protected $migrationFiles = [];

    protected function loadMigrationFiles($rootPath, $wildcard)
    {
        $wildcard = (array) $wildcard;

        foreach ($wildcard as $card) {
            $imported = glob(base_path("database/migrations/*{$card}*.php"));
            $pending = glob(path_separators($rootPath . "/*{$card}*"));

            if (count($imported) == 0) {
                // Add migration files with that wildcard.
                foreach ($pending as $path) {
                    $slices = explode('\\', $path);
                    end($slices);
                    $filename = $slices[key($slices)];
                    $file = explode('.', $filename)[0];
                    $timestamp = date('Y_m_d_His', time());
                    $this->migrationFiles[$path] = $this->app->databasePath()."/migrations/{$timestamp}_{$file}.php";
                };
            };
        };
    }

    protected function publishMigrations()
    {
        $this->loadMigrationFiles($this->migrationPath, $this->migrations);
        if (count($this->migrationFiles) > 0) {
            $this->publishes($this->migrationFiles, kebab_case(config('app.name') . '-schema-updates'));
        };
    }
}
