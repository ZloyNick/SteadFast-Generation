<?php

namespace GeneratorRemake\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class BigMushroom extends VariableAmountPopulator
{
    private $level;

    /**
     * BigMushroom constructor.
     * @param int $odd
     */
    public function __construct($odd = 12)
    {
        parent::__construct(1, 0, $odd);
    }

    public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        $this->level = $level;
        if (!$this->checkOdd($random)) {
            return;
        }
        $x = $chunkX * 16;
        $z = $chunkZ * 16;
        $y = $this->getHighestWorkableBlock($x, $z);
        if (!$this->canMushroomStay($x, $y, $z) || $y == -1)
            return;
        if ($random->nextBoolean()) { //red
            for ($i = 0; $i < $random->nextRange(5, 6); $i++) {
                $level->setBlockIdAt($x, $y + $i, $z, 99);
                $level->setBlockDataAt($x, $y + $i, $z, 10);

            }

            //top
            for ($xx = 0; $xx < 3; $xx++) {
                for ($zz = 0; $zz < 3; $zz++) {
                    $level->setBlockIdAt($x - 1 + $xx, $y + $i, $z - 1 + $zz, 100);
                    $level->setBlockDataAt($x - 1 + $xx, $y + $i, $z - 1 + $zz, 14);
                }
            }

            //left pos
            for ($yy = 0; $yy < 3; $yy++) {
                for ($zz = 0; $zz < 3; $zz++) {
                    $level->setBlockIdAt($x + 2, $y + $i - $yy - 1, $z - 1 + $zz, 100);
                    $level->setBlockDataAt($x + 2, $y + $i - $yy - 1, $z - 1 + $zz, 14);
                }
            }

            //right pos
            for ($yy = 0; $yy < 3; $yy++) {
                for ($zz = 0; $zz < 3; $zz++) {
                    $level->setBlockIdAt($x - 2, $y + $i - $yy - 1, $z - 1 + $zz, 100);
                    $level->setBlockDataAt($x - 2, $y + $i - $yy - 1, $z - 1 + $zz, 14);
                }
            }

            //behind pos
            for ($yy = 0; $yy < 3; $yy++) {
                for ($xx = 0; $xx < 3; $xx++) {
                    $level->setBlockIdAt($x - 1 + $xx, $y + $i - $yy - 1, $z - 2, 100);
                    $level->setBlockDataAt($x - 1 + $xx, $y + $i - $yy - 1, $z - 2, 14);
                }
            }

            //ahead pos
            for ($yy = 0; $yy < 3; $yy++) {
                for ($xx = 0; $xx < 3; $xx++) {
                    $level->setBlockIdAt($x - 1 + $xx, $y + $i - $yy - 1, $z + 2, 100);
                    $level->setBlockDataAt($x - 1 + $xx, $y + $i - $yy - 1, $z + 2, 14);
                }
            }

        } else { //brown

            for ($i = 0; $i < $random->nextRange(5, 7); $i++) {
                $level->setBlockIdAt($x, $y + $i, $z, 99);
                $level->setBlockDataAt($x, $y + $i, $z, 10);
            }

            for ($xx = 0; $xx < 7; $xx++) {
                for ($zz = 0; $zz < 7; $zz++) {
                    $level->setBlockIdAt($x - 3 + $xx, $y + $i, $z - 3 + $zz, 99);
                    $level->setBlockDataAt($x - 3 + $xx, $y + $i, $z - 3 + $zz, 14);
                }
            }
            $level->setBlockIdAt($x - 3, $y + $i, $z - 3, 0);
            $level->setBlockIdAt($x - 3, $y + $i, $z + 3, 0);
            $level->setBlockIdAt($x + 3, $y + $i, $z + 3, 0);
            $level->setBlockIdAt($x + 3, $y + $i, $z - 3, 0);
        }
    }

    private function getHighestWorkableBlock($x, $z)
    {
        for ($y = 127; $y >= 0; --$y) {
            $b = $this->level->getBlockIdAt($x, $y, $z);
            if ($b !== Block::AIR and $b !== Block::LEAVES and $b !== Block::LEAVES2 and $b !== Block::SNOW_LAYER) {
                break;
            }
        }
        return $y === 0 ? -1 : ++$y;
    }

    private function canMushroomStay($x, $y, $z)
    {
        $c = $this->level->getBlockIdAt($x, $y, $z);
        $b = $this->level->getBlockIdAt($x, $y - 1, $z);
        return ($c === Block::AIR or $c === Block::SNOW_LAYER) and ($b === Block::MYCELIUM or $b === Block::GRASS or $b === Block::PODZOL);
    }
}