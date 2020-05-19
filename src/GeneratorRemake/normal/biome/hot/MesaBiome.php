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

namespace GeneratorRemake\normal\biome\hot;

use GeneratorRemake\block\StainedClayMetas as StainedClay;
use GeneratorRemake\normal\biome\Mountainable;
use GeneratorRemake\normal\biome\SandyBiome;
use GeneratorRemake\populator\Cactus;
use GeneratorRemake\populator\DeadBush;
use pocketmine\block\Block;

class MesaBiome extends SandyBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();

        $cactus = new Cactus();
        $cactus->setBaseAmount(0);
        $cactus->setRandomAmount(5);
        $deadBush = new DeadBush();
        $cactus->setBaseAmount(2);
        $deadBush->setRandomAmount(10);

        $this->addPopulator($cactus);
        $this->addPopulator($deadBush);

        $this->setElevation(51, 61);
        $this->setGroundCover([
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_PINK),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_ORANGE),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_BLACK),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_GRAY),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_WHITE),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_ORANGE),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_YELLOW),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_BLACK),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_PINK),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_PINK),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::STAINED_CLAY, StainedClay::CLAY_WHITE),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::RED_SANDSTONE, 0),
            Block::get(Block::RED_SANDSTONE, 0),
        ]);
    }

    public function haveCoverBlock(): bool
    {
        return true;
    }

    public function canCoverBlockStay(int $y): bool
    {
        return $y < 80;
    }

    public function getCoverBlockMeta(): int
    {
        return 1;
    }

    public function getCoverBlock(): int
    {
        return Block::SAND;
    }

    public function getName(): string
    {
        return "Mesa Bryce";
    }
} 