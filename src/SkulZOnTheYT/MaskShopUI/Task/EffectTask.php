<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskShopUI\Task;

use SkulZOnTheYT\MaskShopUI\Main;
use pocketmine\item\Item;
use pocketmine\scheduler\Task;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class EffectTask extends Task{
    
    public function onEnable(int $tick) : void{
        foreach(Main::getInstance()->getServer()->getOnlinePlayers() as $players){
            $inv = $players->getArmorInventory();
            $helmet = $inv->getHelmet();
            if(!$helmet->getId() == Item::MOB_HEAD) return;
            switch($helmet->getDamage()){
                case 0:
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
                case 3:
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
                case 4:
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
    
  public const effectsData = [
		 "speed" => ["name" => "Speed", "image" => "textures/ui/speed_effect"],
	 	"slowness" => ["name" => "Slowness", "image" => "textures/ui/slowness_effect"],
		 "haste" => ["name" => "Haste", "image" => "textures/ui/haste_effect"],
		 "mining_fatigue" => ["name" => "Mining Fatigue", "image" => "textures/ui/mining_fatigue_effect"],
		 "strength" => ["name" => "Strength", "image" => "textures/ui/strength_effect"],
		 "instant_health" => ["name" => "Instant Health", "image" => null],
		 "instant_damage" => ["name" => "Instant Damage", "image" => null],
		 "jump_boost" => ["name" => "Jump Boost", "image" => "textures/ui/jump_boost_effect"],
		 "nausea" => ["name" => "Nausea", "image" => "textures/ui/nausea_effect"],
		 "regeneration" => ["name" => "Regeneration", "image" => "textures/ui/regeneration_effect"],
		 "resistance" => ["name" => "Resistance", "image" => "textures/ui/resistance_effect"],
		 "fire_resistance" => ["name" => "Fire Resistance", "image" => "textures/ui/fire_resistance_effect"],
		 "water_breathing" => ["name" => "Water Breathing", "image" => "textures/ui/water_breathing_effect"],
		 "invisibility" => ["name" => "Invisibility", "image" => "textures/ui/_effect"],
		 "blindness" => ["name" => "Blindness", "image" => "textures/ui/invisibility_effect"],
		 "night_vision" => ["name" => "Night Vision", "image" => "textures/ui/night_vision_effect"],
		 "hunger" => ["name" => "Hunger", "image" => "textures/ui/hunger_effect"],
		 "weakness" => ["name" => "Weakness", "image" => "textures/ui/weakness_effect"],
		 "poison" => ["name" => "Poison", "image" => "textures/ui/poison_effect"],
		 "wither" => ["name" => "Wither", "image" => "textures/ui/wither_effect"],
		 "health_boost" => ["name" => "Health Boost", "image" => "textures/ui/health_boost_effect"],
		 "absorption" => ["name" => "Absorption", "image" => "textures/ui/absorption_effect"],
		 "saturation" => ["name" => "Saturation", "image" => "textures/ui/saturation_effect"],
		 "levitation" => ["name" => "Levitation", "image" => "textures/ui/levitation_effect"],
		 "conduit_power" => ["name" => "Conduit Power", "image" => "textures/ui/conduit_power_effect"]
	];
	
	/** @var array $fronteffect */
	private $fronteffect = ["effectname" => [], "realname" => []];
    
    public static function getEffectNameByName(string $name): string{
    	if(!isset(self::effectsData[$name]["name"])){
    		return "";
    	}
		return self::effectsData[$name]["name"];
	}
	
	public static function getImageEffectByName(string $name): string{
		if(!isset(self::effectsData[$name]["image"])){
			return "";
		}
		return self::effectsData[$name]["image"];
	}
	
	public static function getEffectByName(string $name){
		switch($name){
			case "absorption":
			return VanillaEffects::ABSORPTION();
			case "blindness":
			return VanillaEffects::BLINDNESS();
			case "conduit_power":
			return VanillaEffects::CONDUIT_POWER();
			case "poison":
			return VanillaEffects::POISON();
			case "fire_resistance":
			return VanillaEffects::FIRE_RESISTANCE();
			case "haste":
			return VanillaEffects::HASTE();
			case "health_boost":
			return VanillaEffects::HEALTH_BOOST();
			case "hunger":
			return VanillaEffects::HUNGER();
			case "instant_damage":
			return VanillaEffects::INSTANT_DAMAGE();
			case "instant_health":
			return VanillaEffects::INSTANT_HEALTH();
			case "invisibility":
			return VanillaEffects::INVISIBILITY();
			case "jump_boost":
			return VanillaEffects::JUMP_BOOST();
			case "levitation":
			return VanillaEffects::LEVITATION();
			case "mining_fatigue":
			return VanillaEffects::MINING_FATIGUE();
			case "nausea":
			return VanillaEffects::NAUSEA();
			case "night_vision":
			return VanillaEffects::NIGHT_VISION();
			case "regeneration":
			return VanillaEffects::REGENERATION();
			case "resistance":
			return VanillaEffects::RESISTANCE();
			case "saturation":
			return VanillaEffects::SATURATION();
			case "slowness":
			return VanillaEffects::SLOWNESS();
			case "speed":
			return VanillaEffects::SPEED();
			case "strength":
			return VanillaEffects::STRENGTH();
			case "water_breathing":
			return VanillaEffects::WATER_BREATHING();
			case "weakness":
			return VanillaEffects::WEAKNESS();
			case "wither":
			return VanillaEffects::WITHER();
			default:
			return null;
		}
	}
	
	public static function translateEffectName(string $name): string {
		$name = str_replace("potion.", "", $name);
		switch($name){
			case "jump":
			return "jump_boost";
			case "confusion":
			return "nausea";
			case "heal":
			return "instant_health";
			case "harm":
			return "instant_damage";
			case "conduitPower":
			return "conduit_power";
			case "damageBoost":
			return "strength";
			case "digSlowDown":
			return "mining_fatigue";
			case "digSpeed":
			return "haste";
			case "fireResistance":
			return "fire_resistance";
			case "healthBoost":
			return "health_boost";
			case "moveSlowdown":
			return "slowness";
			case "moveSpeed":
			return "speed";
			case "nightVision":
			return "night_vision";
			case "waterBreathing":
			return "water_breathing";
			default:
			return $name;
		}
	}
}
