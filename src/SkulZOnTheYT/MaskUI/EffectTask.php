<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\utils\MobHeadType;
use pocketmine\scheduler\Task;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {
	
	public function __construct() {
	}

    public function onRun(int $currentTick = 0): void {
        $plugin = Main::getInstance();

        foreach ($plugin->getServer()->getOnlinePlayers() as $player) {
            $helmet = $player->getArmorInventory()->getHelmet();
            
            if ($helmet->getBlock() instanceof MobHeadType) {
                $mobHeadType = $helmet->getBlock()->getMobHeadType();
                
                $effects = [
                    VanillaEffects::STRENGTH(),
                    VanillaEffects::NIGHT_VISION(),
                    VanillaEffects::JUMP_BOOST(),
                    VanillaEffects::REGENERATION(),
                    VanillaEffects::FIRE_RESISTANCE(),
                    VanillaEffects::HEALTH_BOOST(),
                ];
                
                foreach ($effects as $effect) {
                    $amplifier = match ($mobHeadType->getNetworkId()) {
                        MobHeadType::SKELETON => 0,
                        MobHeadType::ZOMBIE => 1,
                        MobHeadType::CREEPER => 1,
                        MobHeadType::PIGLIN => 2,
                        MobHeadType::PLAYER => 3,
                        MobHeadType::WITHER_SKELETON => 3,
                        MobHeadType::DRAGON => 4,
                        default => 0,
                    };
                    
                    $player->getEffects()->add(new EffectInstance($effect, 300, $amplifier, false));
                }
                
                if ($mobHeadType->getNetworkId() === MobHeadType::DRAGON) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::CONDUIT_POWER(), 300, 4, false));
                }
                
                if (!$player->getInventory()->contains($helmet)) {
                    $player->getEffects()->removeAll();
                }
            }
        }
    }
}
