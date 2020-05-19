<?php


namespace GeneratorRemake\normal\biome\normal;


use GeneratorRemake\normal\populator\Tree;
use GeneratorRemake\populator\PopulatorTallGrass;
use pocketmine\block\Sapling;

class SunflowerPlainsBiome extends PlainsBiome
{

    public function __construct()
    {
        parent::__construct();

        $trees = new Tree(Sapling::OAK);
        $trees->setBaseAmount(1);
        $trees->setRandomAmount(0);
        $trees->setOdd(6);
        $this->addPopulator($trees);

        $populator = new PopulatorTallGrass();
        $populator->setType(0);
        $populator->setBaseAmount(8);
        $populator->setRandomAmount(5);
        $this->addPopulator($populator);
    }

    public function getName(): string
    {
        return "Sunflower Plains";
    }

}