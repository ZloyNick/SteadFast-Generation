<?php
/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace GeneratorRemake\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class Mushroom extends VariableAmountPopulator
{
    /** @var ChunkManager */
    private $level;

    public function __construct($odd = 8)
    {
        parent::__construct(3, 6, $odd);
    }

    public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        if (!$this->checkOdd($random)) {
            return;
        }
        $this->level = $level;
        $amount = $this->getAmount($random);
        for ($i = 0; $i < $amount; ++$i) {
            $x = $chunkX * 16;
            $z = $chunkZ * 16;
            $xx = $x - 7 + $random->nextRange(0, 15);
            $zz = $z - 7 + $random->nextRange(0, 15);
            $yy = $this->getHighestWorkableBlock($xx, $zz);
            if ($yy !== -1 and $this->canMushroomStay($xx, $yy, $zz)) {
                $this->level->setBlockIdAt($xx, $yy, $zz, (($random->nextRange(0, 4)) == 0 ? Block::RED_MUSHROOM : Block::BROWN_MUSHROOM));
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

    private function canMushroomStay($x, $y, $z)
    {
        $c = $this->level->getBlockIdAt($x, $y, $z);
        $b = $this->level->getBlockIdAt($x, $y - 1, $z);
        return ($c === Block::AIR || $c === Block::SNOW_LAYER) && ($b === Block::MYCELIUM or $b === Block::GRASS or $b === Block::PODZOL);
    }
}
