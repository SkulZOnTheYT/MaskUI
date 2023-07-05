<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\scheduler\Task;
use pocketmine\block\utils\MobHeadType;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {

        private $player;
	private $activeEffects;
	
	public function __construct (Player $player){
		$this->player = $player;
		$this->activeEffects = [];
	}

	public function getPlayer(): Player {
        return $this->player;
	}

   public function onInventoryTransaction(InventoryTransactionEvent $event): void {
      $transaction = $event->getTransaction();
       $actions = $transaction->getActions();
        foreach ($actions as $action) {
          if ($action instanceof SlotChangeAction) {
            $inventory = $action->getInventory();
            $player = $transaction->getSource();
            
            if ($player instanceof Player && $inventory instanceof ArmorInventory) {
                $this->handleArmorChange($player);
                return;
            }
        }
    }
}

  public function handleArmorChange(Player $player): void {
    $inv = $player->getArmorInventory();
    $wearingMobHead = false;

    foreach ($inv->getContents() as $slot => $item) {
        if ($this->isWearingMobHead($item)) {
            $this->applyEffect($player, $item);
            $wearingMobHead = true;
        }
    }

    if (!$wearingMobHead) {
        $this->removeEffect($player);
    }
}

  public function applyEffect(Player $player, Item $item): void {
    $mobHeadType = $this->getMobHeadType($item);
     switch ($mobHeadType) {
        case MobHeadType::DRAGON():
            $this->applyDragonHeadEffects($player);
            break;
        case MobHeadType::CREEPER():
            $this->applyCreeperHeadEffects($player);
            break;
        case MobHeadType::WITHER_SKELETON():
        case MobHeadType::SKELETON():
             $this->applySkeletonHeadEffects($player);
            break;
	case MobHeadType::ZOMBIE():
	     $this->applyZombieHeadEffects($player);
	     break;
	case MobHeadType::PLAYER():
	     $this->applySteveHeadEffects($player);
	     break;
     }
  }

   public function removeEffect(Player $player): void{
      $player = $this->getPlayer();
	$player->removeAllEffects();
   }

    public function isWearingMobHead(ArmorInventory $inv): bool {
       $helmet = $inv->getHelmet();
          $mobHeadNames = ["Wither Skeleton Skull", "Skeleton Skull", "Dragon Head", "Player Head", "Zombie Head", "Creeper Head"];
          return $helmet !== null && in_array($helmet->getCustomName(), $mobHeadNames);
    }

    private function applyDragonHeadEffects(Player $player): void {
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

    private function applyCreeperHeadEffects(Player $player): void {
	$player = $this->getPlayer();
        $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
    }

    private function applySkeletonHeadEffects(Player $player): void {
	$player = $this->getPlayer();
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
    }

    private function applySteveHeadEffects(Player $player): void {
       $player = $this->getPlayer();
       $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
    }

    private function applyZombieHeadEffects(Player $player): void {
	$player = $this->getPlayer();
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
    }
}
