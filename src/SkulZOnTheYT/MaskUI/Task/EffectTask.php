<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\inventory\ArmorInventory;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\event\player\PlayerEvent;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\block\MobHead;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\utils\MobHeadType;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {

        private $player;
	
	public function __construct (Player $player){
	   $server = Server::getInstance();
           $player = $server->getOnlinePlayers();
	}

    public function onRun(int $currentTick = 0) : void {
     if ($player instanceOf Player) {
        $idInfo = new BlockIdentifier(BlockTypeIds::MOB_HEAD);
	    $breakInfo = new BlockBreakInfo(0);
	    $typeInfo = new BlockTypeInfo($breakInfo);
	    $name1 = ("Mask Name");
          
         $mobHead = new MobHead($idInfo, $name1, $typeInfo);
          $i = $mobHead->asItem();
	  $inv = $players->getArmorInventory();
          $helmet = $inv->getHelmet();
          
           if(!$helmet->getItem() == $i) return;
            switch($mobHead->getMobHeadType()){
                case MobHeadType::SKELETON():
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
                    break;
                case MobHeadType::ZOMBIE():
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
                    break;
                case MobHeadType::CREEPER():
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
		    break;
		case MobHeadType::WITHER_SKELETON():
		     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
		    break;
	        case MobHeadType::PLAYER():
		     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
		    break;
	        case MobHeadType::DRAGON():
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
