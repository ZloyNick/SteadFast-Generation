<?php

namespace GeneratorRemake\object\tree;

use GeneratorRemake\object\BasicGenerator;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

abstract class TreeGenerator extends BasicGenerator
{

    /*
     * returns whether or not a tree can grow into a block
     * For example, a tree will not grow into stone
     */
    public function generateSaplings(Level $level, Random $random, Vector3 $pos)
    {
    }

    protected function canGrowInto(int $id)
    {
        return $id == Item::AIR || $id == Item::LEAVES || $id == Item::GRASS || $id == Item::DIRT || $id == Item::LOG || $id == Item::LOG2 || $id == Item::SAPLING || $id == Item::VINE;
    }

    /*
     * sets dirt at a specific location if it isn't already dirt
     */

    protected function setDirtAt(ChunkManager $level, Vector3 $pos)
    {
        if ($level->getBlockIdAt((int)$pos->x, (int)$pos->y, (int)$pos->z) != Item::DIRT) {
            $this->setBlockAndNotifyAdequately($level, $pos, Block::DIRT);
        }
    }
}
