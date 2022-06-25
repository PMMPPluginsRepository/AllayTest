<?php

declare(strict_types=1);

namespace skh6075\allaytest;

use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

final class AllayEntity extends Entity{

	private Item $item;

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.25, 0.25);
	}

	public static function getNetworkTypeId() : string{
		return "minecraft:allay";
	}

	private function sendHandItem(Item $item): void{
		$this->getWorld()->broadcastPacketToViewers($this->getPosition(), MobEquipmentPacket::create(
			actorRuntimeId: $this->getId(),
			item: ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($item)),
			inventorySlot: 0,
			hotbarSlot: 0,
			windowId: 0));
		$this->item = $item;
	}

	public function onUpdate(int $currentTick) : bool{
		$hasUpdate = parent::onUpdate($currentTick);
		if($this->closed){
			return false;
		}
		if(($currentTick % (5 * 20)) !== 0){
			return false;
		}
		if(!empty($this->item) && !$this->item->isNull()){
			$this->item = VanillaItems::AIR();
		}else{
			$this->item = VanillaItems::COOKIE();
		}
		$this->sendHandItem($this->item);
		return $hasUpdate;
	}
}