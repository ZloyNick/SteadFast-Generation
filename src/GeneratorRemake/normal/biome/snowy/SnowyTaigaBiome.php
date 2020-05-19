<?php


namespace GeneratorRemake\normal\biome\snowy;


use GeneratorRemake\normal\biome\FreezedBiome;
use GeneratorRemake\normal\biome\SnowyBiome;
use GeneratorRemake\normal\populator\Flower;
use GeneratorRemake\normal\populator\Tree;
use GeneratorRemake\populator\TallGrass;
use pocketmine\block\Block;
use pocketmine\block\Sapling;

class SnowyTaigaBiome extends SnowyBiome implements FreezedBiome
{

    public function __construct()
    {
        parent::__construct();
        //height
        $this->setElevation(67, 78);
        //top-blocks
        $this->setGroundCover(
            [
                Block::get(Block::SNOW_LAYER, 0),
                Block::get(Block::GRASS, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
            ]
        );

        $trees = new Tree(Sapling::SPRUCE);
        $trees->setBaseAmount(10);
        $trees->setRandomAmount(20);
        $this->addPopulator($trees);

        $pap = new TallGrass();
        $pap->setMeta(2);
        $pap->setBaseAmount(3);
        $pap->setRandomAmount(6);
        $this->addPopulator($pap);

        $flowers = new Flower(5, 10);
        $flowers->addType([37, 0]);
        $flowers->addType([38, 0]);
        $this->addPopulator($flowers);
    }

    public function getName(): string
    {
        return "Snowy Taiga";
    }

}