<?php

declare(strict_types=1);

namespace TaskService\Tests\Unit\Controllers;

use Override;
use PHPUnit\Framework\TestCase;
use TaskService\Controllers\MigrationsController;
use TaskService\Tests\Unit\Framework\AppMock;

final class MigrationsControllerTest extends TestCase
{
    private AppMock $appMock;

    #[Override]
    protected function setUp(): void
    {
        $this->appMock = new AppMock($this->createMock(...), [], []);
    }

    public function testUpdateDatabaseMySql(): void
    {
        $this->appMock->getMigrationsRepository()->expects($this->once())
            ->method('processMigrationsMySql')
            ->with(__DIR__ . '/../../../src/Migrations/mysql/')
            ->willReturn((static function (): iterable {
                yield 'Processing mysql/2020-05-21_2000_add_migration_table.sql';
            })());

        $migrationsController = new MigrationsController($this->appMock);
        $actual = $migrationsController->updateDatabaseMySql(__DIR__ . '/../../../src/Migrations/mysql/');

        $this->assertSame(['Processing mysql/2020-05-21_2000_add_migration_table.sql'], [...$actual]);
    }

    public function testUpdateDatabaseMySqlAllDone(): void
    {
        $this->appMock->getMigrationsRepository()->expects($this->once())
            ->method('processMigrationsMySql')
            ->with(__DIR__ . '/../../../src/Migrations/mysql/')
            ->willReturn([]);

        $migrationsController = new MigrationsController($this->appMock);
        $actual = $migrationsController->updateDatabaseMySql(__DIR__ . '/../../../src/Migrations/mysql/');

        $this->assertSame([], [...$actual]);
    }

    public function testUpdateDatabaseClickHouse(): void
    {
        $this->appMock->getMigrationsRepository()->expects($this->once())
            ->method('processMigrationsClickHouse')
            ->with(__DIR__ . '/../../../src/Migrations/clickhouse/')
            ->willReturn((static function (): iterable {
                yield 'Processing clickhouse/2020-05-21_2000_add_migration_table.sql';
            })());

        $migrationsController = new MigrationsController($this->appMock);
        $actual = $migrationsController->updateDatabaseClickHouse(__DIR__ . '/../../../src/Migrations/clickhouse/');

        $this->assertSame(['Processing clickhouse/2020-05-21_2000_add_migration_table.sql'], [...$actual]);
    }

    public function testUpdateDatabaseClickHouseAllDone(): void
    {
        $this->appMock->getMigrationsRepository()->expects($this->once())
            ->method('processMigrationsClickHouse')
            ->with(__DIR__ . '/../../../src/Migrations/clickhouse/')
            ->willReturn([]);

        $migrationsController = new MigrationsController($this->appMock);
        $actual = $migrationsController->updateDatabaseClickHouse(__DIR__ . '/../../../src/Migrations/clickhouse/');

        $this->assertSame([], [...$actual]);
    }
}
