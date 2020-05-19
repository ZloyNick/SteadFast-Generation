<?php

/*
Need add mushroom here.
*/

namespace GeneratorRemake\normal\biome\normal;

use GeneratorRemake\normal\biome\GrassyBiome;
use GeneratorRemake\populator\BigMushroom;
use GeneratorRemake\populator\Mushroom;
use pocketmine\block\Block;

class MushroomIslandBiome extends GrassyBiome
{

    public function __construct()
    {
        parent::__construct();
        $this->setGroundCover([
            Block::get(Block::MYCELIUM, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0),
        ]);

        //mushrooms
        $mushroom = new Mushroom(8);
        $bigMushroom = new BigMushroom(5);
        $bigMushroom->setBaseAmount(1);
        $bigMushroom->setRandomAmount(3);
        $mushroom->setBaseAmount(0);
        $mushroom->setRandomAmount(8);

        $this->addPopulator($mushroom);
        $this->addPopulator($bigMushroom);

        $this->temperature = 0.61;
        $this->rainfall = 0;
    }

    public function getName(): string
    {
        return "Mushroom Island";
    }
}
