<?php


namespace GeneratorRemake\object\structures;

use muqsit\vanillagenerator\generator\structures\utils\LootFiller;
use pocketmine\block\Block;
use pocketmine\block\StoneBricks;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Fortress
{
    /** @var Random */
    private static $random;
    private static $instance;

    public function placeObject(ChunkManager $level, $x, $y, $z, Random $random)
    {
        self::$instance = $this;
        self::$random = $random;
        $this->level = $level;
        self::generateFortress($level, $x, $y, $z);
    }

    public static function randomBlockMeta(): int
    {
        return !self::$random->nextRange(0, 2) ? (self::$random->nextBoolean() ? StoneBricks::MOSSY : StoneBricks::CRACKED) : StoneBricks::NORMAL;
    }

    public static function spawnCorridors($x, $y, $z)
    {
        list($xx, $yy, $zz) = [$x, $y, $z];
        for($x = 0; $x < 10;$x++)
        {
            for($z = 0; $z < 10; $z++)
            {
                for($y = 0; $y < 7; $y++)
                {
                    
                }
            }
        }
    }

    public static function generateFortress(ChunkManager $level, $x, $y, $z): Vector3
    {
        /** @var Fortress|ChunkManager $level */
        $level = self::getInstance();
        //cleaning
        for ($yy = 0; $yy < 6; $yy++)
            for ($xx = 0; $xx < 11; $xx++)
                for ($zz = 0; $zz < 16; $zz++)
                    $level->setBlockIdAt($xx + $x, $y + $yy, $zz + $z, Block::AIR);

        //top
        for ($xx = 0; $xx < 11; $xx++)
            for ($zz = 0; $zz < 16; $zz++)
                self::placeBricks($level, $xx + $x, $y + 6, $zz + $z);

        list($xx, $yy, $zz) = [$x, $y, $z];

        //floor
        for ($x = 0; $x < 11; $x++) {
            for ($z = 0; $z < 16; $z++) {
                $level->setBlockIdAt($xx + $x, $yy, $zz + $z, Block::STONE_BRICK);
                $level->setBlockDataAt($xx + $x, $yy, $zz + $z, self::randomBlockMeta());
            }
        }

        //wall top
        for ($y = 0; $y < 6; $y++) {
            for ($x = 0; $x < 11; $x++) {
                if ($x != 0 && $x % 2 == 0 && $x != 10 && $y != 5 && $y > 2) {
                    $level->setBlockIdAt($xx + $x, $yy + $y, $zz, Block::IRON_BAR);
                } else {
                    $level->setBlockIdAt($xx + $x, $yy + $y, $zz, Block::STONE_BRICK);
                    $level->setBlockDataAt($xx + $x, $y + $yy, $zz, self::randomBlockMeta());
                }
            }
        }

        //wall parade
        for ($y = 0; $y < 6; $y++) {
            for ($x = 0; $x < 11; $x++) {
                if ($x > 3 && $x < 7 && $y != 0 && $y < 4) {
                    //BARS  OR AIR
                    if ($x == 4 || $x == 6) {
                        $level->setBlockIdAt($xx + $x, $yy + $y, $zz + 15, Block::IRON_BAR);
                    } else {
                        if ($x == 5 && $y == 3)
                            $level->setBlockIdAt($xx + $x, $yy + $y, $zz + 15, Block::IRON_BAR);
                        else
                            $level->setBlockIdAt($xx + $x, $yy + $y, $zz + 15, Block::AIR);
                    }
                } else {
                    $level->setBlockIdAt($xx + $x, $yy + $y, $zz + 15, Block::STONE_BRICK);
                    $level->setBlockDataAt($xx + $x, $y + $yy, $zz + 15, self::randomBlockMeta());
                }
            }
        }

        //wall right
        for ($y = 0; $y < 6; $y++) {
            for ($z = 0; $z < 16; $z++) {
                if ($z != 0 && $z % 2 == 0 && $z != 14 && $y > 2 && $y != 5) {
                    $level->setBlockIdAt($xx, $yy + $y, $zz + $z, Block::IRON_BAR);
                } else {
                    $level->setBlockIdAt($xx, $yy + $y, $zz + $z, Block::STONE_BRICK);
                    $level->setBlockDataAt($xx, $y + $yy, $zz + $z, self::randomBlockMeta());
                }
            }
        }

        //wall left
        for ($y = 0; $y < 6; $y++) {
            for ($z = 0; $z < 16; $z++) {
                if ($z != 0 && $z % 2 == 0 && $z != 14 && $y > 2 && $y != 5) {
                    $level->setBlockIdAt($xx + 10, $yy + $y, $zz + $z, Block::IRON_BAR);
                } else {
                    $level->setBlockIdAt($xx + 10, $yy + $y, $zz + $z, Block::STONE_BRICK);
                    $level->setBlockDataAt($xx + 10, $y + $yy, $zz + $z, self::randomBlockMeta());
                }
            }
        }

        //lava 1
        list($x, $y, $z) = [$xx + 1, $yy + 1, $zz + 14];
        $level->setBlockIdAt($x, $y, $z, Block::STILL_LAVA);
        $level->setBlockIdAt($x, $y, $z - 1, Block::STILL_LAVA);

        $level->setBlockIdAt($x, $y, $z - 2, Block::STONE_BRICK);
        $level->setBlockDataAt($x, $y, $z - 2, self::randomBlockMeta());
        $level->setBlockIdAt($x + 1, $y, $z - 2, Block::STONE_BRICK);
        $level->setBlockDataAt($x + 1, $y, $z - 2, self::randomBlockMeta());
        $level->setBlockIdAt($x + 1, $y, $z - 1, Block::STONE_BRICK);
        $level->setBlockDataAt($x + 1, $y, $z - 1, self::randomBlockMeta());
        $level->setBlockIdAt($x + 1, $y, $z, Block::STONE_BRICK);
        $level->setBlockDataAt($x + 1, $y, $z, self::randomBlockMeta());

        //lava 2
        list($x, $y, $z) = [$xx + 9, $yy + 1, $zz + 14];
        $level->setBlockIdAt($x, $y, $z, Block::STILL_LAVA);
        $level->setBlockIdAt($x, $y, $z - 1, Block::STILL_LAVA);

        $level->setBlockIdAt($x, $y, $z - 2, Block::STONE_BRICK);
        $level->setBlockDataAt($x, $y, $z - 2, self::randomBlockMeta());
        $level->setBlockIdAt($x - 1, $y, $z - 2, Block::STONE_BRICK);
        $level->setBlockDataAt($x - 1, $y, $z - 2, self::randomBlockMeta());
        $level->setBlockIdAt($x - 1, $y, $z - 1, Block::STONE_BRICK);
        $level->setBlockDataAt($x - 1, $y, $z - 1, self::randomBlockMeta());
        $level->setBlockIdAt($x - 1, $y, $z, Block::STONE_BRICK);
        $level->setBlockDataAt($x - 1, $y, $z, self::randomBlockMeta());

        //lava under portal
        list($x, $z) = [$xx + 3, $zz + 3];
        for ($lx = 0; $lx < 5; $lx++)
            for ($lz = 0; $lz < 5; $lz++) {
                $level->setBlockIdAt($x + $lx, $y, $z + $lz, Block::STONE_BRICK);
                $level->setBlockDataAt($x + $lx, $y, $z + $lz, self::randomBlockMeta());
            }

        $x++;
        $z++;

        //lava
        for ($lx = 0; $lx < 3; $lx++)
            for ($lz = 0; $lz < 3; $lz++) {
                $level->setBlockIdAt($x + $lx, $y, $z + $lz, Block::STILL_LAVA);
            }

        //face of stairs = 1

        $z += 4;//stairs block start;
        for ($sy = 0; $sy < 3; $sy++)//y
            for ($sx = 0; $sx < 3; $sx++) {//x
                $level->setBlockIdAt($x + $sx, $y + $sy, $z, Block::STONE_BRICK);
                $level->setBlockDataAt($x + $sx, $y + $sy, $z, self::randomBlockMeta());
            }
        $z++;//next stairs
        for ($sy = 0; $sy < 2; $sy++)//y
            for ($sx = 0; $sx < 3; $sx++) {//x
                $level->setBlockIdAt($x + $sx, $y + $sy, $z, Block::STONE_BRICK);
                $level->setBlockDataAt($x + $sx, $y + $sy, $z, self::randomBlockMeta());
            }
        $z++;//end of stairs
        for ($sx = 0; $sx < 3; $sx++) {//x
            $level->setBlockIdAt($x + $sx, $y, $z, Block::STONE_BRICK);
            $level->setBlockDataAt($x + $sx, $y, $z, self::randomBlockMeta());
        }

        $z++;//starting stairs setting
        for ($sx = 0; $sx < 3; $sx++) {//x
            $level->setBlockIdAt($x + $sx, $y, $z, Block::STONE_BRICK_STAIRS);
            $level->setBlockDataAt($x + $sx, $y, $z, 3);
        }
        $z--;
        for ($sx = 0; $sx < 3; $sx++) {//x
            $level->setBlockIdAt($x + $sx, $y + 1, $z, Block::STONE_BRICK_STAIRS);
            $level->setBlockDataAt($x + $sx, $y + 1, $z, 3);
        }
        $z--;
        for ($sx = 0; $sx < 3; $sx++) {//x
            $level->setBlockIdAt($x + $sx, $y + 2, $z, Block::STONE_BRICK_STAIRS);
            $level->setBlockDataAt($x + $sx, $y + 2, $z, 3);
        }

        $z--;
        $level->setBlockIdAt($x + 1, $y + 2, $z - 1, Block::MONSTER_SPAWNER);
        LootFiller::createSpawnerData($level, $x + 1, $y + 2, $z - 1, 39);

        $x = $xx + 3;
        $z = $zz + 3;
        $y += 2;
        for ($_x = 0; $_x < 5; $_x++)
            for ($_z = 0; $_z < 5; $_z++)
                if ($_z != 4 && $_x != 4 || $_z != 0 && $_x != 0 || $_z != 0 && $_x != 4 || $_z != 4 && $_x != 0)
                    if (self::$random->nextRange(0, 10))
                        $level->setBlockIdAt($x + $_x, $y, $z + $_z, Block::END_PORTAL_FRAME);
                    else {
                        $level->setBlockIdAt($x + $_x, $y, $z + $_z, Block::END_PORTAL_FRAME);
                        $level->setBlockDataAt($x + $_x, $y, $z + $_z, 1);
                    }
        $x++;
        $z++;

        for ($_x = 0; $_x < 3; $_x++)
            for ($_z = 0; $_z < 3; $_z++)
                if ($_z != 4 && $_x != 4 || $_z != 0 && $_x != 0 || $_z != 0 && $_x != 4 || $_z != 4 && $_x != 0)
                    $level->setBlockIdAt($x + $_x, $y, $z + $_z, Block::AIR);

        $x--;
        $z--;
        $level->setBlockIdAt($x, $y, $z, Block::AIR);
        $level->setBlockIdAt($x + 4, $y, $z, Block::AIR);
        $level->setBlockIdAt($x + 4, $y, $z + 4, Block::AIR);
        $level->setBlockIdAt($x, $y, $z + 4, Block::AIR);

        //completed portal floor generation

        //starting generating fountain

        /** @end_portal_generation */

        /** @start_fountain_generation */

        list($xx, $yy, $zz) = [$xx, $yy, $zz + 16];

        //cleaning
        for ($x = 0; $x < 11; $x++) {
            for ($z = 0; $z < 10; $z++) {
                for ($y = 0; $y < 6; $y++) {
                    $level->setBlockIdAt($xx + $x, $yy + $y, $zz + $z, Block::AIR);
                }
            }
        }

        //top

        for ($x = 0; $x < 11; $x++) {
            for ($z = 0; $z < 10; $z++) {
                self::placeBricks($level, $xx + $x, $yy + 6, $zz + $z);
            }
        }

        //paul
        for ($x = 0; $x < 11; $x++)
            for ($z = 0; $z < 9; $z++) {
                $level->setBlockIdAt($xx + $x, $yy, $zz + $z, Block::STONE_BRICK);
                $level->setBlockDataAt($xx + $x, $yy, $zz + $z, self::randomBlockMeta());
            }

        //setting walls

        //wall in z1
        for ($z = 0; $z < 10; $z++)
            for ($y = 0; $y < 6; $y++) {
                $level->setBlockIdAt($xx, $yy + $y, $zz + $z, Block::STONE_BRICK);
                $level->setBlockDataAt($xx, $yy + $y, $zz + $z, self::randomBlockMeta());
                if ($y != 0 && $y < 3) {
                    if ($z > 2 && $z < 6) {
                        $level->setBlockIdAt($xx, $yy + $y, $zz + $z, Block::IRON_BAR);
                        if ($z == 4)
                            $level->setBlockIdAt($xx, $yy + $y, $zz + $z, Block::AIR);
                    }
                }
            }

        //wall in z2
        for ($z = 0; $z < 10; $z++)
            for ($y = 0; $y < 6; $y++) {
                self::placeBricks($level, $xx + 10, $yy + $y, $zz + $z);
                if ($y != 0 && $y < 3) {
                    if ($z > 2 && $z < 6) {
                        $level->setBlockIdAt($xx + 10, $yy + $y, $zz + $z, Block::IRON_BAR);
                        if ($z == 4)
                            $level->setBlockIdAt($xx + 10, $yy + $y, $zz + $z, Block::AIR);
                    }
                }
            }

        //parade wall
        for ($y = 0; $y < 6; $y++) {
            for ($x = 0; $x < 11; $x++) {
                self::placeBricks($level, $xx + $x, $yy + $y, $zz + 9);
                if ($y != 0 && $y < 3) {
                    if ($x > 3 && $x < 7) {
                        $level->setBlockIdAt($xx + $x, $yy + $y, $zz + 9, Block::IRON_BAR);
                        if ($x == 5)
                            $level->setBlockIdAt($xx + $x, $yy + $y, $zz + 9, Block::AIR);
                    }
                }
            }
        }

        //fountain
        for ($x = 3; $x < 8; $x++) {
            for ($z = 2; $z < 7; $z++) {
                self::placeBricks($level, $xx + $x, $yy + 1, $zz + $z);
            }
        }

        for ($x = 4; $x < 7; $x++) {
            for ($z = 3; $z < 6; $z++) {
                $level->setBlockIdAt($xx + $x, $yy + 1, $zz + $z, Block::STILL_WATER);
            }
        }

        //done
        for ($y = 0; $y < 4; $y++) {
            $level->setBlockIdAt($xx + 5, $yy + $y, $zz + 4, Block::STONE_BRICK);
        }

        //water at last
        $level->setBlockIdAt($xx + 5, $yy + 4, $zz + 4, Block::STILL_WATER);

        /**
         * @end_fountain_build
         */

        //DONE!

        /** @start_bibliary_generation */

        //сейчас идём по $x влево
        list($xx, $zz) = [$xx, $zz - 2];

        //очищаем территорию
        //13x13 blocks

        //bricks
        for ($x = 1; $x < 13; $x++) {
            for ($z = 0; $z < 13; $z++) {
                for ($y = 0; $y < 10; $y++) {
                    self::placeBricks($level, $xx - $x, $yy + $y, $zz + $z);
                }
            }
        }

        //air
        for ($x = 1; $x < 12; $x++) {
            for ($z = 1; $z < 12; $z++) {
                for ($y = 1; $y < 10; $y++) {
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + $z, Block::AIR);
                }
            }
        }

        //books 1 wall
        for ($x = 1; $x < 12; $x++)
            for ($y = 1; $y < 5; $y++) {
                if ($x == 1 || $x == 5 || $x == 9)
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + 1, Block::WOODEN_PLANKS);
                else
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + 1, Block::BOOKSHELF);
            }

        //books 2 wall
        for ($x = 1; $x < 12; $x++)
            for ($y = 1; $y < 5; $y++) {
                if ($x == 1 || $x == 5 || $x == 9)
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + 11, Block::WOODEN_PLANKS);
                else
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + 11, Block::BOOKSHELF);
            }

        //2 floor books
        //books 1 wall
        for ($x = 1; $x < 12; $x++)
            for ($y = 6; $y < 10; $y++) {
                if ($x == 1 || $x == 5 || $x == 9)
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + 1, Block::WOODEN_PLANKS);
                else
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + 1, Block::BOOKSHELF);
            }

        //books 2 wall
        for ($x = 1; $x < 12; $x++)
            for ($y = 6; $y < 10; $y++) {
                if ($x == 1 || $x == 5 || $x == 9)
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + 11, Block::WOODEN_PLANKS);
                else
                    $level->setBlockIdAt($xx - $x, $yy + $y, $zz + 11, Block::BOOKSHELF);
            }

        //now, i will set the bridges at floor 2

        for ($x = 1; $x < 12; $x++) {
            for ($z = 1; $z < 12; $z++) {
                $level->setBlockIdAt($xx - $x, $yy + 5, $zz + $z, Block::WOODEN_PLANKS);
            }
        }

        for ($x = 3; $x < 10; $x++) {
            for ($z = 4; $z < 9; $z++) {
                $level->setBlockIdAt($xx - $x, $yy + 5, $zz + $z, Block::AIR);
            }
        }

        //fences
        for ($x = 2; $x < 11; $x++) {
            for ($z = 3; $z < 10; $z++) {
                $level->setBlockIdAt($xx - $x, $yy + 6, $zz + $z, Block::FENCE);
            }
        }

        //fences clear
        for ($x = 3; $x < 10; $x++) {
            for ($z = 4; $z < 9; $z++) {
                $level->setBlockIdAt($xx - $x, $yy + 6, $zz + $z, Block::AIR);
            }
        }

        //setting central lamp
        $level->setBlockIdAt($xx - 7, $yy + 9, $zz + 7, Block::FENCE);
        $level->setBlockIdAt($xx - 7, $yy + 8, $zz + 7, Block::FENCE);
        $level->setBlockIdAt($xx - 7, $yy + 7, $zz + 7, Block::FENCE);
        $level->setBlockIdAt($xx - 7 + 1, $yy + 7, $zz + 7, Block::FENCE);
        $level->setBlockIdAt($xx - 7 + 1, $yy + 8, $zz + 7, Block::TORCH);
        $level->setBlockIdAt($xx - 7 - 1, $yy + 7, $zz + 7, Block::FENCE);
        $level->setBlockIdAt($xx - 7 - 1, $yy + 8, $zz + 7, Block::TORCH);
        $level->setBlockIdAt($xx - 7, $yy + 7, $zz + 7 + 1, Block::FENCE);
        $level->setBlockIdAt($xx - 7, $yy + 8, $zz + 7 + 1, Block::TORCH);
        $level->setBlockIdAt($xx - 7, $yy + 7, $zz + 7 - 1, Block::FENCE);
        $level->setBlockIdAt($xx - 7, $yy + 8, $zz + 7 - 1, Block::TORCH);


        //center books
        for ($x = 2; $x < 11; $x++)
            for ($z = 1; $z < 11; $z++) {
                for ($y = 1; $y < 4; $y++) {
                    if ($x % 2 == 0 && ($z > 2 && $z < 6 || $z > 6 && $z < 10))
                        $level->setBlockIdAt($xx - $x, $yy + $y, $zz + $z, Block::BOOKSHELF);
                    else
                        if (!self::$random->nextRange(0, 3))
                            $level->setBlockIdAt($xx - $x, $yy + $y + self::$random->nextRange(0, 1), $zz + $z, Block::COBWEB);
                }
            }

        //done
        //now, ladder
        for ($y = 1; $y < 8; $y++) {
            $level->setBlockIdAt($xx - 11, $yy + $y, $zz + 10, Block::LADDER);
            $level->setBlockDataAt($xx - 11, $yy + $y, $zz + 10, 5);
        }

        for ($x = 1; $x < 13; $x++) {
            for ($z = 0; $z < 13; $z++) {
                self::placeBricks($level, $xx - $x, $yy + 10, $zz + $z);
            }
        }

        /** @end_bibliary_building */

        self::spawnCorridors($xx+10, $yy, $zz+9);


        return new Vector3($xx, $yy, $zz + 15);
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    private static function placeBricks($level, $x, $y, $z)
    {
        /** @var ChunkManager $level */
        $level->setBlockIdAt($x, $y, $z, Block::STONE_BRICK);
        $level->setBlockDataAt($x, $y, $z, self::randomBlockMeta());
    }

}