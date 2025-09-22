<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\scheduler\Task;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\utils\MobHeadType;
use pocketmine\item\Item;

class EffectTask extends Task {

    public function onRun(): void {
        $server = Main::getInstance()->getServer();
        foreach ($server->getOnlinePlayers() as $player) {
            $helmet = $player->getArmorInventory()->getHelmet();
            if ($helmet instanceof Item) {

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::SKELETON())->asItem()->getStateId()) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 0));
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::ZOMBIE())->asItem()->getStateId()) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), 300, 0));
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::CREEPER())->asItem()->getStateId()) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 0));
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PIGLIN())->asItem()->getStateId()) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 300, 0));
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PLAYER())->asItem()->getStateId()) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 300, 0));
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::WITHER_SKELETON())->asItem()->getStateId()) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 0));
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem()->getStateId()) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 300, 0));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 300, 2));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 300, 2));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), 300, 1));
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 300, 1));
                }
            }
        }
    }
}