<?php


namespace GeneratorRemake\normal\biome\hot;


use GeneratorRemake\normal\biome\Mountainable;
use GeneratorRemake\normal\populator\WaterPit;

class DesertMBiome extends DesertBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();
        $waterPit = new WaterPit(0, 1);
        $this->addPopulator($waterPit);
        $this->setElevation(67, 78);
    }

    public function getName(): string
    {
        return "Desert Mountains";
    }

}