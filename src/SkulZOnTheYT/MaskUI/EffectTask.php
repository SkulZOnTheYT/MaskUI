<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\scheduler\Task;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\utils\MobHeadType;

class EffectTask extends Task {

    public function onRun(): void {
        $server = Main::getInstance()->getServer();
        foreach ($server->getOnlinePlayers() as $player) {
            $helmet = $player->getArmorInventory()->getHelmet();

            if ($helmet === null) {
                continue;
            }

            if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::SKELETON())->asItem()->getStateId()) {
                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 200, 0));
            }

            if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::ZOMBIE())->asItem()->getStateId()) {
                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), 200, 0));
            }

            if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::CREEPER())->asItem()->getStateId()) {
                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 200, 0));
            }

            if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PIGLIN())->asItem()->getStateId()) {
                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 200, 0));
            }

            if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PLAYER())->asItem()->getStateId()) {
                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 200, 0));
            }

            if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::WITHER_SKELETON())->asItem()->getStateId()) {
                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 200, 0));
            }

            if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem()->getStateId()) {
                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 200, 0));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 200, 2));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 200, 2));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), 200, 1));
                $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 200, 1));
            }
        }
    }
}