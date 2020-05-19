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
use GeneratorRemake\normal\populator\WaterPit;
use GeneratorRemake\populator\Cactus;
use GeneratorRemake\populator\DeadBush;
use GeneratorRemake\populator\MesaTree;
use pocketmine\block\Block;

class MesaPlateauFBiome extends SandyBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();

        $pit = new WaterPit(0, 1);
        $this->addPopulator($pit);
        $cactus = new Cactus();
        $cactus->setBaseAmount(1);
        $cactus->setRandomAmount(5);
        $deadBush = new DeadBush();
        $cactus->setBaseAmount(4);
        $deadBush->setRandomAmount(10);
        $tree = new MesaTree();

        $this->addPopulator($cactus);
        $this->addPopulator($deadBush);
        $this->addPopulator($tree);

        $this->setElevation(63, 110);

        $this->temperature = 0.76;
        $this->rainfall = 2;
        $this->setGroundCover([
            Block::get(Block::HARDENED_CLAY, 0),
            Block::get(Block::STAINED_CLAY, 6),
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
            Block::get(Block::STAINED_CLAY, 6),
            Block::get(Block::STAINED_CLAY, 6),
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
        return $y > 90;
    }

    public function getCoverBlock(): int
    {
        return Block::GRASS;
    }

    public function getName(): string
    {
        return "Mesa Plateau F";
    }
}