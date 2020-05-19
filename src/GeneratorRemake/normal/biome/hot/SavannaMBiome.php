<?php


namespace GeneratorRemake\normal\biome\hot;


use GeneratorRemake\normal\biome\Mountainable;
use GeneratorRemake\normal\populator\WaterPit;

class SavannaMBiome extends SavannaBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();
        $waterPit = new WaterPit(0, 1);
        $this->addPopulator($waterPit);
    }

    public function getName(): string
    {
        return "Savanna Mountains";
    }

}