<?php


namespace GeneratorRemake\normal\biome\cold;

use GeneratorRemake\normal\populator\Flower;
use GeneratorRemake\normal\populator\Mushroom;
use GeneratorRemake\populator\MossStone;
use GeneratorRemake\populator\PopulatorTallGrass;
use GeneratorRemake\populator\TallGrass;
use GeneratorRemake\populator\Tree;
use GeneratorRemake\populator\tree\SpruceMegaTreePopulator;
use pocketmine\block\Block;
use pocketmine\block\Sapling;

class MegaTaigaBiome extends TaigaBiome
{

    public function __construct()
    {
        parent::__construct();
        $this->clearPopulators();

        $trees = new Tree(Sapling::SPRUCE);
        $trees->setBaseAmount(1);
        $trees->setRandomAmount(3);
        $this->addPopulator($trees);

        $mossStone = new MossStone();
        $mossStone->setBaseAmount(0);
        $mossStone->setRandomAmount(1);
        $this->addPopulator($mossStone);

        $mushrooms = new Mushroom();
        $mushrooms->setBaseAmount(3);
        $mushrooms->setRandomAmount(2);
        $this->addPopulator($mushrooms);

        $trees = new SpruceMegaTreePopulator();
        $trees->setBaseAmount(5);
        $trees->setRandomAmount(10);
        $this->addPopulator($trees);

        $this->setElevation(67, 78);

        $this->setGroundCover([
            Block::get(Block::PODZOL, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0)
        ]);

        $flowers = new Flower();
        $flowers->addType([38, 0]);
        $flowers->addType([37, 0]);
        $flowers->setBaseAmount(2);
        $flowers->setRandomAmount(2);
        $this->addPopulator($flowers);

        $grass = new TallGrass();
        $grass->setBaseAmount(5);
        $grass->setRandomAmount(6);
        $grass->setMeta(2);
        $this->addPopulator($grass);

        $doubleGrass = new PopulatorTallGrass(5, 6);
        $doubleGrass->setType(2);
        $this->addPopulator($doubleGrass);
    }

    public function getName(): string
    {
        return "Mega Taiga";
    }

}