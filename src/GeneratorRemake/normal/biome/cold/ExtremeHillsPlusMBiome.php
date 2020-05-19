<?php


namespace GeneratorRemake\normal\biome\cold;


use GeneratorRemake\normal\biome\MountainablePlus;
use pocketmine\block\Block;

class ExtremeHillsPlusMBiome extends ExtremeHillsBiome implements MountainablePlus
{

    public function __construct()
    {
        parent::__construct();
        $this->setElevation(67, 90);
    }

    public function haveCoverBlock(): bool
    {
        return true;
    }

    public function canCoverBlockStay(int $y): bool
    {
        return true;
    }

    public function getCoverBlock(): int
    {
        return ($r = mt_rand(0, 10)) < 2 ? Block::GRASS : ($r < 4 ? Block::DIRT : Block::GRAVEL);
    }

}