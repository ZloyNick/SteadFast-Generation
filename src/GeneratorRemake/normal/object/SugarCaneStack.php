<?php

/*
 *
 *    _______                                _
 *   |__   __|                              | |
 *      | | ___  ___ ___  ___ _ __ __ _  ___| |_
 *      | |/ _ \/ __/ __|/ _ \  __/ _` |/ __| __|
 *      | |  __/\__ \__ \  __/ | | (_| | (__| |_
 *      |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Tessetact Team
 * @link http://www.github.com/TesseractTeam/Tesseract
 * 
 *
 */


namespace GeneratorRemake\normal\object;

use GeneratorRemake\object\Object;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class SugarCaneStack extends Object
{

    /** @var Random */
    private $random;
    private $baseHeight = 2;
    private $randomHeight = 4;
    private $totalHeight;

    public function __construct(Random $random)
    {
        echo "Construct 1/2" . PHP_EOL
		$this->random = $random;
		$this->randomize();
		echo "Construct 2/2" . PHP_EOL
	}

    public function randomize()
    {
        echo "Randomize 1/2" . PHP_EOL
		$this->totalHeight = $this->baseHeight + $this->random->nextBoundedInt($this->randomHeight);
		echo "Randomize 2/2" . PHP_EOL
	}

    public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z): bool
    {
        echo "canPlaceObject 1/3" . PHP_EOL
		$below = $level->getBlockIdAt($x, $y - 1, $z);
		echo "canPlaceObject 2/3" . PHP_EOL
		if ($level->getBlockIdAt($x, $y, $z) == Block::AIR and
            ($below == Block::DIRT or $below == Block::GRASS or $below == Block::SAND) and (
                $this->isWater($level->getBlockIdAt($x - 1, $y - 1, $z)) or
                $this->isWater($level->getBlockIdAt($x + 1, $y - 1, $z)) or
                $this->isWater($level->getBlockIdAt($x, $y - 1, $z - 1)) or
                $this->isWater($level->getBlockIdAt($x, $y - 1, $z + 1))
            )
        ) {
            echo "canPlaceObject 3/3" . PHP_EOL
			return true;
		}
		echo "canPlaceObject 3/3" . PHP_EOL
		return false;
	}

    private function isWater(int $id): bool
    {
        echo "isWater 1/2" . PHP_EOL
		if ($id == Block::WATER or $id == Block::STILL_WATER) {
            echo "isWater 2/2" . PHP_EOL
			return true;
		}
		echo "isWater 2/2" . PHP_EOL
		return false;
	}

    public function placeObject(ChunkManager $level, int $x, int $y, int $z)
    {
        echo "placeObject 1/2" . PHP_EOL
		for ($yy = 0; $yy < $this->totalHeight; $yy++) {
            if ($level->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
                return;
            }
            $level->setBlockIdAt($x, $y + $yy, $z, Block::SUGARCANE_BLOCK);
        }
		echo "placeObject 2/2" . PHP_EOL
	}
}