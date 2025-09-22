<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\utils\MobHeadType;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\effect\EffectInstance;

class EffectTask extends Task {

    public function __construct () {
	}

    public function onRun(): void {
      $server = Main::getInstance()->getServer();
      foreach ($server->getOnlinePlayers() as $player) {
         if (!$player instanceof Player) {
               continue;
         }

         $helmet = $player->getArmorInventory()->getHelmet();
         if ($helmet === null) {
               continue;
         }

         if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::SKELETON())->asItem()->getStateId()) {
               $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 0));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0));
         }

         if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::ZOMBIE())->asItem()->getStateId()) {
               $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), 220, 0));
         }

         if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::CREEPER())->asItem()->getStateId()) {
               $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0));
         }

         if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PIGLIN())->asItem()->getStateId()) {
               $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 220, 0));
         }

         if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PLAYER())->asItem()->getStateId()) {
               $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 220, 0));
         }

         if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::WITHER_SKELETON())->asItem()->getStateId()) {
               $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0));
         }

         if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem()->getStateId()) {
               $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 220, 2));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), 220, 2));
               $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 220, 2));
         }
      }
    }
}