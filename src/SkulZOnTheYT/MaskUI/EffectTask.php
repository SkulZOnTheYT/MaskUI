<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\Item;
use pocketmine\item\Armor;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {
	
	private $player = $this->getName();

	public function __construct(Player $player) {}

    public function onRun(int $currentTick = 0) : void{
	$dragon = Item::fromString("minecraft:dragon_head");
        $creeper = Item::fromString("minecraft:creeper_head");
        $wither = Item::fromString("minecraft:wither_skeleton_skull");
        $steve = Item::fromString("minecraft:player_head");
        $skeleton = Item::fromString("minecraft:skeleton_skull");
        $zombie = Item::fromString("minecraft:zombie_head");
	    
        $helmet = $this->getArmorInventrory()->getHelmet();
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
