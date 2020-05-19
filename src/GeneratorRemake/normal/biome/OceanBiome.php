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

namespace GeneratorRemake\normal\biome;

use GeneratorRemake\populator\Mushroom;
use GeneratorRemake\populator\SugarCane;
use GeneratorRemake\populator\TallGrass;
use pocketmine\block\Block;

class OceanBiome extends WateryBiome
{

    public function __construct()
    {
        parent::__construct();
        $this->setGroundCover([
            Block::get(Block::SAND, 0),
            Block::get(Block::SAND, 0),
            Block::get(Block::SAND, 0),
            Block::get(Block::SAND, 0),
            Block::get(Block::SAND, 0),
        ]);

        $sugarcane = new SugarCane();
        $sugarcane->setBaseAmount(6);
        $tallGrass = new TallGrass();
        $tallGrass->setBaseAmount(5);
        $mushroom = new Mushroom();

        $this->addPopulator($mushroom);
        $this->addPopulator($sugarcane);
        $this->addPopulator($tallGrass);

        $this->setElevation(55, 65);

        $this->temperature = 0;
        $this->rainfall = 0;
    }

    public function getName(): string
    {
        return "Ocean";
    }
}
