<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\event\player\PlayerEvent;
use pocketmine\utils\TextFormat;
use pocketmine\block\MobHead;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\utils\MobHeadType;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {

	private $player;

	public function __construct (Player $player) {
		$this->player = $player;
	}

	public function getPlayer(): Player {
        return $this->player;
	}

    public function onRun(int $currentTick = 0) : void{
	$dr = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::DRAGON(), ("Dragon Head"));
	    $dragon = $dr->asItem();
        $cr = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::CREEPER(), ("Creeper Head"));
	    $creeper = $cr->asItem();
        $wi = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::WITHER_SKELETON(), ("Wither Skull"));
	    $wither = $wi->asItem();
        $st = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::PLAYER(), ("Player Head"));
	    $steve = $st->asItem();
        $sk = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::SKELETON(), ("Skeleton Skull"));
	    $skeleton = $sk->asItem();
        $zo = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::ZOMBIE(), ("Zombie Head"));
	    $zombie = $zo->asItem();
	    
        $helmet = $armorInventory->getHelmet();
          if ($helmet !== null) {
            switch ($helmet->getItem()) {
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
         $player = $this->getPlayer();
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
	$player = $this->getPlayer();
        $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
    }

    private function applySkeletonHeadEffects(): void {
	$player = $this->getPlayer();
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
    }

    private function applySteveHeadEffects(): void {
       $player = $this->getPlayer();
       $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
    }

    private function applyZombieHeadEffects(): void {
	$player = $this->getPlayer();
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
    }
}
