<?php


namespace GeneratorRemake\populator;


use GeneratorRemake\normal\biome\Mountainable;
use GeneratorRemake\normal\Normal;
use GeneratorRemake\selector\Biome;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\FullChunk;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\utils\Random;

class MountainsPopulator extends Populator
{

    /** @var Simplex */
    private $noiseBase;

    /**
     * MountainsPopulator constructor.
     * @param Random $random
     */
    public function __construct(Random $random)
    {
        $this->noiseBase = new Simplex($random, 4, 1 / 4, 1 / 32);
    }

    /**
     * @param ChunkManager $level
     * @param $chunkX
     * @param $chunkZ
     * @param Random $random
     */
    public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        $noise = Generator::getFastNoise3D($this->noiseBase, 16, 128, 16, 4, 8, 4, $chunkX * 16, 0, $chunkZ * 16);
        for ($x = 0; $x < 16; $x++) {
            for ($z = 0; $z < 16; $z++) {
                $minSum = 0;
                $maxSum = 0;
                $weightSum = 0;
                /** @var FullChunk $chunk */
                $chunk = $level->getChunk($chunkX, $chunkZ);
                /** @var Biome $biome */
                $biome = $chunk->getBiomeId($x, $z);
                if (!$biome instanceof Mountainable)
                    return;

                for ($sx = -Normal::getSmoothSize(); $sx <= Normal::getSmoothSize(); $sx++) {
                    for ($sz = -Normal::getSmoothSize(); $sz <= Normal::getSmoothSize(); $sz++) {

                        $weight = Normal::getGaussianKernel()[$sx + Normal::getSmoothSize()] [$sz + Normal::getSmoothSize()];
                        $minSum += ($biome->getMinElevation() - 1) * $weight;
                        $maxSum += $biome->getMaxElevation() * $weight;

                        $weightSum += $weight;
                    }
                }

                $minSum /= $weightSum;
                $maxSum /= $weightSum;

                $smoothHeight = ($maxSum - $minSum) / 2;

                for ($y = $this->getHighestWorkableBlock($level, $x, $z); $y < 256 && $y != -1; $y++) {
                    $noiseValue = $noise[$x] [$z] [$y] - 1 / $smoothHeight * ($y - $smoothHeight - $minSum);

                    if ($noiseValue > 0) {
                        $chunk->setBlockId($x, $y, $z, Block::STONE);
                    } elseif ($y <= Normal::getSeaHeight()) {
                        $chunk->setBlockId($x, $y, $z, Block::STILL_WATER);
                    }
                }
            }
        }
    }

    /**
     * @param ChunkManager $level
     * @param int $x
     * @param int $z
     * @return int
     */
    public function getHighestWorkableBlock(ChunkManager $level, int $x, int $z): int
    {
        for ($y = 256; $y > 0; $y--)
            if ($level->getBlockIdAt($x, $y, $z) == Block::STONE)
                return $y;
        return -1;
    }

}