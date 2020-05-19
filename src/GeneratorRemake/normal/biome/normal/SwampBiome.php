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

namespace GeneratorRemake\normal\biome\normal;

use GeneratorRemake\normal\biome\GrassyBiome;
use GeneratorRemake\populator\BigMushroom;
use GeneratorRemake\populator\Flower;
use GeneratorRemake\populator\LilyPad;
use GeneratorRemake\populator\Mushroom;
use GeneratorRemake\populator\SugarCane;
use GeneratorRemake\populator\TallGrass;
use GeneratorRemake\populator\tree\SwampTreePopulator;
use pocketmine\block\Block;
use pocketmine\block\Flower as FlowerBlock;

class SwampBiome extends GrassyBiome
{

    public function __construct()
    {
        parent::__construct();

        $flower = new Flower();
        $flower->setBaseAmount(8);
        $flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_BLUE_ORCHID]);

        $lilyPad = new LilyPad();
        $lilyPad->setBaseAmount(4);
        $lilyPad->setRandomAmount(10);

        $tallGrass = new TallGrass();
        $tallGrass->setBaseAmount(1);

        $mushroom = new Mushroom();
        $mushroom->setBaseAmount(0);
        $mushroom->setRandomAmount(3);

        $sugarCane = new SugarCane();

        $bigMushroom = new BigMushroom(12);

        $sugarCane->setBaseAmount(2);
        $sugarCane->setRandomAmount(15);

        $tree = new SwampTreePopulator();
        $tree->setBaseAmount(1);
        $tree->setRandomAmount(3);

        $this->addPopulator($mushroom);
        $this->addPopulator($bigMushroom);
        $this->addPopulator($lilyPad);
        $this->addPopulator($flower);
        $this->addPopulator($tallGrass);
        $this->addPopulator($sugarCane);
        $this->addPopulator($tree);
        $this->setElevation(62, 63);

        $this->temperature = 0.41;
        $this->rainfall = 0;
    }

    public function getName(): string
    {
        return "Swamp";
    }
}