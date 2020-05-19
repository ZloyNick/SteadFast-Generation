<?php
/*
Need add red flower
*/

namespace GeneratorRemake\normal\biome\normal;

use GeneratorRemake\normal\biome\GrassyBiome;
use GeneratorRemake\populator\BigMushroom;
use GeneratorRemake\populator\Flower;
use GeneratorRemake\populator\Mushroom;
use GeneratorRemake\populator\PopulatorTallGrass;
use GeneratorRemake\populator\Sugarcane;
use GeneratorRemake\populator\TallGrass;
use GeneratorRemake\populator\tree\DarkOakTreePopulator;
use pocketmine\block\Block;

class RoofedForestBiome extends GrassyBiome
{
    public function __construct()
    {
        parent::__construct();

        $bigmushroom = new BigMushroom(6);
        $bigmushroom->setBaseAmount(1);
        $this->addPopulator($bigmushroom);

        $populator1 = new PopulatorTallGrass();
        $populator1->setType(1);
        $populator1->setBaseAmount(1);
        $populator1->setRandomAmount(5);
        $this->addPopulator($populator1);

        $populator2 = new PopulatorTallGrass();
        $populator2->setType(4);
        $populator2->setBaseAmount(1);
        $populator2->setRandomAmount(5);
        $this->addPopulator($populator2);

        $populator3 = new PopulatorTallGrass();
        $populator3->setType(5);
        $populator3->setBaseAmount(1);
        $populator3->setRandomAmount(5);
        $this->addPopulator($populator3);

        $sugarcane = new Sugarcane();
        $sugarcane->setBaseAmount(6);
        $tallGrass = new TallGrass();
        $trees = new DarkOakTreePopulator();
        $trees->setBaseAmount(20);
        $tallGrass->setBaseAmount(9);
        $flower = new Flower();
        $flower->setBaseAmount(1);
        $flower->setRandomAmount(5);
        $flower->addType([Block::DANDELION, 0]);
        $mushroom = new Mushroom();
        $mushroom->setBaseAmount(50);
        $this->addPopulator($mushroom);
        $this->addPopulator($sugarcane);
        $this->addPopulator($tallGrass);
        $this->addPopulator($trees);
        $this->setElevation(67, 78);
    }

    public function getName(): string
    {
        return "Roofed Forest";
    }
}
