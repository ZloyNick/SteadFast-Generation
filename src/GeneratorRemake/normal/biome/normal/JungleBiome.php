<?php
/*
Need add cocoa beans variable populator
*/

namespace GeneratorRemake\normal\biome\normal;

use GeneratorRemake\normal\biome\GrassyBiome;
use GeneratorRemake\normal\populator\Flower;
use GeneratorRemake\populator\Melon;
use GeneratorRemake\populator\PopulatorTallGrass;
use GeneratorRemake\populator\Sugarcane;
use GeneratorRemake\populator\TallGrass;
use GeneratorRemake\populator\tree\JungleBigTreePopulator;
use GeneratorRemake\populator\tree\JungleFloorPopulator;
use GeneratorRemake\populator\tree\JungleTreePopulator;

class JungleBiome extends GrassyBiome
{
    public function __construct()
    {
        parent::__construct();
        $plant = new PopulatorTallGrass();
        $plant->setType(3);
        $plant->setBaseAmount(10);

        $smallTree = new JungleFloorPopulator();
        $smallTree->setRandomAmount(5);
        $smallTree->setBaseAmount(10);

        $sugarcane = new Sugarcane();
        $sugarcane->setBaseAmount(6);

        $tallGrass = new TallGrass();
        $tallGrass->setBaseAmount(50);

        $trees = new JungleTreePopulator();
        $trees->setBaseAmount(6);
        $trees->setBaseAmount(12);

        $bigTrees = new JungleBigTreePopulator();
        $bigTrees->setBaseAmount(8);
        $bigTrees->setRandomAmount(10);

        $melon = new Melon();
        $melon->setBaseAmount(3);
        $melon->setRandomAmount(5);
        $melon->setOdd(4);

        $flowers = new Flower();
        $flowers->addType([37, 0]);
        $flowers->addType([38, 0]);
        $flowers->setBaseAmount(2);
        $flowers->setRandomAmount(6);

        $this->addPopulator($flowers);
        $this->addPopulator($plant);
        $this->addPopulator($melon);
        $this->addPopulator($sugarcane);
        $this->addPopulator($tallGrass);
        $this->addPopulator($trees);
        $this->addPopulator($bigTrees);
        $this->addPopulator($smallTree);
        $this->setElevation(67, 78);
    }

    public function getName(): string
    {
        return "Jungle";
    }

    public function getColor()
    {
        return 0x92bc59;
    }
}