<?php

namespace TaskService\Repositories;

use Exception;
use TaskService\Framework\App;

class MigrationsRepository
{
    public function __construct(private App $app) {}

    /**
     * @return iterable<int, string>
     */
    public function processMigrationsMySql(string $path): iterable
    {
        foreach (scandir($path) ?: [] as $file) {
            if (str_ends_with($file, '.sql') && !$this->isMySqlImported($file)) {
                yield 'Processing mysql/' . $file;

                $this->importMySql($path . $file);
            }
        }
    }

    public function importMySql(string $file): void
    {
        if (!is_readable($file)) {
            throw new Exception('invalid file');
        }

        $database = $this->app->getDatabase();

        $database->exec(file_get_contents($file) ?: '');

        $query = 'INSERT INTO migration SET filename = ?, created_at = now()';
        $database->prepare($query)->execute([basename($file)]);
    }

    public function isMySqlImported(string $filename): bool
    {
        $database = $this->app->getDatabase();

        $query = "SHOW tables LIKE 'migration'";
        if ($database->query($query)->fetchColumn(0) === false) {
            return false;
        }

        $query = 'SELECT filename FROM migration WHERE filename = ?';
        $statement = $database->prepare($query);
        $statement->execute([$filename]);

        return (bool) $statement->rowCount();
    }

    /**
     * @return iterable<int, string>
     */
    public function processMigrationsClickHouse(string $path): iterable
    {
        foreach (scandir($path) ?: [] as $file) {
            if (str_ends_with($file, '.sql') && !$this->isClickHouseImported($file)) {
                yield 'Processing clickhouse/' . $file;

                $this->importClickHouse($path . $file);
            }
        }
    }

    public function importClickHouse(string $file): void
    {
        if (!is_readable($file)) {
            throw new Exception('invalid file');
        }

        $database = $this->app->getClickHouse();

        $database->exec(file_get_contents($file) ?: '');

        $query = 'INSERT INTO migration (filename, created_at) VALUES (?, now())';
        $statement = $database->prepare($query);

        $statement->execute([basename($file)]);
    }

    public function isClickHouseImported(string $filename): bool
    {
        $database = $this->app->getClickHouse();

        $query = "SHOW tables LIKE 'migration'";
        if ($database->query($query)->fetchColumn(0) === false) {
            return false;
        }

        $query = 'SELECT filename FROM migration WHERE filename = ?';
        $statement = $database->prepare($query);
        $statement->execute([$filename]);

        return (bool) $statement->rowCount();
    }
}
