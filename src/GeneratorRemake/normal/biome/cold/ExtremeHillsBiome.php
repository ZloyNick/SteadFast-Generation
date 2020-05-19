<?php


namespace GeneratorRemake\normal\biome\cold;


use GeneratorRemake\normal\biome\GrassyBiome;
use GeneratorRemake\normal\biome\Mountainable;
use GeneratorRemake\normal\populator\Flower;
use GeneratorRemake\normal\populator\Tree;
use pocketmine\block\Block;
use pocketmine\block\Sapling;

class ExtremeHillsBiome extends GrassyBiome implements Mountainable
{

    public function __construct()
    {
        parent::__construct();
        $trees = new Tree(Sapling::SPRUCE);
        $trees->setBaseAmount(2);
        $trees->setRandomAmount(2);
        $this->addPopulator($trees);

        $this->setGroundCover(
            [
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
            ]
        );

        $trees2 = new Tree(Sapling::OAK);
        $trees2->setBaseAmount(2);
        $trees2->setRandomAmount(2);
        $this->addPopulator($trees2);

        $flowers = new Flower(3, 6);
        $this->addPopulator($flowers);

        $this->setElevation(67, 82);
    }

    public function canCoverBlockStay(int $y): bool
    {
        return true;
    }

    public function haveCoverBlock(): bool
    {
        return true;
    }

    public function getCoverBlock(): int
    {
        return mt_rand(0, 7) == 0 ? Block::GRAVEL : Block::GRASS;
    }

    public function getName(): string
    {
        return "Extreme Hills";
    }

}