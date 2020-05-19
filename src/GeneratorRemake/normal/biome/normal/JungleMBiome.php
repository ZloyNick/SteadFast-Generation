<?php


namespace GeneratorRemake\normal\biome\normal;

use GeneratorRemake\normal\biome\Mountainable;

class JungleMBiome extends JungleBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getName(): string
    {
        return "Jungle M";
    }

}