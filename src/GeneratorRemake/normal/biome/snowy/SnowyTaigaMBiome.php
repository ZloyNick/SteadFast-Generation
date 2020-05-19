<?php


namespace GeneratorRemake\normal\biome\snowy;


use GeneratorRemake\normal\biome\FreezedBiome;
use GeneratorRemake\normal\biome\Mountainable;

class SnowyTaigaMBiome extends SnowyTaigaBiome implements FreezedBiome, Mountainable
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getName(): string
    {
        return "Snowy Taiga Mountains";
    }

}