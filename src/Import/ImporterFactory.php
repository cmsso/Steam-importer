<?php
declare(strict_types=1);

namespace ScriptFUSION\Steam250\Import;

use ScriptFUSION\Steam250\Database\DatabaseFactory;
use ScriptFUSION\Steam250\LoggerFactory;
use ScriptFUSION\Steam250\PorterFactory;

final class ImporterFactory
{
    public function create(string $appListPath, int $chunks, int $chunkIndex, bool $verbose): Importer
    {
        $extension = 'sqlite';
        $chunks && $extension .= ".p$chunkIndex";

        $importer = new Importer(
            (new PorterFactory)->create(),
            (new DatabaseFactory)->create("steam.$extension"),
            (new LoggerFactory)->create('Import', $verbose),
            $appListPath
        );
        $importer->setChunks($chunks);
        $importer->setChunkIndex($chunkIndex);

        return $importer;
    }
}
