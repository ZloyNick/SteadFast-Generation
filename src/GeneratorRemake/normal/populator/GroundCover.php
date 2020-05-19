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

namespace GeneratorRemake\normal\populator;

use GeneratorRemake\populator\Populator;
use GeneratorRemake\selector\Biome;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\FullChunk;
use pocketmine\utils\Random;

class GroundCover extends Populator
{

    public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        /** @var FullChunk $chunk */
        $chunk = $level->getChunk($chunkX, $chunkZ);
        $maxY = $level->getMaxY();
        for ($x = 0; $x < 16; ++$x) {
            for ($z = 0; $z < 16; ++$z) {
                $biome = Biome::getBiome($chunk->getBiomeId($x, $z));

                $cover = $biome->getGroundCover();
                if (count($cover) > 0) {
                    $diffY = 0;
                    if (!$cover[0]->isSolid()) {
                        $diffY = 1;
                    }

                    $column = $chunk->getBlockIdColumn($x, $z);
                    for ($y = $maxY - 1; $y > 0; --$y) {
                        if ($column{$y} !== "\x00" and !Block::get(ord($column{$y}))->isTransparent()) {
                            break;
                        }
                    }
                    $startY = min($level->getMaxY() - 1, $y + $diffY);
                    $endY = $startY - count($cover);
                    for ($y = $startY; $y > $endY and $y >= 0; --$y) {
                        $b = $cover[$startY - $y];
                        if ($column{$y} === "\x00" and $b->isSolid()) {
                            break;
                        }
                        if ($b->getDamage() === 0) {
                            $chunk->setBlockId($x, $y, $z, $b->getId());
                        } else {
                            $chunk->setBlock($x, $y, $z, $b->getId(), $b->getDamage());
                        }
                    }
                    if ($biome->haveCoverBlock()) {
                        if ($biome->canCoverBlockStay($startY + 1)) {
                            $chunk->setBlockId($x, $startY + 1, $z, $biome->getCoverBlock());
                            $chunk->setBlockData($x, $startY + 1, $z, $biome->getCoverBlockMeta());
                        }
                    }
                    $startY += 2;
                    if ($biome->haveMoreCover()) {
                        $moreCovers = $biome->getMoreCover();
                        foreach ($moreCovers as $id => $vls) {
                            if ($vls[0] < $startY && $vls[1] > $startY)
                                $chunk->setBlockId($x, $startY, $z, $id);
                        }
                    }
                }
            }
        }
    }
}