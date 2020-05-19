<?php


namespace GeneratorRemake\normal\biome\cold;


use GeneratorRemake\normal\biome\GrassyBiome;
use pocketmine\block\Block;

class StoneBeachBiome extends GrassyBiome
{

    public function __construct()
    {
        parent::__construct();
        $this->setGroundCover(
            [
                Block::get(Block::STONE, 0),
                Block::get(Block::STONE, 0),
                Block::get(Block::STONE, 0),
                Block::get(Block::STONE, 0),
            ]
        );
    }

    public function getName(): string
    {
        return "Stone Beach";
    }

}