<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace GeneratorRemake;

use pocketmine\level\format\FullChunk;
use pocketmine\level\generator\Generator;
use pocketmine\level\Level;
use pocketmine\level\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class AutoGenerator extends AsyncTask
{

    public $levelId;
    public $chunkClass;
    public $chunks = [];

    public function __construct(Level $level, FullChunk $chunk)
    {
        $this->levelId = $level->getId();
        $this->chunkClass = get_class($chunk);
    }

    public function add(FullChunk $chunk)
    {
        $this->chunks[count($this->chunks)] = $chunk->toFastBinary();
    }

    public function onRun()
    {
        /** @var SimpleChunkManager $manager */
        $manager = $this->getFromThreadStore("generation.level{$this->levelId}.manager");
        /** @var Generator $generator */
        $generator = $this->getFromThreadStore("generation.level{$this->levelId}.generator");
        /** @var FullChunk $chunkC */
        $chunkC = $this->chunkClass;
        /** @var FullChunk $chunk */
        for ($i = 0; $i < count($this->chunks); $i++) {
            /** @var FullChunk $chunk */
            $chunk = $chunkC::fromFastBinary($this->chunks[$i]);
            $manager->setChunk($chunk->getX(), $chunk->getZ(), $chunk);
            $generator->generateChunk($chunk->getX(), $chunk->getZ());
            $chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
            $chunk->setGenerated();
            $this->chunks[$i] = $chunk->toFastBinary();
            echo "Generated: {$i}/" . count($this->chunks) . PHP_EOL;
        }
    }

    public function onCompletion(Server $server)
    {
        $level = $server->getLevel($this->levelId);
        $level->registerGenerator();
        /** @var FullChunk $chunkC */
        $chunkC = $this->chunkClass;
        foreach ($this->chunks as $i => $chunk) {
            /** @var FullChunk $chunk */
            $chunk = $chunkC::fromFastBinary($chunk);
            $level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
        }
    }
}