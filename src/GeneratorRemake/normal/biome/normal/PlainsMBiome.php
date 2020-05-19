<?php


namespace GeneratorRemake\normal\biome\normal;


use GeneratorRemake\normal\biome\Mountainable;
use GeneratorRemake\normal\populator\WaterPit;

class PlainsMBiome extends PlainsBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();
        $water = new WaterPit(0, 1);
        $this->addPopulator($water);
    }

    public function getName(): string
    {
        return "Plains Mountains";
    }

}