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

namespace GeneratorRemake\selector;

use GeneratorRemake\noise\Simplex;
use pocketmine\utils\Random;
use SplFixedArray;

class BiomeSelector
{

    /** @var int */
    private $fallback;

    /** @var Simplex */
    private $temperature;
    /** @var Simplex */
    private $rainfall;
    private $map = null;


    public function __construct(Random $random, Biome $fallback)
    {
        $this->fallback = $fallback->getId();
        $this->temperature = new Simplex($random, 2, 1 / 16, 1 / 2048);
        $this->rainfall = new Simplex($random, 2, 1 / 16, 1 / 2048);
    }

    public function recalculate()
    {
        $this->map = new SplFixedArray(64 * 64);
        for ($i = 0; $i < 64; ++$i) {
            for ($j = 0; $j < 64; ++$j) {
                $biome = $this->lookup($i / 63, $j / 63, (($i + $j) / 63) * 0.5);
                $this->map[$i + ($j << 6)] = $biome;
            }
        }
    }

    public function lookup($rain, $temp, $hills)
    {
        if ($temp < 0.20) {
            if ($temp < 0.05) {
                return Biome::SNOWY_TUNDRA;
            } elseif ($temp < 0.1) {
                return Biome::ICE_SPIKES;
            } elseif ($temp < 0.15) {
                if ($hills < 0.5)
                    return Biome::SNOWY_TAIGA;
                else
                    return Biome::SNOWY_TAIGA_MOUNTAINS;
            } else {
                return Biome::FROZEN_RIVER;
            }
            //cold
        } elseif ($temp < 0.3) {
            if ($temp < 0.25) {
                if ($hills < 0.25)
                    return Biome::EXTREME_HILLS;
                elseif ($hills < 0.5)
                    return Biome::EXTREME_HILLS_MOUNTAINS;
                elseif ($hills < 0.75)
                    return Biome::EXTREME_HILLS_PLUS;
                else
                    return Biome::EXTREME_HILLS_PLUS_MOUNTAINS;
            } else {
                if ($hills < 0.25)
                    return Biome::TAIGA;
                elseif ($hills < 0.5)
                    return Biome::TAIGA_MOUNTAINS;
                elseif ($hills < 0.75)
                    return Biome::MEGA_TAIGA;
                else
                    return Biome::MEGA_SPRUCE_TAIGA;
            }
            //normal
        } elseif ($temp < 0.7) {
            if ($temp < 0.35) {
                if ($hills < 0.5)
                    return Biome::PLAINS;
                else
                    return Biome::PLAINS_MOUNTAINS;
            } elseif ($temp < 0.4)
                return Biome::SUNFLOWER_PLAINS;
            elseif ($temp < 0.45)
                return Biome::FOREST;
            elseif ($temp < 0.5)
                if ($hills < 0.5)
                    return Biome::BIRCH_FOREST;
                else
                    return Biome::BIRCH_FOREST_MOUNTAINS;
            elseif ($temp < 0.55)
                return Biome::FLOWER_FOREST;
            elseif ($temp < 0.6)
                if ($hills < 0.5)
                    return Biome::SWAMP;
                else
                    return Biome::SWAMP_MOUNTAINS;
            elseif ($temp < 0.65)
                if ($hills < 0.5)
                    return Biome::JUNGLE;
                else
                    return Biome::JUNGLE_MOUNTAINS;
            else
                if ($hills < 0.5)
                    return Biome::MUSHROOM_ISLAND_SHORE;
                else
                    return Biome::MUSHROOM_ISLAND;
            //hot
        } else {
            if ($temp < 0.80) {
                if ($hills < 0.5)
                    return Biome::SAVANNA;
                else
                    return Biome::SAVANNA_MOUNTAINS;
            } elseif ($temp < 0.90) {
                if ($hills < 0.25)
                    return Biome::MESA;
                elseif ($hills < 0.5)
                    return Biome::MESA_PLATEAU_F;
                elseif ($hills < 0.75)
                    return Biome::MESA_PLATEAU_F_MOUNTAINS;
                else
                    return Biome::MESA_BRYCE;
            } else {
                if ($hills < 0.5)
                    return Biome::DESERT;
                else
                    return Biome::DESERT_MOUNTAINS;
            }
        }
    }

    public function pickBiome($x, $z): int
    {
        $temperature = (int)($this->getTemperature($x, $z) * 63);
        $rain = (int)($this->getRainfall($x, $z) * 63);
        $b = $this->map[$temperature + ($rain << 6)];
        return $b;
    }

    public function getTemperature($x, $z)
    {
        return ($this->temperature->noise2D($x, $z, true) + 1) / 2;
    }

    public function getRainfall($x, $z)
    {
        return ($this->rainfall->noise2D($x, $z, true) + 1) / 2;
    }

}