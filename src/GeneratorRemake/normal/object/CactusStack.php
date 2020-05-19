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

class CactusStack extends Object
{
    /** @var Random */
    private $random;
    private $baseHeight = 1;
    private $randomHeight = 3;
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
            ($below == Block::SAND or $below == Block::CACTUS) and (
                $level->getBlockIdAt($x - 1, $y - 1, $z) == Block::AIR and
                $level->getBlockIdAt($x + 1, $y - 1, $z) == Block::AIR and
                $level->getBlockIdAt($x, $y - 1, $z - 1) == Block::AIR and
                $level->getBlockIdAt($x, $y - 1, $z + 1) == Block::AIR
            )
        ) {
            echo "canPlaceObject 3/3" . PHP_EOL
			return true;
		}
		echo "canPlaceObject 3/3" . PHP_EOL
		return false;
	}

    public function placeObject(ChunkManager $level, int $x, int $y, int $z)
    {
        echo "canPlaceObject 1/2" . PHP_EOL
		for ($yy = 0; $yy < $this->totalHeight; $yy++) {
            if ($level->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
                return;
            }
            $level->setBlockIdAt($x, $y + $yy, $z, Block::CACTUS);
        }
		echo "canPlaceObject 2/2" . PHP_EOL
	}
}