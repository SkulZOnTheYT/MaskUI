<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\utils\MobHeadType;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {
	
	public function __construct () {
	}

    public function onRun(int $currentTick = 0) : void {
      $server = Main::getInstance()->getServer();
       foreach ($server->getOnlinePlayers() as $player) {
        $helmet = $player->getArmorInventory()->getHelmet();
           if ($helmet->getTypeId() === VanillaItems::DIAMOND_HELMET()->getTypeId()) {
	     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
             $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
           }
	   if ($helmet !== null && $helmet->getTypeId() === ItemTypeIds::NETHERITE_HELMET) {
	     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
             $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
	   }
	   if ($helmet->getTypeId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::ZOMBIE())->asItem()->getTypeId()) {
	     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
             $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
             $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
             $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
             $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
             $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
	   } 
        }
    }
}
