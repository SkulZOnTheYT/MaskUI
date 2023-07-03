<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\inventory\ArmorInventory;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\scheduler\Task;
use pocketmine\event\player\PlayerEvent;
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
	
    public function onRun(int $currentTick = 0) : void{
      $player = $this->getPlayer();
	$inv = $player->getArmorInventory();
        if (!$this->isWearingMobHead($inv)) {
            $this->onCancel();
            return;
        }
	    
        $helmet = $inv->getHelmet();
        if ($helmet instanceof Item) {
            switch ($helmet->getName()) {
		case "Wither Skeleton Skull":
                case "Skeleton Skull":
                    $this->applySkeletonHeadEffects();
                    break;
                case "Dragon Head":
                    $this->applyDragonHeadEffects();
                    break;
                case "Creeper Head":
                    $this->applyCreeperHeadEffects();
                    break;
		case "Player Head":
                    $this->applySteveHeadEffects();
                    break;
                case "Zombie Head":
                    $this->applyZombieHeadEffects();
                    break;
	     }
        }
    }

   public function onInventoryTransaction(InventoryTransactionEvent $event): void {
      $transaction = $event->getTransaction();
       $actions = $transaction->getActions();
        foreach ($actions as $action) {
          if ($action instanceof SlotChangeAction) {
            $inventory = $action->getInventory();
            $source = $transaction->getSource();
            
            if ($source instanceof Player && $inventory instanceof ArmorInventory) {
                $this->handleArmorChange($source);
                return;
            }
        }
    }
}

  public function handleArmorChange(Player $player): void {
    $inv = $player->getArmorInventory();
    $helmet = $inv->getHelmet();
    if ($helmet !== null && $this->isWearingMobHead($inv)) {
        $this->onRun($player);
    } else {
        $this->onCancel();
    }
}

   public function onCancel(): void{
      $player = $this->getPlayer();
        if (isset($this->activeEffects[$player->getName()])) {
          $player->getEffects()->remove($this->activeEffects[$player->getName()]);
          unset($this->activeEffects[$player->getName()]);
        }
   }

    public function isWearingMobHead(ArmorInventory $inv): bool {
       $helmet = $inv->getHelmet();
          $mobHeadNames = ["Wither Skeleton Skull", "Skeleton Skull", "Dragon Head", "Player Head", "Zombie Head", "Creeper Head"];
          return $helmet !== null && in_array($helmet->getCustomName(), $mobHeadNames);
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
