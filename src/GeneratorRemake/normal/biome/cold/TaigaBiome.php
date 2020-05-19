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

namespace GeneratorRemake\normal\biome\cold;

use GeneratorRemake\normal\biome\GrassyBiome;
use GeneratorRemake\normal\populator\Flower;
use GeneratorRemake\populator\MossStone;
use GeneratorRemake\populator\TallGrass;
use GeneratorRemake\populator\Tree;
use pocketmine\block\Block;
use pocketmine\block\Sapling;

class TaigaBiome extends GrassyBiome
{

    public function __construct()
    {
        parent::__construct();

        $mossStone = new MossStone();
        $mossStone->setBaseAmount(0);
        $mossStone->setRandomAmount(1);
        $this->addPopulator($mossStone);

        $trees = new Tree(Sapling::SPRUCE);
        $trees->setBaseAmount(5);
        $trees->setRandomAmount(10);
        $this->addPopulator($trees);

        $this->setElevation(67, 78);

        $this->setGroundCover([
            Block::get(Block::GRASS, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0)
        ]);

        $flowers = new Flower();
        $flowers->addType([38, 0]);
        $flowers->addType([37, 0]);
        $flowers->setBaseAmount(2);
        $flowers->setRandomAmount(2);
        $this->addPopulator($flowers);

        $grass = new TallGrass();
        $grass->setBaseAmount(5);
        $grass->setRandomAmount(6);
        $grass->setMeta(2);
        $this->addPopulator($grass);
    }

    public function getName(): string
    {
        return "Taiga";
    }
}
