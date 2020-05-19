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

namespace GeneratorRemake\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class Cactus extends Populator
{
    /** @var ChunkManager */
    private $level;
    private $randomAmount;
    private $baseAmount;

    public function setRandomAmount($amount)
    {
        $this->randomAmount = $amount;
    }

    public function setBaseAmount($amount)
    {
        $this->baseAmount = $amount;
    }

    public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        $this->level = $level;
        $amount = $random->nextRange(0, $this->randomAmount + 1) + $this->baseAmount;
        for ($i = 0; $i < $amount; ++$i) {
            $x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
            $z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
            $y = $this->getHighestWorkableBlock($x, $z);
            $tallRand = $random->nextRange(0, 17);
            $yMax = (int)($tallRand > 7) + (int)($tallRand > 15) + 1;

            if ($y !== -1) {
                if ($this->canCactusStay($x, $y, $z)) {
                    for ($yy = 0; $yy < $yMax; $yy++) {
                        $this->level->setBlockIdAt($x, $y + $yy, $z, Block::CACTUS);
                        $this->level->setBlockDataAt($x, $y + $yy, $z, 1);
                    }

                }
            }
        }
    }

    private function getHighestWorkableBlock($x, $z)
    {
        for ($y = 127; $y >= 0; --$y) {
            $b = $this->level->getBlockIdAt($x, $y, $z);
            if ($b !== Block::AIR and $b !== Block::LEAVES and $b !== Block::LEAVES2 and $b !== Block::SNOW_LAYER) {
                break;
            }
        }

        return $y === 0 ? -1 : ++$y;
    }

    private function canCactusStay($x, $y, $z)
    {
        $b = $this->level->getBlockIdAt($x, $y, $z);
        $below = $this->level->getBlockIdAt($x, $y - 1, $z);
        foreach (array($this->level->getBlockIdAt($x + 1, $y, $z), $this->level->getBlockIdAt($x - 1, $y, $z), $this->level->getBlockIdAt($x, $y, $z + 1), $this->level->getBlockIdAt($x, $y, $z - 1)) as $adjacent) {
            if ($adjacent !== Block::AIR) return false;
        }
        return ($b === Block::AIR) and ($below === Block::SAND or $below === Block::CACTUS);
    }
}