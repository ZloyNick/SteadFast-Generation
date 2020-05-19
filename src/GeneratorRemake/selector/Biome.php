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

use archinheim\entity\EntityCow;
use archinheim\entity\EntityCreeper;
use archinheim\entity\EntityEnderman;
use archinheim\entity\EntitySkeleton;
use archinheim\entity\EntitySpider;
use archinheim\entity\EntityZombie;
use GeneratorRemake\normal\biome\cold\{ExtremeHillsBiome,
    ExtremeHillsMBiome,
    ExtremeHillsPlusBiome,
    ExtremeHillsPlusMBiome,
    MegaSpruceTaigaBiome,
    MegaTaigaBiome,
    StoneBeachBiome,
    TaigaBiome,
    TaigaMBiome};
use GeneratorRemake\ender\biome\EnderBiome;
use GeneratorRemake\nether\biome\HellBiome;
use GeneratorRemake\normal\biome\FreezedBiome;
use GeneratorRemake\normal\biome\hot\{DesertBiome,
    DesertMBiome,
    MesaBiome,
    MesaBryceBiome,
    MesaPlateauFBiome,
    MesaPlateuFMBiome,
    SavannaBiome,
    SavannaMBiome};
use GeneratorRemake\normal\biome\normal\{BeachBiome,
    FlowerForestBiome,
    ForestBiome,
    JungleBiome,
    JungleMBiome,
    MushroomIslandBiome,
    MushroomIslandShore,
    PlainsBiome,
    PlainsMBiome,
    RiverBiome,
    RoofedForestBiome,
    RoofedForestMBiome,
    SunflowerPlainsBiome,
    SwampBiome,
    SwampMBiome};
use GeneratorRemake\normal\biome\OceanBiome;
use GeneratorRemake\normal\biome\snowy\{FrozenRiverBiome,
    IcePlainsSpikesBiome,
    SnowyBeachBiome,
    SnowyTaigaBiome,
    SnowyTaigaMBiome,
    SnowyTundraBiome};
use GeneratorRemake\normal\biome\SnowyBiome;
use GeneratorRemake\populator\Populator;
use GeneratorRemake\populator\Pumpkin;
use pocketmine\block\Block;
use pocketmine\entity\animal\walking\EntityChicken;
use pocketmine\entity\animal\walking\Pig;
use pocketmine\entity\animal\walking\Sheep;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

abstract class Biome
{

    const OCEAN = 0;
    const PLAINS = 1;
    const DESERT = 2;
    const EXTREME_HILLS = 3;
    const FOREST = 4;
    const TAIGA = 5;
    const SWAMP = 6;
    const RIVER = 7;
    const HELL = 8;
    const END = 9;
    const FROZEN_RIVER = 11;
    const SNOWY_TUNDRA = 12;
    const MUSHROOM_ISLAND = 14;
    const MUSHROOM_ISLAND_SHORE = 15;
    const BEACH = 16;
    const JUNGLE = 21;
    const STONE_BEACH = 25;
    const SNOWY_BEACH = 26;
    const BIRCH_FOREST = 27;
    const ROOFED_FOREST = 29;
    const SNOWY_TAIGA = 30;
    const MEGA_TAIGA = 32;
    const EXTREME_HILLS_PLUS = 34;
    const SAVANNA = 35;
    const MESA = 37;
    const MESA_PLATEAU_F = 38;
    const PLAINS_MOUNTAINS = 128;
    const SUNFLOWER_PLAINS = 129;
    const DESERT_MOUNTAINS = 130;
    const EXTREME_HILLS_MOUNTAINS = 131;
    const FLOWER_FOREST = 132;
    const TAIGA_MOUNTAINS = 133;
    const SWAMP_MOUNTAINS = 134;
    const ICE_SPIKES = 140;
    const JUNGLE_MOUNTAINS = 149;
    const BIRCH_FOREST_MOUNTAINS = 155;
    const ROOFED_FOREST_MOUNTAINS = 157;
    const SNOWY_TAIGA_MOUNTAINS = 158;
    const MEGA_SPRUCE_TAIGA = 160;
    const EXTREME_HILLS_PLUS_MOUNTAINS = 162;
    const SAVANNA_MOUNTAINS = 163;
    const MESA_BRYCE = 165;
    const MESA_PLATEAU_F_MOUNTAINS = 166;


    const VOID = 127;

    const MAX_BIOMES = 256;

    /** @var Biome[] */
    public static $biomes = [];
    private static $mobsOverride = [];
    private static $monsters = [];
    protected $rainfall = 0.2;
    protected $temperature = 0.2;
    private $id;
    private $registered = false;
    /** @var Populator[] */
    private $populators = [];
    private $minElevation;
    private $maxElevation;
    private $groundCover = [];
    private $coverBlock = Block::AIR;
    private $coverBlockY = 0;
    private $coverBlockMeta = 0;

    public static function init()
    {
        //snowy
        //total 6
        self::register(self::FROZEN_RIVER, new FrozenRiverBiome());
        self::register(self::SNOWY_TUNDRA, new SnowyTundraBiome());
        self::register(self::SNOWY_BEACH, new SnowyBeachBiome());
        self::register(self::SNOWY_TAIGA, new SnowyTaigaBiome());
        self::register(self::ICE_SPIKES, new IcePlainsSpikesBiome());
        self::register(self::SNOWY_TAIGA_MOUNTAINS, new SnowyTaigaMBiome());

        //cold
        //total 9
        self::register(self::EXTREME_HILLS, new ExtremeHillsBiome());
        self::register(self::EXTREME_HILLS_MOUNTAINS, new ExtremeHillsMBiome());
        self::register(self::EXTREME_HILLS_PLUS, new ExtremeHillsPlusBiome());
        self::register(self::EXTREME_HILLS_PLUS_MOUNTAINS, new ExtremeHillsPlusMBiome());
        self::register(self::MEGA_SPRUCE_TAIGA, new MegaSpruceTaigaBiome());
        self::register(self::MEGA_TAIGA, new MegaTaigaBiome());
        self::register(self::STONE_BEACH, new StoneBeachBiome());
        self::register(self::TAIGA, new TaigaBiome());
        self::register(self::TAIGA_MOUNTAINS, new TaigaMBiome());

        //normal
        //total 17
        self::register(self::BEACH, new BeachBiome());
        self::register(self::BIRCH_FOREST, new ForestBiome(ForestBiome::TYPE_BIRCH));
        self::register(self::BIRCH_FOREST_MOUNTAINS, new ForestBiome(ForestBiome::TYPE_BIRCH_TALL));
        self::register(self::FLOWER_FOREST, new FlowerForestBiome());
        self::register(self::FOREST, new ForestBiome());
        self::register(self::JUNGLE, new JungleBiome());
        self::register(self::JUNGLE_MOUNTAINS, new JungleMBiome());
        self::register(self::MUSHROOM_ISLAND, new MushroomIslandBiome());
        self::register(self::MUSHROOM_ISLAND_SHORE, new MushroomIslandShore());
        self::register(self::PLAINS, new PlainsBiome());
        self::register(self::PLAINS_MOUNTAINS, new PlainsMBiome());
        self::register(self::RIVER, new RiverBiome());
        self::register(self::ROOFED_FOREST, new RoofedForestBiome());
        self::register(self::ROOFED_FOREST_MOUNTAINS, new RoofedForestMBiome());
        self::register(self::SUNFLOWER_PLAINS, new SunflowerPlainsBiome());
        self::register(self::SWAMP, new SwampBiome());
        self::register(self::SWAMP_MOUNTAINS, new SwampMBiome());

        //hot
        //total 8
        self::register(self::DESERT, new DesertBiome());
        self::register(self::DESERT_MOUNTAINS, new DesertMBiome());
        self::register(self::MESA, new MesaBiome());
        self::register(self::MESA_BRYCE, new MesaBryceBiome());
        self::register(self::MESA_PLATEAU_F, new MesaPlateauFBiome());
        self::register(self::MESA_PLATEAU_F_MOUNTAINS, new MesaPlateuFMBiome());
        self::register(self::SAVANNA, new SavannaBiome());
        self::register(self::SAVANNA_MOUNTAINS, new SavannaMBiome());

        //no-temped
        self::register(self::OCEAN, new OceanBiome());

        //hell
        self::register(self::HELL, new HellBiome());

        //end
        self::register(self::END, new EnderBiome());

        self::$mobsOverride = [
            Sheep::class,
            EntityCow::class,
            EntityChicken::class,
            Pig::class,
            EntityEnderman::class
        ];
        self::$monsters = [
            EntityZombie::class,
            EntityCreeper::class,
            EntitySkeleton::class,
            EntitySpider::class
        ];
    }

    protected static function register($id, Biome $biome)
    {
        self::$biomes[(int)$id] = $biome;
        $biome->setId((int)$id);

        $pumpkins = new Pumpkin();
        if (!($biome instanceof SnowyBiome) && !($biome instanceof FreezedBiome))
            $biome->addPopulator($pumpkins);
    }

    public function addPopulator(Populator $populator)
    {
        $this->populators[count($this->populators)] = $populator;
    }

    public static function getMonsters(): array
    {
        return self::$monsters;
    }

    public static function getMonster(int $k)
    {
        return self::$monsters[$k];
    }

    public static function getMobs(): array
    {
        return self::$mobsOverride;
    }

    public static function getMob(int $k)
    {
        return self::$mobsOverride[$k];
    }

    /**
     * @param $id
     *
     * @return Biome
     */
    public static function getBiome($id)
    {
        return isset(self::$biomes[$id]) ? self::$biomes[$id] : self::$biomes[self::OCEAN];
    }

    public function getPopulators()
    {
        return $this->populators;
    }

    public function clearPopulators()
    {
        $this->populators = [];
    }

    public function removePopulator($class)
    {
        if (isset($this->populators[$class])) {
            unset($this->populators[$class]);
        }
    }

    public function populateChunk(ChunkManager $level, $chunkX, $chunkZ, Random $random)
    {
        foreach ($this->populators as $populator) {
            $populator->populate($level, $chunkX, $chunkZ, $random);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if (!$this->registered) {
            $this->registered = true;
            $this->id = $id;
        }
    }

    public abstract function getName(): string;

    public function getMinElevation()
    {
        return $this->minElevation;
    }

    public function getMaxElevation()
    {
        return $this->maxElevation;
    }

    public function setElevation($min, $max)
    {
        $this->minElevation = $min;
        $this->maxElevation = $max;
    }

    public function haveMoreCover(): bool
    {
        return false;
    }

    public function getMoreCover(): array
    {
        return [];
    }

    public function haveCoverBlock(): bool
    {
        return false;
    }

    public function getCoverBlock(): int
    {
        return $this->coverBlock;
    }

    public function setCoverBlock(int $id)
    {
        $this->coverBlock = $id;
    }

    public function getCoverBlockMeta(): int
    {
        return $this->coverBlockMeta;
    }

    public function setCoverBlockMeta(int $meta)
    {
        $this->coverBlockMeta = $meta;
    }

    public function canCoverBlockStay(int $y): bool
    {
        return $y > $this->coverBlockY - 1;
    }

    /**
     * @return Block[]
     */
    public function getGroundCover()
    {
        return $this->groundCover;
    }

    /**
     * @param Block[] $covers
     */
    public function setGroundCover(array $covers)
    {
        $this->groundCover = $covers;
    }

    /**public function getTemperature()
     * {
     * return $this->temperature;
     * }
     *
     * public function getRainfall()
     * {
     * return $this->rainfall;
     * }*/
}