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
use GeneratorRemake\populator\Flower;
use GeneratorRemake\populator\Mushroom;
use GeneratorRemake\populator\PopulatorTallGrass;
use GeneratorRemake\populator\TallGrass;
use GeneratorRemake\populator\Tree;
use GeneratorRemake\populator\tree\FallenTreePopulator;
use pocketmine\block\Sapling;

class ForestBiome extends GrassyBiome
{

    const TYPE_NORMAL = 0;
    const TYPE_BIRCH = 1;
    const TYPE_BIRCH_TALL = 2;

    public $type;

    public function __construct($type = self::TYPE_NORMAL)
    {
        parent::__construct();

        $flower = new Flower();
        $flower->addType(0);
        $flower->addType(1);
        $flower->setBaseAmount(1);
        $flower->setRandomAmount(5);
        $this->addPopulator($flower);

        $populator1 = new PopulatorTallGrass();
        $populator1->setType(1);
        $populator1->setBaseAmount(1);
        $populator1->setRandomAmount(5);
        $this->addPopulator($populator1);

        $populator2 = new PopulatorTallGrass();
        $populator2->setType(4);
        $populator2->setBaseAmount(1);
        $populator2->setRandomAmount(5);
        $this->addPopulator($populator2);

        $populator3 = new PopulatorTallGrass();
        $populator3->setType(5);
        $populator3->setBaseAmount(1);
        $populator3->setRandomAmount(5);
        $this->addPopulator($populator3);

        $this->type = $type;

        $trees = new Tree($type > self::TYPE_NORMAL ? Sapling::BIRCH : Sapling::OAK, $type != self::TYPE_BIRCH_TALL);
        $trees->setBaseAmount(5);
        $trees->setBaseAmount(2);
        $this->addPopulator($trees);

        $tallGrass = new TallGrass();
        $tallGrass->setBaseAmount(3);

        $this->addPopulator($tallGrass);

        $mushroom = new Mushroom();
        $mushroom->setBaseAmount(1);
        $mushroom->setRandomAmount(3);
        $this->addPopulator($mushroom);

        $fallenTree = new FallenTreePopulator($this->type == self::TYPE_NORMAL ? self::TYPE_NORMAL : self::TYPE_BIRCH_TALL);
        $this->addPopulator($fallenTree);

        $this->setElevation(67, $type != self::TYPE_BIRCH_TALL ? 76 : 81);
    }

    public function getName(): string
    {
        return $this->type === self::TYPE_BIRCH ? "Birch Forest" : ($this->type == self::TYPE_NORMAL ? "Forest" : "Birch Forest Mountains");
    }
}