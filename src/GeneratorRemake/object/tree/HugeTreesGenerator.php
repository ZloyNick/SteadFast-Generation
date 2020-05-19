<?php

namespace GeneratorRemake\object\tree;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

abstract class HugeTreesGenerator extends TreeGenerator
{
    /**
     * The base height of the tree
     */
    protected $baseHeight;

    /**
     * Sets the metadata for the wood blocks used
     */
    protected $woodMetadata;

    /**
     * Sets the metadata for the leaves used in huge trees
     */
    protected $leavesMetadata;
    protected $extraRandomHeight;

    public function __construct(int $baseHeightIn, int $extraRandomHeightIn, Block $woodMetadataIn, Block $leavesMetadataIn)
    {
        $this->baseHeight = $baseHeightIn;
        $this->extraRandomHeight = $extraRandomHeightIn;
        $this->woodMetadata = $woodMetadataIn;
        $this->leavesMetadata = $leavesMetadataIn;
    }

    /*
     * Calculates the height based on this trees base height and its extra random height
     */
    protected function getHeight(Random $rand)
    {
        $i = $rand->nextBoundedInt(3) + $this->baseHeight;

        if ($this->extraRandomHeight > 1) {
            $i += $rand->nextBoundedInt($this->extraRandomHeight);
        }

        return $i;
    }

    /*
     * returns whether or not there is space for a tree to grow at a certain position
     */

    protected function ensureGrowable(ChunkManager $worldIn, Random $rand, Vector3 $treePos, int $p_175929_4_)
    {
        return $this->isSpaceAt($worldIn, $treePos, $p_175929_4_) && $this->ensureDirtsUnderneath($treePos, $worldIn);
    }

    /*
     * returns whether or not there is dirt underneath the block where the tree will be grown.
     * It also generates dirt around the block in a 2x2 square if there is dirt underneath the blockpos.
     */

    private function isSpaceAt(ChunkManager $worldIn, Vector3 $leavesPos, int $height)
    {
        $flag = true;

        if ($leavesPos->getY() >= 1 && $leavesPos->getY() + $height + 1 <= 256) {
            for ($i = 0; $i <= 1 + $height; ++$i) {
                $j = 2;

                if ($i == 0) {
                    $j = 1;
                } else if ($i >= 1 + $height - 2) {
                    $j = 2;
                }

                for ($k = -$j; $k <= $j && $flag; ++$k) {
                    for ($l = -$j; $l <= $j && $flag; ++$l) {
                        $blockPos = $leavesPos->add($k, $i, $l);
                        if ($leavesPos->getY() + $i < 0 || $leavesPos->getY() + $i >= 256 || !$this->canGrowInto($worldIn->getBlockIdAt((int)$blockPos->x, (int)$blockPos->y, (int)$blockPos->z))) {
                            $flag = false;
                        }
                    }
                }
            }

            return $flag;
        } else {
            return false;
        }
    }

    /*
     * returns whether or not a tree can grow at a specific position.
     * If it can, it generates surrounding dirt underneath.
     */

    private function ensureDirtsUnderneath(Vector3 $pos, ChunkManager $worldIn)
    {
        $blockpos = $pos->getSide(Vector3::SIDE_DOWN);
        $block = $worldIn->getBlockIdAt((int)$blockpos->x, (int)$blockpos->y, (int)$blockpos->z);

        if (($block == Block::GRASS || $block == Block::DIRT) && $pos->getY() >= 2) {
            $this->setDirtAt($worldIn, $blockpos);
            $this->setDirtAt($worldIn, $blockpos->getSide(Vector3::SIDE_EAST));
            $this->setDirtAt($worldIn, $blockpos->getSide(Vector3::SIDE_SOUTH));
            $this->setDirtAt($worldIn, $blockpos->getSide(Vector3::SIDE_SOUTH)->getSide(Vector3::SIDE_EAST));
            return true;
        } else {
            return false;
        }
    }

    protected function growLeavesLayerStrict(ChunkManager $worldIn, Vector3 $layerCenter, int $width)
    {
        $i = $width * $width;

        for ($j = -$width; $j <= $width + 1; ++$j) {
            for ($k = -$width; $k <= $width + 1; ++$k) {
                $l = $j - 1;
                $i1 = $k - 1;

                if ($j * $j + $k * $k <= $i || $l * $l + $i1 * $i1 <= $i || $j * $j + $i1 * $i1 <= $i || $l * $l + $k * $k <= $i) {
                    $blockpos = $layerCenter->add($j, 0, $k);
                    $id = $worldIn->getBlockIdAt((int)$blockpos->x, (int)$blockpos->y, (int)$blockpos->z);

                    if ($id == Block::AIR || $id == Block::LEAVES) {
                        $this->setBlockAndNotifyAdequately($worldIn, $blockpos, $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
                    }
                }
            }
        }
    }

    /*
     * grow leaves in a circle
     */
    protected function growLeavesLayer(ChunkManager $worldIn, Vector3 $layerCenter, int $width)
    {
        $i = $width * $width;

        for ($j = -$width; $j <= $width; ++$j) {
            for ($k = -$width; $k <= $width; ++$k) {
                if ($j * $j + $k * $k <= $i) {
                    $blockpos = $layerCenter->add($j, 0, $k);
                    $id = $worldIn->getBlockIdAt((int)$blockpos->x, (int)$blockpos->y, (int)$blockpos->z);

                    if ($id == Block::AIR || $id == Block::LEAVES) {
                        $this->setBlockAndNotifyAdequately($worldIn, $blockpos, $this->leavesMetadata->getId(), $this->leavesMetadata->getDamage());
                    }
                }
            }
        }
    }
}

