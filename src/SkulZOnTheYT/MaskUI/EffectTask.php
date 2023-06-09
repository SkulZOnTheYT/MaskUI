<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {
    
    private $player = $this->player
    
    private $dragon = ItemFactory::getInstance()->get(397, 5, 1);
    private $creeper = ItemFactory::getInstance()->get(397, 4, 1);
    private $wither = ItemFactory::getInstance()->get(397, 1, 1);
    private $steve = ItemFactory::getInstance()->get(397, 3, 1);
    private $skeleton = ItemFactory::getInstance()->get(397, 0, 1);
    private $zombie = ItemFactory::getInstance()->get(397, 2, 1);
    
     public static function getEffectByName(string $name){
		switch($name){
			case "absorption":
			return VanillaEffects::ABSORPTION();
			case "blindness":
			return VanillaEffects::BLINDNESS();
			case "conduit_power":
			return VanillaEffects::CONDUIT_POWER();
			case "poison":
			return VanillaEffects::POISON();
			case "fire_resistance":
			return VanillaEffects::FIRE_RESISTANCE();
			case "haste":
			return VanillaEffects::HASTE();
			case "health_boost":
			return VanillaEffects::HEALTH_BOOST();
			case "hunger":
			return VanillaEffects::HUNGER();
			case "instant_damage":
			return VanillaEffects::INSTANT_DAMAGE();
			case "instant_health":
			return VanillaEffects::INSTANT_HEALTH();
			case "invisibility":
			return VanillaEffects::INVISIBILITY();
			case "jump_boost":
			return VanillaEffects::JUMP_BOOST();
			case "levitation":
			return VanillaEffects::LEVITATION();
			case "mining_fatigue":
			return VanillaEffects::MINING_FATIGUE();
			case "nausea":
			return VanillaEffects::NAUSEA();
			case "night_vision":
			return VanillaEffects::NIGHT_VISION();
			case "regeneration":
			return VanillaEffects::REGENERATION();
			case "resistance":
			return VanillaEffects::RESISTANCE();
			case "saturation":
			return VanillaEffects::SATURATION();
			case "slowness":
			return VanillaEffects::SLOWNESS();
			case "speed":
			return VanillaEffects::SPEED();
			case "strength":
			return VanillaEffects::STRENGTH();
			case "water_breathing":
			return VanillaEffects::WATER_BREATHING();
			case "weakness":
			return VanillaEffects::WEAKNESS();
			case "wither":
			return VanillaEffects::WITHER();
			default:
			return null;
		}
	}

    public function onRun(int $currentTick) {
        $helmet = $this->player->getArmorInventory()->getHelmet();
          if ($helmet !== null) {
            switch ($helmet->getId()) {
                case $dragon:
                    $this->applyDragonHeadEffects();
                    break;
                case $creeper:
                    $this->applyCreeperHeadEffects();
                    break;
                case $wither:
                case $skeleton:
                    $this->applySkeletonHeadEffects();
                    break;
                case $steve:
                    $this->applySteveHeadEffects();
                    break;
                case $zombie:
                    $this->applyZombieHeadEffects();
                    break;
            }
        }
    }

    private function applyDragonHeadEffects(): void {
        
         $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
         $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
         $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
         $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 2, false));
         $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
         $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
         $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
         $player->getEffects()->add(new EffectInstance(VanillaEffects::SATURATION(), 220, 2, false));
         $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
    }

    private function applyCreeperHeadEffects(): void {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
    }

    private function applySkeletonHeadEffects(): void {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
    }

    private function applySteveHeadEffects(): void {
       $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
    }

    private function applyZombieHeadEffects(): void {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
    }
}
