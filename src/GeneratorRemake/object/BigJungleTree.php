<?php
/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace GeneratorRemake\object;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use pocketmine\utils\VectorIterator;

class BigJungleTree extends Tree
{

    private $height, $firstGroup, $secoundGroup;

    public function __construct()
    {
        $this->height = mt_rand(30, 40);
        $this->firstGroup = (int)($this->height * 0.6);
        $this->secoundGroup = (int)($this->height * 0.8);
    }

    public function placeObject(ChunkManager $level, $x, $y, $z, Random $random)
    {
        /**for($yy = $y; $yy <= $this->height; $yy++)
         * {
         * if($yy == $this->firstGroup)
         * {
         *
         * }
         * for($xx = $x; $xx <= $x+1; $xx)
         * {
         * for($zz = $z; $zz <= $z+1; $zz)
         * {
         * $level->setBlockIdAt($x, $y, $z, Block::LOG);
         * $level->setBlockIdAt($x, $y, $z, Wood::JUNGLE);
         * }
         * }
         * }*/
    }

}