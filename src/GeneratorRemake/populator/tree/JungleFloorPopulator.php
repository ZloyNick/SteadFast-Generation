<?php

namespace GeneratorRemake\populator\tree;

use GeneratorRemake\object\tree\NewJungleTree;
use GeneratorRemake\populator\Populator;
use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\FullChunk;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class JungleFloorPopulator extends Populator
{

    /** @var ChunkManager */
    private $level;
    private $randomAmount;
    private $baseAmount;

    private $type;

    public function __construct(int $type = Sapling::JUNGLE)
    {
        $this->type = $type;
    }

    public function setRandomAmount(int $randomAmount)
    {
        $this->randomAmount = $randomAmount;
    }

    public function setBaseAmount(int $baseAmount)
    {
        $this->baseAmount = $baseAmount;
    }

    public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        $this->level = $level;
        $chunk = $level->getChunk($chunkX, $chunkZ);
        $amount = $random->nextBoundedInt($this->randomAmount + 1) + $this->baseAmount;
        $v = new Vector3();

        for ($i = 0; $i < $amount; ++$i) {
            $x = $this->randomRange($random, $chunkX << 4, ($chunkX << 4) + 15);
            $z = $this->randomRange($random, $chunkZ << 4, ($chunkZ << 4) + 15);
            $y = $this->getHighestWorkableBlock($level, $x, $z, $chunk);
            if ($y == -1) {
                continue;
            }

            $object = new NewJungleTree(1, 0);
            $object->generate($level, $random, $v->setComponents($x, $y, $z));
        }
    }

    private function randomRange(Random $random, int $start, int $end)
    {
        return $start + ($random->nextInt() % ($end + 1 - $start));
    }

    protected function getHighestWorkableBlock(ChunkManager $level, int $x, int $z, FullChunk $chunk)
    {
        for ($y = 255; $y > 0; --$y) {
            $b = $this->level->getBlockIdAt($x, $y, $z);
            if ($b == Block::DIRT || $b == Block::GRASS) {
                break;
            } else if ($b != Block::AIR && $b != Block::SNOW_LAYER) {
                return -1;
            }
        }
        return ++$y;
    }
}
