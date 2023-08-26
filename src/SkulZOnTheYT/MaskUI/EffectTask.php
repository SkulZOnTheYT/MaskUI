<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\block\BlockTypeIds;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {
	
	public function __construct () {
	}

    public function onRun(int $currentTick = 0) : void {
      $server = Main::getInstance()->getServer();
        foreach ($server->getOnlinePlayers() as $player) {
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                     $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));	     
        }
    }
}
