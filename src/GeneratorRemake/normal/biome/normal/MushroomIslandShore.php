<?php


namespace GeneratorRemake\normal\biome\normal;


use GeneratorRemake\normal\biome\Mountainable;

class MushroomIslandShore extends MushroomIslandBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();
        $this->setElevation(56, 60);
    }

    public function getName(): string
    {
        return "Mushroom Island Shore";
    }

}