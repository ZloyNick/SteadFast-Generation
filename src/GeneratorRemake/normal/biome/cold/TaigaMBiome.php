<?php


namespace GeneratorRemake\normal\biome\cold;


use GeneratorRemake\normal\biome\Mountainable;

class TaigaMBiome extends TaigaBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getName(): string
    {
        return "Taiga Mountains";
    }

}