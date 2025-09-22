<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;
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
                    $this->applyEffects($player, [
                        new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::SPEED(), 220, 0, false),
                        new EffectInstance(VanillaEffects::HASTE(), 220, 0, false),
                    ]);
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::ZOMBIE())->asItem()->getStateId()) {
                    $this->applyEffects($player, [
                        new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::RESISTANCE(), 220, 0, false),
                        new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false),
                        new EffectInstance(VanillaEffects::HUNGER(), 220, 0, false),
                    ]);
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::CREEPER())->asItem()->getStateId()) {
                    $this->applyEffects($player, [
                        new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false),
                        new EffectInstance(VanillaEffects::SPEED(), 220, 1, false),
                        new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false),
                    ]);
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PIGLIN())->asItem()->getStateId()) {
                    $this->applyEffects($player, [
                        new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false),
                        new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false),
                        new EffectInstance(VanillaEffects::RESISTANCE(), 220, 0, false),
                    ]);
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PLAYER())->asItem()->getStateId()) {
                    $this->applyEffects($player, [
                        new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::SPEED(), 220, 0, false),
                        new EffectInstance(VanillaEffects::HASTE(), 220, 1, false),
                        new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::LUCK(), 220, 0, false),
                    ]);
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::WITHER_SKELETON())->asItem()->getStateId()) {
                    $this->applyEffects($player, [
                        new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false),
                        new EffectInstance(VanillaEffects::RESISTANCE(), 220, 1, false),
                        new EffectInstance(VanillaEffects::SPEED(), 220, 0, false),
                    ]);
                    foreach ($player->getWorld()->getNearbyEntities($player->getBoundingBox()->expandedCopy(3, 3, 3), $player) as $entity) {
                        if ($entity instanceof Player && $entity !== $player) {
                            $entity->getEffects()->add(new EffectInstance(VanillaEffects::WITHER(), 60, 0, false));
                        }
                    }
                }

                if ($helmet->getStateId() === VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem()->getStateId()) {
                    $this->applyEffects($player, [
                        new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false),
                        new EffectInstance(VanillaEffects::RESISTANCE(), 220, 1, false),
                        new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false),
                        new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false),
                        new EffectInstance(VanillaEffects::SPEED(), 220, 1, false),
                        new EffectInstance(VanillaEffects::SLOW_FALLING(), 220, 0, false),
                        new EffectInstance(VanillaEffects::ABSORPTION(), 220, 0, false),
                        new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false),
                    ]);
                }
            }
        }
    }

    private function applyEffects(Player $player, array $effects): void {
        foreach ($effects as $effect) {
            $player->getEffects()->add($effect);
        }
    }
}
