<?php


namespace GeneratorRemake\normal\biome\snowy;


use GeneratorRemake\normal\biome\FreezedBiome;
use GeneratorRemake\normal\biome\SnowyBiome;
use GeneratorRemake\populator\Populator;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\FullChunk;
use pocketmine\utils\Random;

class IcePlainsSpikesBiome extends SnowyBiome implements FreezedBiome
{

    const ID = 140;

    public function __construct()
    {
        parent::__construct();

        $this->setElevation(67, 78);
        $this->setGroundCover(
            [
                Block::get(Block::SNOW, 0),
                Block::get(Block::GRASS, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
                Block::get(Block::DIRT, 0),
            ]
        );
        $iceSpikes = new PopulatorIceSpikes();
        $this->addPopulator($iceSpikes);
    }

    public function getName(): string
    {
        return "Ice Plains Spikes Biome";
    }

}

class PopulatorIceSpikes extends Populator
{

    public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        $chunk = $level->getChunk($chunkX, $chunkZ);
        for ($i = 0; $i < 8; $i++) {
            $x = ($chunkX << 4) + $random->nextBoundedInt(16);//random x
            $z = ($chunkZ << 4) + $random->nextBoundedInt(16);//random z
            $isTall = $random->nextBoundedInt(16) == 0;//tall random
            $height = 10 + $random->nextBoundedInt(16) + ($isTall ? $random->nextBoundedInt(20) : 0);//max 57
            $startY = $this->getHighestWorkableBlock($x, $z, $chunk);//highest block
            $maxY = min(127, $startY + $height);//$startY+57 max
            if ($isTall) {
                echo "IsTall\n";
                for ($y = $startY; $y < $maxY; $y++) {
                    //center column
                    $level->setBlockIdAt($x, $y, $z, Block::PACKED_ICE);
                    //t shape
                    $level->setBlockIdAt($x + 1, $y, $z, Block::PACKED_ICE);
                    $level->setBlockIdAt($x - 1, $y, $z, Block::PACKED_ICE);
                    $level->setBlockIdAt($x, $y, $z + 1, Block::PACKED_ICE);
                    $level->setBlockIdAt($x, $y, $z - 1, Block::PACKED_ICE);
                    //additional blocks on the side
                    if ($random->nextBoolean()) {
                        $level->setBlockIdAt($x + 1, $y, $z + 1, Block::PACKED_ICE);
                    }
                    if ($random->nextBoolean()) {
                        $level->setBlockIdAt($x + 1, $y, $z - 1, Block::PACKED_ICE);
                    }
                    if ($random->nextBoolean()) {
                        $level->setBlockIdAt($x - 1, $y, $z + 1, Block::PACKED_ICE);
                    }
                    if ($random->nextBoolean()) {
                        $level->setBlockIdAt($x - 1, $y, $z - 1, Block::PACKED_ICE);
                    }
                }
                //finish with a point
                $level->setBlockIdAt($x + 1, $maxY, $z, Block::PACKED_ICE);
                $level->setBlockIdAt($x - 1, $maxY, $z, Block::PACKED_ICE);
                $level->setBlockIdAt($x, $maxY, $z + 1, Block::PACKED_ICE);
                $level->setBlockIdAt($x, $maxY, $z - 1, Block::PACKED_ICE);
                for ($y = $maxY; $y < $maxY + 3; $y++) {
                    $level->setBlockIdAt($x, $y, $z, Block::PACKED_ICE);
                }
            } else {
                echo "Not Tall\n";
                //the maximum possible radius in blocks
                $baseWidth = $random->nextBoundedInt(1) + 4;
                $shrinkFactor = $baseWidth / (float)$height;
                $currWidth = $baseWidth;
                for ($y = $startY; $y < $maxY; $y++) {
                    for ($xx = (int)-$currWidth; $xx < $currWidth; $xx++) {
                        for ($zz = (int)-$currWidth; $zz < $currWidth; $zz++) {
                            $currDist = (int)sqrt($xx * $xx + $zz * $zz);
                            if ((int)$currWidth != $currDist && $random->nextBoolean()) {
                                $level->setBlockIdAt($x + $xx, $y, $z + $zz, Block::PACKED_ICE);
                            }
                        }
                    }
                    $currWidth -= $shrinkFactor;
                }
            }
        }
    }

    public function getHighestWorkableBlock(int $x, int $z, FullChunk $chunk)
    {
        return $chunk->getHighestBlockAt($x & 0xF, $z & 0xF) - 5;
    }

    public function getName(): string
    {
        return "Ice Plains Spike";
    }
}