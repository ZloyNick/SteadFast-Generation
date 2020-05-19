<?php


namespace GeneratorRemake\normal\biome\cold;


use GeneratorRemake\normal\biome\MountainablePlus;
use GeneratorRemake\normal\populator\Tree;
use pocketmine\block\Block;
use pocketmine\block\Sapling;

class ExtremeHillsPlusBiome extends ExtremeHillsBiome implements MountainablePlus
{

    public function __construct()
    {
        parent::__construct();
        $this->setGroundCover(
            [
                Block::get(Block::GRASS, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
            ]
        );
        foreach ($this->getPopulators() as $populator) {
            if ($populator instanceof Tree) {
                /** @var Tree $populator */
                if ($populator->getType() == Sapling::SPRUCE) {
                    $populator->setBaseAmount(5);
                    $populator->setRandomAmount(10);
                }
                if ($populator->getType() == Sapling::OAK) {
                    $populator->setBaseAmount(3);
                    $populator->setRandomAmount(2);
                }
            }
        }
    }

    public function getName(): string
    {
        return "Extreme Hills Plus";
    }

    public function canCoverBlockStay(int $y): bool
    {
        return $y > 80;
    }

    public function haveCoverBlock(): bool
    {
        return true;
    }

    public function getCoverBlock(): int
    {
        return Block::SNOW_LAYER;
    }

}