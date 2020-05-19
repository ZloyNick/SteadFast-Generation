<?php


namespace GeneratorRemake\normal\biome\normal;


use GeneratorRemake\normal\biome\GrassyBiome;
use GeneratorRemake\normal\populator\Tree;
use GeneratorRemake\populator\Flower;
use GeneratorRemake\populator\PopulatorTallGrass;
use GeneratorRemake\populator\TallGrass;
use pocketmine\block\Sapling;

class PlainsBiome extends GrassyBiome
{

    public function __construct()
    {
        parent::__construct();

        $trees = new Tree(Sapling::OAK);
        $trees->setBaseAmount(1);
        $trees->setRandomAmount(0);
        $trees->setOdd(6);
        $this->addPopulator($trees);

        $grass = new TallGrass();
        $grass->setBaseAmount(10);
        $grass->setRandomAmount(20);
        $this->addPopulator($grass);
        $doubleGrass = new PopulatorTallGrass(5, 5);
        $doubleGrass->setType(2);
        $this->addPopulator($doubleGrass);
        $flower = new Flower();
        $flower->setBaseAmount(1);
        $flower->setRandomAmount(5);
        $flower->addType([38, 0]);
        $flower->addType([38, 3]);
        $flower->addType([38, 4]);
        $flower->addType([38, 5]);
        $flower->addType([38, 6]);
        $flower->addType([38, 7]);
        $flower->addType([38, 8]);
        $this->addPopulator($flower);
    }

    public function getName(): string
    {
        return "Plains";
    }

}