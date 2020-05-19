<?php


namespace GeneratorRemake\normal\biome\normal;


use GeneratorRemake\normal\biome\Mountainable;

class RoofedForestMBiome extends RoofedForestBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getName(): string
    {
        return "Roofed Forest M";
    }

}