<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\block\BlockTypeIds;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {

	private $player;
	
	public function __construct (Player $player){
	   $this->player = $player;
	}

	public function getPlayer(): Player {
        return $this->player;
	}

    public function onRun(int $currentTick = 0) : void {
     $player = $this->getPlayer();
     if ($player instanceof Player){
        $inv = $players->getArmorInventory();
            $helmet = $inv->getHelmet();
            if($helmet->getTypeId() !== BlockTypeIds::MOB_HEAD) return;
            switch($helmet->getStateId()){
                case 2:
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
                    break;
                case 4:
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
                    break;
                case 1:
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
	            break;
	        case 6:
	             $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
		    break;
	        case 3:
		     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
		    break;
	        case 5:
		     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
      	             $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::SATURATION(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
		    break;
	    }
        }
    }
}
