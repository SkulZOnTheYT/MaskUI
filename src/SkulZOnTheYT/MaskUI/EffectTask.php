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
	
	private Player $player;
	
	public function __construct(Player $player) {
        $this->player = $player;
    }

    public function onRun(int $currentTick) : void{
	$dragon = ItemFactory::getInstance()->get(397, 5, 1);
        $creeper = ItemFactory::getInstance()->get(397, 4, 1);
        $wither = ItemFactory::getInstance()->get(397, 1, 1);
        $steve = ItemFactory::getInstance()->get(397, 3, 1);
        $skeleton = ItemFactory::getInstance()->get(397, 0, 1);
        $zombie = ItemFactory::getInstance()->get(397, 2, 1);
	    
        $helmet = $this->player->getArmorInventory()->getHelmet();
          if ($helmet !== null) {
            switch ($helmet->getId()) {
                case $dragon:
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::FIRE_RESISTANCE), 220, 3, false));
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::JUMP_BOOST), 220, 2, false));
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::HEALTH_BOOST), 220, 4, false));
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::SPEED), 220, 2, false));
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::NIGHT_VISION), 220, 2, false));
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::NIGHT_VISION), 220, 2, false));
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::STRENGTH), 220, 2, false));
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::SATURATION), 220, 2, false));
                    $player->getEffects()->add(new EffectInstance(EffectInstance::getEffect(Effect::REGENERATION), 220, 2, false));
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
