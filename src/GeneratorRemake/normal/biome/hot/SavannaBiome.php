<?php
/*
Finish
*/

namespace GeneratorRemake\normal\biome\hot;

use GeneratorRemake\normal\biome\GrassyBiome;
use GeneratorRemake\normal\biome\Mountainable;
use GeneratorRemake\populator\PopulatorTallGrass;
use GeneratorRemake\populator\SugarCane;
use GeneratorRemake\populator\TallGrass;
use GeneratorRemake\populator\tree\SavannaTreePopulator;

class SavannaBiome extends GrassyBiome implements Mountainable
{
    public function __construct()
    {
        parent::__construct();
        $sugarcane = new SugarCane();
        $sugarcane->setBaseAmount(6);
        $tallGrass = new TallGrass();
        $tallGrass->setBaseAmount(50);
        $populatortallGrass = new PopulatorTallGrass();
        $populatortallGrass->setType(2);
        $populatortallGrass->setBaseAmount(50);
        $trees = new SavannaTreePopulator();
        $trees->setBaseAmount(5);
        $trees->setRandomAmount(3);
        $this->addPopulator($sugarcane);
        $this->addPopulator($tallGrass);
        $this->addPopulator($populatortallGrass);
        $this->addPopulator($trees);
        $this->setElevation(67, 78);
    }

    public function getName(): string
    {
        return "Savanna";
    }

    public function getColor()
    {
        return 0xBFA243;
    }

}
