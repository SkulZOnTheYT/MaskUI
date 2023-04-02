<?php

namespace Kylan1940\MaskShopUI;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\Sound;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use onebone\economyapi\EconomyAPI;

use Kylan1940\MaskShopUI\Task\EffectTask;

use Kylan1940\MaskShopUI\Form\{Form, SimpleForm, FormAPI};

class Main extends PluginBase implements Listener {
    
    /** @var Main $instance */
    private static $instance;

    public $eco;
	
	public $plugin;

	public function onEnable() : void{
	    self::$instance = $this;
	    $this->getScheduler()->scheduleRepeatingTask(new EffectTask(), 20);
        $this->getLogger()->info(TextFormat::GREEN . "§7[MaskShop§7]§a Plugin Enable");
        $this->checkDepends();
    }

    public function checkDepends(){
        $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if(is_null($this->eco)){
            $this->getLogger()->info("§7[§eMaskShop§7] §cPlease install EconomyAPI Plugin, §4disabling plugin...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }
	
	public static function getInstance() : self{
	    return self::$instance;
	}
     
     
    public function onCommand(CommandSender $sender, Command $cmd, string $label,array $args) : bool {
		
		switch($cmd->getName()){
		
			case "maskshop":			    
			$this->MaskShopForm($sender);
			return true;
		}
		return true;
	}
	
	public function MaskShopForm($sender){
		$form = new SimpleForm(function (Player $sender, $data){
			$result = $data;
			if($result === null){
			}
			switch($result){
								
				case 0:
				       $sender->getLevel()->addSound(new AnvilFallSound($sender));
				       $sender->sendMessage($this->getConfig()->get("quit.message"));
									
					break;
				case 1:
				       $this->FeatureMenu($sender);
				    break;
				case 2:
					$zombie = $this->getConfig()->get("zombie.price");
					if($this->eco->myMoney($sender) >= $zombie){
										
                       $this->eco->reduceMoney($sender, $zombie);
                       $name = $sender->getName();
                       $e = Enchantment::getEnchantment(0);
                       $item1 = Item::get(397, 2, 1);
                       $item1->setCustomName("§2Zombie §eMask \n§bOwner: §c$name");
					   $item1->addEnchantment(new EnchantmentInstance($e, 4));
                       $sender->getInventory()->addItem($item1);
                       $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
					   $sender->sendMessage($this->getConfig()->get("msg.shop.zombie"));
                      return true;
                    }else{
                       $sender->getLevel()->addSound(new AnvilFallSound($sender));
                       $sender->sendMessage($this->getConfig()->get("msg.no-money"));
                    }
									
					break;
				case 3:
					$creeper = $this->getConfig()->get("creeper.price");
					if($this->eco->myMoney($sender) >= $creeper){
										
                       $this->eco->reduceMoney($sender, $creeper);
                       $name = $sender->getName();
                       $e = Enchantment::getEnchantment(0);
                       $item2 = Item::get(397, 4, 1);
                       $item2->setCustomName("§aCreeper §eMask \n§bOwner: §c$name");
					   $item2->addEnchantment(new EnchantmentInstance($e, 4));
                       $sender->getInventory()->addItem($item2);
                       $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
					   $sender->sendMessage($this->getConfig()->get("msg.shop.creeper"));
                      return true;
                    }else{
                       $sender->getLevel()->addSound(new AnvilFallSound($sender));
                       $sender->sendMessage($this->getConfig()->get("msg.no-money"));
                    }
									
					break;
				case 4:
					$wither = $this->getConfig()->get("wither.price");
					if($this->eco->myMoney($sender) >= $wither){
										
                       $this->eco->reduceMoney($sender, $wither);
                       $name = $sender->getName();
                       $e = Enchantment::getEnchantment(0);
                       $item3 = Item::get(397, 1, 1);
                       $item3->setCustomName("§7Wither §eMask \n§bOwner: §c$name");
					   $item3->addEnchantment(new EnchantmentInstance($e, 4));
                       $sender->getInventory()->addItem($item3);
                       $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
					   $sender->sendMessage($this->getConfig()->get("msg.shop.wither"));
                      return true;
                    }else{
                       $sender->getLevel()->addSound(new AnvilFallSound($sender));
                       $sender->sendMessage($this->getConfig()->get("msg.no-money"));
                    }
									
					break;
				case 5:
					$dragon = $this->getConfig()->get("dragon.price");
					if($this->eco->myMoney($sender) >= $dragon){
										
                       $this->eco->reduceMoney($sender, $dragon);
                       $name = $sender->getName();
                       $e = Enchantment::getEnchantment(0);
                       $item5 = Item::get(397, 5, 1);
                       $item5->setCustomName("§cDragon §eMask \n§bOwner: §c$name");
					   $item5->addEnchantment(new EnchantmentInstance($e, 4));
                       $sender->getInventory()->addItem($item5);
                       $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
					   $sender->sendMessage($this->getConfig()->get("msg.shop.dragon"));
                      return true;
                    }else{
                       $sender->getLevel()->addSound(new AnvilFallSound($sender));
                       $sender->sendMessage($this->getConfig()->get("msg.no-money"));
                    }
									
					break;
				case 6:	
					$steve = $this->getConfig()->get("steve.price");
					if($this->eco->myMoney($sender) >= $steve){
										
                       $this->eco->reduceMoney($sender, $steve);
                       $name = $sender->getName();
                       $e = Enchantment::getEnchantment(0);
                       $item4 = Item::get(397, 3, 1);
                       $item4->setCustomName("§3Steve §eMask \n§bOwner: §c$name");
					   $item4->addEnchantment(new EnchantmentInstance($e, 4));
                       $sender->getInventory()->addItem($item4);
                       $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
					   $sender->sendMessage($this->getConfig()->get("msg.shop.steve"));
                      return true;
                    }else{
                       $sender->getLevel()->addSound(new AnvilFallSound($sender));
                       $sender->sendMessage($this->getConfig()->get("msg.no-money"));
                    }
									
					break;
				case 7:
					$skeleton = $this->getConfig()->get("skeleton.price");
					if($this->eco->myMoney($sender) >= $skeleton){
										
                       $this->eco->reduceMoney($sender, $skeleton);
                       $name = $sender->getName();
                       $e = Enchantment::getEnchantment(0);
                       $item6 = Item::get(397, 0, 1);
                       $item6->setCustomName("§fSkeleton §eMask \n§bOwner: §c$name");
					   $item6->addEnchantment(new EnchantmentInstance($e, 4));
                       $sender->getInventory()->addItem($item6);
                       $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::HASTE), 22000, 2, false));
                    $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 22000, 2, false));
                    $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 22000, 0, false));
                    $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 22000, 1, false));
                       $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
					   $sender->sendMessage($this->getConfig()->get("msg.shop.skeleton"));
                      return true;
                    }else{
                       $sender->getLevel()->addSound(new AnvilFallSound($sender));
                       $sender->sendMessage($this->getConfig()->get("msg.no-money"));
                    }
									
					break;
								
					}
					
			});
			
			$money = $this->eco->myMoney($sender);
			$zombie = $this->getConfig()->get("zombie.price");
			$wither = $this->getConfig()->get("wither.price");
			$dragon = $this->getConfig()->get("dragon.price");
			$skeleton = $this->getConfig()->get("skeleton.price");
			$creeper = $this->getConfig()->get("creeper.price");
			$steve = $this->getConfig()->get("steve.price");
					
			$form->setTitle("§eMask §bShop");
			$form->setContent(str_replace(["{name}"], [$sender->getName()], "§fHello §b{name}, §fYour Money: §6{$money}§a$ \n\n§fFor know the price and the effect you will get when use the mask, you can open the §eMask §dFeatures §fmenu first"));
					
			$form->addButton("§cExit", 0);
			$form->addButton("§l§eMask §dFeatures", 1);
			$form->addButton("§l§2Zombie", 1, "https://gamepedia.cursecdn.com/minecraft_gamepedia/thumb/f/f8/Zombie_Head.png/150px-Zombie_Head.png?version=8a15fc74edd30aa4d804eb08247859a7");
			$form->addButton("§a§lCreeper", 1, "https://gamepedia.cursecdn.com/minecraft_gamepedia/thumb/9/97/Creeper_Head.png/150px-Creeper_Head.png?version=94a13fb9d962554106e25c5a777fc9fd");
			$form->addButton("§7§lWither Skeleton", 1, "https://gamepedia.cursecdn.com/minecraft_gamepedia/thumb/a/ac/Wither_Skeleton_Skull.png/150px-Wither_Skeleton_Skull.png?version=72391cd2dd387f87838d8e5af634a22f");
			$form->addButton("§c§lDragon", 1, "https://gamepedia.cursecdn.com/minecraft_gamepedia/thumb/b/b6/Dragon_Head.png/150px-Dragon_Head.png?version=0687499d687de1761e5c0319c0ef6e86");
			$form->addButton("§3§lSteve", 1);
			$form->addButton("§f§lSkeleton", 1);
					
			$form->sendToPlayer($sender);
            return $form;
    }
    
    public function FeatureMenu($sender){
        $form = new SimpleForm(function (Player $sender, $data){
			$result = $data;
			if($result === null){
			}
			switch($result){
								
				case 0:
				       $this->MaskShopForm($sender);
									
					break;
				case 1:
				       $sender->getLevel()->addSound(new AnvilFallSound($sender));
				       $sender->sendMessage($this->getConfig()->get("quit.message"));
				       
				    break;
				    
			        }
			        
		        });
		        
		    $zombie = $this->getConfig()->get("zombie.price");
			$wither = $this->getConfig()->get("wither.price");
			$dragon = $this->getConfig()->get("dragon.price");
			$skeleton = $this->getConfig()->get("skeleton.price");
			$creeper = $this->getConfig()->get("creeper.price");
			$steve = $this->getConfig()->get("steve.price"); 
			
		    $form->setTitle("§d§lEffects §bList");
            $form->setContent("§6This plugin made by §fmisael38 \n\n§fSkeleton §eMask \n§fPrice: §6$skeleton \n§dEffects: \n§e-§dHaste §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dNight Vision §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dSpeed §7(§bI§7) §c*Only For 18 Minutes \n§e-§dJump Boost §7(§bII§7) §c*Only For 18 Minutes \n\n§2Zombie §eMask \n§fPrice: §6$zombie \n§dEffects: \n§e-§dStrength §7(§bI§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dJump Boost  §7(§bI§7) \n§e-§dRegeneration §7(§bI§7) \n§e-§dFire Resistance §7(§bI§7) \n\n§aCreeper §eMask \n§fPrice: §6$creeper \n§dEffects: \n§e-§dJump Boost §7(§bII§7) \n§e-§dStrength §7(§bII§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dRegeneration §7(§bII§7) \n§e-§dFire Resistance §7(§bI§7) \n§e-§dSpeed §7(§bI§7) \n\n§7Wither Skeleton §eMask \n§fPrice: §6$wither \n§dEffects: \n§e-§dSpeed §7(§bI§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dRegeneration \n§7(§bI§7) \n§e-§dHealth Boost §7(§bI§7) \n§e-§dFire Resistance §7(§bII§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n\n§3Steve §eMask \n§fPrice: §6$steve \n§dEffects: \n§e-§dStrength §7(§bIII§7) \n§e-§dSpeed §7(§bII§7) \n§e-§dRegeneration §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n\n§cDragon §eMask \n§fPrice: §6$dragon \n§dEffects: \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dSpeed §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dAbsorption §7(§bIII§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dSaturation §7(§bIII§7) \n§e-§dRegeneration §7(§bIII§7)"); 
            $form->addButton("§l§aBACK", 1);
            $form->addButton("§l§cEXIT", 2);
            $form->sendToPlayer($sender);
	}
}
