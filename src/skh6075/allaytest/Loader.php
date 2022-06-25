<?php

declare(strict_types=1);

namespace skh6075\allaytest;

use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Location;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\SpawnEgg;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

final class Loader extends PluginBase{

	protected function onLoad() : void{
		EntityFactory::getInstance()->register(AllayEntity::class, function(World $world, CompoundTag $nbt): AllayEntity{
			return new AllayEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt);
		}, ['Allay', 'minecraft:allay']);
		ItemFactory::getInstance()->register(new class(new ItemIdentifier(ItemIds::SPAWN_EGG, 134), 'Allay Spawn Egg') extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : AllayEntity{
				return new AllayEntity(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		}, true);
	}
}