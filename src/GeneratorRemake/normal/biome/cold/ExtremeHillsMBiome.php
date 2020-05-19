<?php


namespace GeneratorRemake\normal\biome\cold;


use GeneratorRemake\normal\biome\Mountainable;
use pocketmine\block\Block;

class ExtremeHillsMBiome extends ExtremeHillsBiome implements Mountainable
{
    public function __construct()
    {
        parent::__construct();
        $this->setElevation(67, 90);
    }

    public function getName(): string
    {
        return "Extreme Hills M";
    }

    public function haveCoverBlock(): bool
    {
        return true;
    }

    public function getCoverBlock(): int
    {
        return ($r = mt_rand(0, 10)) < 5 ? Block::GRASS : Block::GRAVEL;
    }

    public function canCoverBlockStay(int $y): bool
    {
        return true;
    }

    public function haveMoreCover(): bool
    {
        return true;
    }

    public function getMoreCover(): array
    {
        return [Block::SNOW_LAYER => [80, 127]];
    }

}