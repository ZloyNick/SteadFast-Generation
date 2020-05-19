<?php


namespace GeneratorRemake\normal\biome\normal;

use GeneratorRemake\populator\Flower;
use GeneratorRemake\populator\Mushroom;
use GeneratorRemake\populator\TallGrass;
use GeneratorRemake\populator\tree\FallenTreePopulator;

class FlowerForestBiome extends ForestBiome
{

    public function __construct($type = self::TYPE_NORMAL)
    {
        parent::__construct($type);
        $this->clearPopulators();
        $flower = new Flower(5, 10);
        for ($i = 2; $i < 9; $i++) {
            $flower->addType([38, $i]);
        }
        $tallGrass = new TallGrass();
        $tallGrass->setBaseAmount(3);
        $tallGrass->setRandomAmount(6);

        $mushroom = new Mushroom();
        $mushroom->setBaseAmount(1);
        $mushroom->setRandomAmount(3);
        $this->addPopulator($mushroom);
        $this->addPopulator($tallGrass);
        $this->addPopulator($flower);

        $fallenTree = new FallenTreePopulator($this->type);
        //$this->addPopulator($fallenTree);
    }

    public function getName(): string
    {
        return "Flower Forest";
    }

}