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

namespace GeneratorRemake\normal;

use GeneratorRemake\normal\biome\cold\ExtremeHillsBiome;
use GeneratorRemake\normal\biome\cold\MegaSpruceTaigaBiome;
use GeneratorRemake\normal\biome\cold\MegaTaigaBiome;
use GeneratorRemake\normal\biome\FreezedBiome;
use GeneratorRemake\normal\biome\SnowyBiome;
use GeneratorRemake\normal\populator\GroundCover;
use GeneratorRemake\normal\populator\Mineshaft;
use GeneratorRemake\object\OreType;
use GeneratorRemake\populator\Cave;
use GeneratorRemake\populator\DungeonPopulator as Dungeons;
use GeneratorRemake\populator\Ore;
use GeneratorRemake\populator\Populator;
use GeneratorRemake\populator\RavinePopulator as Ravine;
use GeneratorRemake\selector\Biome;
use GeneratorRemake\selector\BiomeSelector;
use pocketmine\block\Block;
use pocketmine\block\CoalOre;
use pocketmine\block\DiamondOre;
use pocketmine\block\Dirt;
use pocketmine\block\GoldOre;
use pocketmine\block\Gravel;
use pocketmine\block\IronOre;
use pocketmine\block\LapisOre;
use pocketmine\block\RedstoneOre;
use pocketmine\block\Stone;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\FullChunk;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\level\Level;
use pocketmine\utils\Config;
use pocketmine\utils\Random;

class Normal extends Generator
{

    private static $GAUSSIAN_KERNEL = null;
    private static $SMOOTH_SIZE = 2;
    /** @var Populator[] */
    protected $populators = [];
    /** @var ChunkManager */
    protected $level;
    /** @var Random */
    protected $random;
    protected $waterHeight = 62;
    protected $bedrockDepth = 5;
    /** @var Populator[] */
    protected $generationPopulators = [];
    /** @var Simplex */
    protected $noiseBase;
    /** @var BiomeSelector */
    protected $selector;
    /** @var Simplex */
    private $noiseSeaFloor;
    /** @var Simplex */
    private $noiseLand;
    /** @var Simplex */
    private $noiseMountains;
    /** @var Simplex */
    private $noiseBaseGround;
    /** @var Simplex */
    private $noiseRiver;
    /** @var Simplex */
    private $noiseOcean;
    /** @var Simplex */
    private $mountainChance;
    private $heightOffset;
    private $seaHeight = 62;
    private $seaFloorHeight = 48;
    private $beathStartHeight = 60;
    private $beathStopHeight = 64; // 36 / 2
    private $seaFloorGenerateRange = 5;
    private $landHeightRange = 12;
    private $mountainHeight = 4;
    private $basegroundHeight = 3;

    public function __construct(array $options = [])
    {
        if (self::$GAUSSIAN_KERNEL === null) {
            self::generateKernel();
        }
    }

    private static function generateKernel()
    {
        self::$GAUSSIAN_KERNEL = [];

        $bellSize = 1 / self::$SMOOTH_SIZE;
        $bellHeight = 2 * self::$SMOOTH_SIZE;

        for ($sx = -self::$SMOOTH_SIZE; $sx <= self::$SMOOTH_SIZE; ++$sx) {
            self::$GAUSSIAN_KERNEL[$sx + self::$SMOOTH_SIZE] = [];

            for ($sz = -self::$SMOOTH_SIZE; $sz <= self::$SMOOTH_SIZE; ++$sz) {
                $bx = $bellSize * $sx;
                $bz = $bellSize * $sz;
                self::$GAUSSIAN_KERNEL[$sx + self::$SMOOTH_SIZE][$sz + self::$SMOOTH_SIZE] = $bellHeight * exp(-($bx * $bx + $bz * $bz) / 2);
            }
        }
    }

    public static function getGaussianKernel(): array
    {
        return self::$GAUSSIAN_KERNEL;
    }

    public static function getSmoothSize(): int
    {
        return self::$SMOOTH_SIZE;
    }

    public static function getDimension()
    {
        return 0;
    }

    public static function getSeaHeight(): int
    {
        return 62;
    }

    public function getName()
    {
        return "vanilla";
    }

    public function getSettings()
    {
        return [];
    }

    public function getSelector(): BiomeSelector
    {
        return $this->selector;
    }

    /** @var Populator[] */
    private $structures = [];

    public function init(ChunkManager $level, Random $random, int $id = -1)
    {
        /**
         * Generation 2.0 initial
         */

        $this->level = $level;
        $this->random = $random;
        $this->config = new Config(getcwd()."BiomeIds.yml", Config::YAML);
        $this->random->setSeed($this->level->getSeed());
        $this->noiseSeaFloor = new Simplex($this->random, 1, 1 / 8, 1 / 64);
        $this->noiseLand = new Simplex($this->random, 2, 1 / 8, 1 / 512);
        $this->noiseMountains = new Simplex($this->random, 4, 1, 1 / 500);
        $this->noiseBaseGround = new Simplex($this->random, 4, 1 / 4, 1 / 64);
        $this->noiseRiver = new Simplex($this->random, 2, 1, 1 / 512);
        $this->noiseOcean = new Simplex($random, 2, 1 / 16, 1 / 2048);
        $this->mountainChance = new Simplex($random, 2, 1 / 16, 1 / 128);
        $this->random->setSeed($this->level->getSeed());
        Biome::init();
        $this->selector = new BiomeSelector($this->random, Biome::getBiome(Biome::OCEAN));

        $this->heightOffset = $random->nextRange(-5, 3);

        $this->selector->recalculate();

        $cave = new Cave();
        $this->populators[] = $cave;

        $dungeons = new Dungeons();
        $dungeons->setBaseAmount(0);
        $dungeons->setRandomAmount(20);

        $ravines = new Ravine();
        $ravines->setBaseAmount(0);
        $ravines->setRandomAmount(1);

        $mineshaft = new Mineshaft();
        $mineshaft->setBaseAmount(0);
        $mineshaft->setRandomAmount(102);

        $cover = new GroundCover();
        $this->generationPopulators[] = $cover;

        $this->populators[] = $ravines;
        $this->populators[] = $dungeons;

        $this->structures[] = $mineshaft;

        $ores = new Ore();
        $ores->setOreTypes([
            new OreType(new CoalOre(), 20, 17, 0, 128),
            new OreType(new IronOre(), 20, 9, 0, 64),
            new OreType(new RedstoneOre(), 8, 8, 0, 16),
            new OreType(new LapisOre(), 3, 7, 0, 16),
            new OreType(new GoldOre(), 5, 9, 0, 32),
            new OreType(new DiamondOre(), 3, 8, 0, 16),
            new OreType(new Dirt(), 20, 33, 0, 128),
            new OreType(new Gravel(), 20, 33, 0, 128),
            new OreType(new Stone(Stone::GRANITE), 10, 33, 0, 80),
            new OreType(new Stone(Stone::DIORITE), 10, 33, 0, 80),
            new OreType(new Stone(Stone::ANDESITE), 10, 33, 0, 80)
        ]);
        $this->generationPopulators[] = $ores;
    }

    public function generateOcean($x, $z)
    {
        $hash = $x * 2345803 ^ $z * 9236449 ^ $this->level->getSeed();
        $hash *= $hash + 223;
        $xNoise = $hash >> 20 & 3;
        $zNoise = $hash >> 22 & 3;
        $oceanNoise = ($this->noiseOcean->noise2D($x+$xNoise-1, $z+$zNoise-1, true) + 1) / 2;
        $oceanNoise = (int)($oceanNoise * 2048);
        return $oceanNoise / 2048;
    }

    public function generateChunk($chunkX, $chunkZ)
    {
        $this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
        $seaFloorNoise = Generator::getFastNoise2D($this->noiseSeaFloor, 16, 16, 4, $chunkX * 16, 0, $chunkZ * 16);
        $landNoise = Generator::getFastNoise2D($this->noiseLand, 16, 16, 4, $chunkX * 16, 0, $chunkZ * 16);
        $mountainNoise = Generator::getFastNoise2D($this->noiseMountains, 16, 16, 4, $chunkX * 16, 0, $chunkZ * 16);
        $baseNoise = Generator::getFastNoise2D($this->noiseBaseGround, 16, 16, 4, $chunkX * 16, 0, $chunkZ * 16);
        $riverNoise = Generator::getFastNoise2D($this->noiseRiver, 16, 16, 4, $chunkX * 16, 0, $chunkZ * 16);

        /** @var FullChunk $chunk */
        $chunk = $this->level->getChunk($chunkX, $chunkZ);

        for ($genx = 0; $genx < 16; $genx++) {
            for ($genz = 0; $genz < 16; $genz++) {
                $canBaseGround = false;
                $canRiver = true;

                $baseGroundHeight = $this->beathStopHeight+1;

                //using a quadratic function which smooth the world
                //y = (2.956x)^2 - 0.6,  (0 <= x <= 2)
                $landHeightNoise = $landNoise[$genx][$genz];
                $landHeightNoise *= 2.956;
                $landHeightNoise = $landHeightNoise - 0.6;
                $landHeightNoise = $landHeightNoise > 0 ? $landHeightNoise : 0;

                // generate some standard mountains
                $mountainHeightGenerate = $mountainNoise[$genx][$genz] - 0.2;
                $mountainHeightGenerate = $mountainHeightGenerate > 0 ? $mountainHeightGenerate : 0;
                $mountainGenerate = (int)($this->mountainHeight * $mountainHeightGenerate);

                $baseGroundHeight += $mountainGenerate;

                //prepare for generate ocean, desert, and land
                if ($baseGroundHeight < $this->beathStartHeight) {
                    if ($baseGroundHeight < $this->beathStartHeight - 5) {
                        $baseGroundHeight += $seaFloor = (int)($this->seaFloorGenerateRange * $seaFloorNoise[$genx][$genz]);
                    }
                    $biome = Biome::getBiome(Biome::OCEAN);
                    if ($baseGroundHeight < $this->seaFloorHeight - $this->seaFloorGenerateRange) {
                        $baseGroundHeight = $this->seaFloorHeight;
                    }
                    $canRiver = false;
                } else {
                    $biome = $this->pickBiome($chunkX * 16 + $genx, $chunkZ * 16 + $genz);
                    if ($canBaseGround) {
                        $baseGroundHeight = (int)($this->landHeightRange * $landHeightNoise) - $this->landHeightRange;
                        $baseGroundHeight2 = (int)($this->basegroundHeight * ($baseNoise[$genx][$genz]));
                        if ($baseGroundHeight2 > $baseGroundHeight) $baseGroundHeight2 = $baseGroundHeight;
                        if ($baseGroundHeight2 > $mountainGenerate)
                            $baseGroundHeight2 += $mountainGenerate;
                        else $baseGroundHeight2 = 0;
                        $baseGroundHeight += $baseGroundHeight2;
                    }
                }
                if ($canRiver && $baseGroundHeight <= $this->seaHeight - 5) {
                    $canRiver = false;
                }
                //generate river
                if ($canRiver) {
                    $riverGenerate = $riverNoise[$genx][$genz];
                    if ($riverGenerate > -0.25 && $riverGenerate < 0.25) {
                        $riverGenerate = $riverGenerate > 0 ? $riverGenerate : -$riverGenerate;
                        $riverGenerate = 0.25 - $riverGenerate;
                        $riverGenerate = $riverGenerate * $riverGenerate * 4;
                        $riverGenerate = $riverGenerate - 0.0000001;
                        $riverGenerate = $riverGenerate > 0 ? $riverGenerate : 0;
                        $baseGroundHeight -= $riverGenerate * 64;
                        if ($baseGroundHeight < $this->seaHeight) {
                            if ($biome instanceof FreezedBiome)
                                $biome = Biome::getBiome(Biome::FROZEN_RIVER);
                            else
                                $biome = Biome::getBiome(Biome::RIVER);
                            //to generate river floor
                            if ($baseGroundHeight <= $this->seaHeight - 8) {
                                $genyHeight1 = $this->seaHeight - 9 + (int)($this->basegroundHeight * ($baseNoise[$genx][$genz]));
                                $genyHeight2 = $baseGroundHeight < $this->seaHeight - 7 ? $this->seaHeight - 7 : $baseGroundHeight;
                                $baseGroundHeight = $genyHeight1 > $genyHeight2 ? $genyHeight1 : $genyHeight2;
                            }
                        }
                    }
                }
                $chunk->setBiomeId($genx, $genz, $biome->getId());
                //generating
                $generateHeight = $baseGroundHeight > $this->seaHeight ? (int)$baseGroundHeight : $this->seaHeight;
                for ($geny = 0; $geny <= $generateHeight; $geny++) {
                    if($geny <= $this->beathStopHeight && $geny >= $this->beathStartHeight)
                        $biome =
                            Biome::getBiome(
                                $biome instanceof FreezedBiome
                                ||
                                $biome instanceof SnowyBiome
                                    ?
                                    Biome::SNOWY_BEACH
                                    :
                                    (
                                    $biome instanceof ExtremeHillsBiome
                                    ||
                                    $biome instanceof MegaTaigaBiome
                                    ||
                                    $biome instanceof MegaSpruceTaigaBiome
                                        ?
                                        Biome::STONE_BEACH
                                        :
                                        Biome::BEACH
                                    )
                            );
                    if ($geny <= $this->bedrockDepth && ($geny == 0 or $this->random->nextRange(1, 5) == 1)) {
                        $chunk->setBlockId($genx, $geny, $genz, Block::BEDROCK);
                    } elseif ($geny > $baseGroundHeight) {
                        if ($biome instanceof FreezedBiome and $geny == $this->seaHeight) {
                            $chunk->setBlockId($genx, $geny, $genz, Block::ICE);
                        } else {
                            $chunk->setBlockId($genx, $geny, $genz, Block::STILL_WATER);
                        }
                    } else {
                        $chunk->setBlockId($genx, $geny, $genz, Block::STONE);
                    }
                }
            }
        }
        foreach ($this->generationPopulators as $populator) {
            $populator->populate($this->level, $chunkX, $chunkZ, $this->random);
        }
    }

    private function pickBiome(int $x, int $z): Biome
    {
        $hash = $x * 2345803 ^ $z * 9236449 ^ $this->level->getSeed();
        $hash *= $hash + 223;
        $xNoise = $hash >> 20 & 3;
        $zNoise = $hash >> 22 & 3;
        if ($xNoise == 3) {
            $xNoise = 1;
        }
        if ($zNoise == 3) {
            $zNoise = 1;
        }
        $b = $this->selector->pickBiome($x + $xNoise - 1, $z + $zNoise - 1);
        return Biome::getBiome($b);
    }

    public function populateChunk($chunkX, $chunkZ)
    {
        $this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
        /** @var Populator $populator */
        foreach ($this->populators as $populator) {
            $populator->populate($this->level, $chunkX, $chunkZ, $this->random);
        }
        /** @var Populator $str */
        foreach($this->structures as $str)
            $str->populate($this->level, $chunkX, $chunkZ, $this->random);
        /** @var FullChunk $chunk */
        $chunk = $this->level->getChunk($chunkX, $chunkZ);
        $biome = Biome::getBiome($chunk->getBiomeId(7, 7));
        $biome->populateChunk($this->level, $chunkX, $chunkZ, $this->random);
        $chunk->setPopulated();
    }

    public function getSpawn()
    {
        return $this->level->getSpawnLocation();
    }
}