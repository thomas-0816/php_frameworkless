<?php

namespace TaskService\Controllers;

use TaskService\Framework\App;

class MigrationsController
{
    public function __construct(private App $app) {}

    /**
     * @return iterable<int, string>
     */
    public function updateDatabaseMySql(string $path): iterable
    {
        return $this->app->getMigrationsRepository()->processMigrationsMySql($path);
    }

    /**
     * @return iterable<int, string>
     */
    public function updateDatabaseClickHouse(string $path): iterable
    {
        return $this->app->getMigrationsRepository()->processMigrationsClickHouse($path);
    }
}
