<?php
declare(strict_types=1);

namespace ScriptFUSION\Steam250;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

final class DatabaseFactory
{
    public function create(): Connection
    {
        $connection = DriverManager::getConnection(['url' => 'sqlite:///db/games.sqlite']);

        $connection->exec(
            'CREATE TABLE IF NOT EXISTS review (
                id INTEGER PRIMARY KEY NOT NULL,
                app_name TEXT NOT NULL,
                total_reviews INTEGER NOT NULL,
                positive_reviews INTEGER NOT NULL,
                negative_reviews INTEGER NOT NULL,
                app_type TEXT,
                release_date INTEGER
            );'
        );

        return $connection;
    }
}