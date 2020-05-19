<?php

namespace GeneratorRemake\populator;

use GeneratorRemake\object\Object;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;


class WellPopulator extends Populator
{
    /** @var ChunkManager */
    protected $level;


    /**
     * Populates the chunk
     *
     * @param ChunkManager $level
     * @param int $chunkX
     * @param int $chunkZ
     * @param Random $random
     * @return void
     */
    public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        $this->level = $level;
        $well = new Well ();
        $x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
        $z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
        $y = $this->getHighestWorkableBlock($x, $z) - 1;
        //if ($well->canPlaceObject($level, $x, $y, $z, $random))
        $well->placeObject($level, $x, $y, $z, $random);
    }

    /**
     * @param $x
     * @param $z
     * @return int
     */
    protected function getHighestWorkableBlock($x, $z)
    {
        for ($y = 127; $y > 0; --$y) {
            $b = $this->level->getBlockIdAt($x, $y, $z);
            if ($b === Block::SAND) {
                break;
            }
        }

        return ++$y;
    }
}

class Well extends Object
{
    public $overridable = [
        Block::AIR => true,
        6 => true,
        17 => true,
        18 => true,
        Block::DANDELION => true,
        Block::POPPY => true,
        Block::SNOW_LAYER => true,
        Block::LOG2 => true,
        Block::LEAVES2 => true,
        Block::CACTUS => true
    ];
    /** @var ChunkManager */
    protected $level;
    protected $directions = [
        [
            1,
            1
        ],
        [
            1,
            -1
        ],
        [
            -1,
            -1
        ],
        [
            -1,
            1
        ]
    ];

    /**
     * Checks if a Well is placable
     *
     * @param ChunkManager $level
     * @param int $x
     * @param int $y
     * @param int $z
     * @param Random $random
     * @return bool
     */
    public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random)
    {
        $this->level = $level;
        for ($xx = $x - 2; $xx <= $x + 2; $xx++)
            for ($yy = $y; $yy <= $y + 3; $yy++)
                for ($zz = $z - 2; $zz <= $z + 2; $zz++)
                    if (!isset($this->overridable[$level->getBlockIdAt($xx, $yy, $zz)]))
                        return false;
        return true;
    }

    /**
     * Places a well
     *
     * @param ChunkManager $level
     * @param int $x
     * @param int $y
     * @param int $z
     * @param Random $random
     * @return void
     */
    public function placeObject(ChunkManager $level, $x, $y, $z, Random $random)
    {
        if ($random->nextRange(0, 50) > 5)
            return;
        $this->level = $level;
        foreach ($this->directions as $direction) {
            // Building pillars
            for ($yy = $y; $yy < $y + 3; $yy++)
                $this->placeBlock($x + $direction [0], $yy, $z + $direction [1], Block::SANDSTONE);

            // Building corners
            $this->placeBlock($x + ($direction [0] * 2), $y, $z + $direction [1], Block::SANDSTONE);
            $this->placeBlock($x + $direction [0], $y, $z + ($direction [1] * 2), Block::SANDSTONE);
            $this->placeBlock($x + ($direction [0] * 2), $y, $z + ($direction [1] * 2), Block::SANDSTONE);

            // Building slabs on the sides. Places two times due to all directions.
            $this->placeBlock($x + ($direction [0] * 2), $y, $z, 44, 1);
            $this->placeBlock($x, $y, $z + ($direction [1] * 2), 44, 1);

            // Placing water.Places two times due to all directions.
            $this->placeBlock($x + $direction [0], $y, $z, Block::WATER);
            $this->placeBlock($x, $y, $z + $direction [1], Block::WATER);
        }

        // Final things
        for ($xx = $x - 1; $xx <= $x + 1; $xx++)
            for ($zz = $z - 1; $zz <= $z + 1; $zz++)
                $this->placeBlock($xx, $y + 3, $zz, 44, 1);
        $this->placeBlock($x, $y + 3, $z, Block::SANDSTONE, 1);
        $this->placeBlock($x, $y, $z, Block::WATER);
    }

    /**
     * Places a block
     *
     * @param int $x
     * @param int $y
     * @param int $z
     * @param int $id
     * @param int $meta
     * @return void
     */
    public function placeBlock($x, $y, $z, $id = 0, $meta = 0)
    {
        $this->level->setBlockIdAt($x, $y, $z, $id);
        $this->level->setBlockDataAt($x, $y, $z, $meta);
    }
}