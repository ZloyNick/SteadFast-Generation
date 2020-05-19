<?php
/*
Finish ,more in future
*/

namespace GeneratorRemake\normal\biome\snowy;

use GeneratorRemake\normal\biome\FreezedBiome;
use GeneratorRemake\normal\biome\SnowyBiome;
use pocketmine\block\Block;

class FrozenRiverBiome extends SnowyBiome implements FreezedBiome
{
    public function __construct()
    {
        parent::__construct();

        $this->setElevation(56, 74);
        $this->setGroundCover([
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0),
            Block::get(Block::DIRT, 0),
        ]);
    }

    public function getName(): string
    {
        return "Frozen River";
    }
}
