<?php


namespace GeneratorRemake\normal\biome\snowy;


use GeneratorRemake\normal\biome\FreezedBiome;
use GeneratorRemake\normal\biome\SnowyBiome;
use GeneratorRemake\normal\populator\Tree;
use GeneratorRemake\populator\PopulatorTallGrass;
use pocketmine\block\Block;
use pocketmine\block\Sapling;

class SnowyTundraBiome extends SnowyBiome implements FreezedBiome
    /** For ice populating */
{

    const ID = 12;

    public function __construct()
    {
        parent::__construct();
        //height
        $this->setElevation(67, 78);
        //top-blocks
        $this->setGroundCover(
            [
                Block::get(Block::SNOW, 0),
                Block::get(Block::GRASS, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
            ]
        );

        //spruce trees
        $spruceTrees = new Tree(Sapling::SPRUCE);
        $spruceTrees->setBaseAmount(1);
        $spruceTrees->setRandomAmount(5);
        $this->addPopulator($spruceTrees);

        //oak trees
        $oakTrees = new Tree(Sapling::OAK);
        $oakTrees->setBaseAmount(1);
        $oakTrees->setRandomAmount(5);
        $this->addPopulator($oakTrees);

        //grass
        $grass = new PopulatorTallGrass();
        $grass->setBaseAmount(5);
        $grass->setRandomAmount(10);
        $this->addPopulator($grass);
    }

    public function getName(): string
    {
        return "Snowy Tundra";
    }

}