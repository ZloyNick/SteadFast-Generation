<?php

namespace GeneratorRemake\normal\biome\hot;

use GeneratorRemake\normal\biome\SandyBiome;
use GeneratorRemake\populator\Cactus;
use GeneratorRemake\populator\DeadBush;
use GeneratorRemake\populator\SugarCane;
use GeneratorRemake\populator\TemplePopulator;
use GeneratorRemake\populator\WellPopulator;
use pocketmine\block\Block;

class DesertBiome extends SandyBiome
{

    public function __construct()
    {
        parent::__construct();

        $deadBush = new DeadBush();
        $deadBush->setBaseAmount(1);
        $deadBush->setRandomAmount(4);
        $deadBush->setOdd(8);

        $sugarCane = new SugarCane();
        $sugarCane->setRandomAmount(20);
        $sugarCane->setBaseAmount(3);

        $cactus = new Cactus();
        $cactus->setBaseAmount(0);
        $cactus->setRandomAmount(1);

        $this->addPopulator($cactus);
        $this->addPopulator($deadBush);
        $this->addPopulator($sugarCane);


        $well = new WellPopulator();
        $this->addPopulator($well);

        $temple = new TemplePopulator();
        $this->addPopulator($temple);

        $this->setElevation(63, 74);

        $this->temperature = 0.76;
        $this->rainfall = 0.25;
        $this->setGroundCover([
            Block::get(Block::SAND, 0),
            Block::get(Block::SAND, 0),
            Block::get(Block::SAND, 0),
            Block::get(Block::SAND, 0),
            Block::get(Block::SANDSTONE, 0),
            Block::get(Block::SANDSTONE, 0),
            Block::get(Block::SANDSTONE, 0),
            Block::get(Block::SANDSTONE, 0),
            Block::get(Block::SANDSTONE, 0),
            Block::get(Block::SANDSTONE, 0),
            Block::get(Block::SANDSTONE, 0),
            Block::get(Block::SANDSTONE, 0)
        ]);
    }

    public function getName(): string
    {
        return "Desert";
    }
}