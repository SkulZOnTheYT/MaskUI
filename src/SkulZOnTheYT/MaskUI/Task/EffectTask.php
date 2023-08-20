<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use MaskShop\Main;
use pocketmine\item\Item;
use pocketmine\scheduler\Task;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class EffectTask extends Task{
    
    public function onRun(int $tick) : void{
        foreach(Main::getInstance()->getServer()->getOnlinePlayers() as $players){
            $inv = $players->getArmorInventory();
            $helmet = $inv->getHelmet();
            if(!$helmet->getItem() == Item::MOB_HEAD) return;
            switch($helmet->getName()){
                case 4:
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 220, 1, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 220, 1, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 220, 1, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 220, 1, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 220, 0, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 220, 0, false));
                    break;
                case 1:
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 220, 0, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 220, 0, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::HEALTH_BOOST), 220, 0, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 220, 1, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 220, 2, false));
                    break;
                case 2:
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 220, 0, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 220, 1, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 220, 0, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 220, 0, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 220, 0, false));
                    break;
                case 5:
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 220, 3, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::HEALTH_BOOST), 220, 4, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::ABSORPTION), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::SATURATION), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 220, 2, false));
                    break;
                case 3:
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 220, 1, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::HEALTH_BOOST), 220, 4, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 220, 2, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 220, 3, false));
                    $players->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 220, 2, false));
                    break;
            }
        }
    }
}
