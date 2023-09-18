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
            if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::SKELETON())->asItem()->getStateId()) {
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 0, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 300, 0, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 0, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 300, 1, false));
           }
	   if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::ZOMBIE())->asItem()->getStateId()) {
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 300, 0, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 0, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 300, 1, false));
	   } 
	   if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::CREEPER())->asItem()->getStateId()) {
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 300, 1, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 1, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::SATURATION(), 300, 1, false));
	   }
	   if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PIGLIN())->asItem()->getStateId()) {
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 300, 2, false));
      	      $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::INSTANT_HEALTH(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SATURATION(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 2, false)); 
	   }
	   if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PLAYER())->asItem()->getStateId()) {
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 300, 3, false));
      	      $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::INSTANT_HEALTH(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SATURATION(), 300, 2, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 2, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::WATER_BREATHING(), 300, 2, false));
	   }
	   if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::WITHER_SKELETON())->asItem()->getStateId()) {
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 300, 3, false));
      	      $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::INSTANT_HEALTH(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SATURATION(), 300, 3, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 3, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::WATER_BREATHING(), 300, 3, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 300, 3, false));
	   }
	   if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem()->getStateId()) {
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 300, 4, false));
      	      $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 300, 4, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 300, 4, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 4, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 4, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::INSTANT_HEALTH(), 300, 4, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 4, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::SATURATION(), 300, 4, false));
              $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 4, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::WATER_BREATHING(), 300, 4, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 300, 4, false));
	      $player->getEffects()->add(new EffectInstance(VanillaEffects::CONDUIT_POWER(), 300, 4, false));
	   }
        }
    }
}
