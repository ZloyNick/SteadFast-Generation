<?php


namespace GeneratorRemake\normal\biome\hot;


use GeneratorRemake\populator\LavaLakePopulator;

class MesaPlateuFMBiome extends MesaPlateauFBiome
{

    public function __construct()
    {
        parent::__construct();
        $lava = new LavaLakePopulator(0, 1);
        $this->addPopulator($lava);
    }

    public function getName(): string
    {
        return "Mesa Plateu F Mountains";
    }

}