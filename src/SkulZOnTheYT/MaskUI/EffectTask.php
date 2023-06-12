<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use pocketmine\block\MobHead;
use pocketmine\block\utils\MobHeadType;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {

	public function __construct(Sender $sender) {
	    if($sender Instanceof Player){
		 $this->onRun($sender);
            }
	}

    public function onRun(int $currentTick = 0) : void{
	$dr = new MobHead(BlockLegacyIds::SKULL, MobHeadType::DRAGON(), ("Dragon Head"));
	    $dragon = $dr->asItem();
        $cr = new MobHead(BlockLegacyIds::SKULL, MobHeadType::CREEPER(), ("Creeper Head"));
	    $creeper = $cr->asItem();
        $wi = new MobHead(BlockLegacyIds::SKULL, MobHeadType::WITHER_SKELETON(), ("Wither Skull"));
	    $wither = $wi->asItem();
        $st = new MobHead(BlockLegacyIds::SKULL, MobHeadType::PLAYER(), ("Player Head"));
	    $steve = $st->asItem();
        $sk = new MobHead(BlockLegacyIds::SKULL, MobHeadType::SKELETON(), ("Skeleton Skull"));
	    $skeleton = $sk->asItem();
        $zo = new MobHead(BlockLegacyIds::SKULL, MobHeadType::ZOMBIE(), ("Zombie Head"));
	    $zombie = $zo->asItem();
	    
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

         $sender->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
         $sender->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
         $sender->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
         $sender->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 2, false));
         $sender->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
         $sender->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
         $sender->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
         $sender->getEffects()->add(new EffectInstance(VanillaEffects::SATURATION(), 220, 2, false));
         $sender->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
    }

    private function applyCreeperHeadEffects(): void {
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 0, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
    }

    private function applySkeletonHeadEffects(): void {
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
    }

    private function applySteveHeadEffects(): void {
       $sender->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
       $sender->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1, false));
       $sender->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
       $sender->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
       $sender->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
       $sender->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
       $sender->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
    }

    private function applyZombieHeadEffects(): void {
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
        $sender->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
    }
}
