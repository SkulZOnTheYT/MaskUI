<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskShopUI;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerArmorEquipEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\scheduler\Task;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\libs\cooldogedev\libSQL\context\ClosureContext;
use SkulZOnTheYT\MaskShopUI\Form\{Form, SimpleForm};

class Main extends PluginBase implements Listener {
    
    /** @var Main $instance */
    private static $instance;
	
	public $plugin;

	public function onEnable() : void{
	    self::$instance = $this;
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $this->saveDefaultConfig();
      $this->getResource("config.yml");
		
      if($this->getConfig()->get("config-ver") != 2)
        {
            $this->getLogger()->info("MaskShopUI's config is NOT up to date. Please delete the config.yml and restart the server or the plugin may not work properly.");
        }
    }
	
	public static function getInstance() : self{
	    return self::$instance;
	}
	
	const ZOMBIE_HEAD = 397:2;
	const CREEPER_HEAD = 397:4;
	const WITHER_SKELETON_SKULL = 397:1;
	const DRAGON_HEAD = 397:5;
	const STEVE_HEAD = 397:3;
	const SKELETON_SKULL = 397:0;
     
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
        if($sender instanceof Player){
          if($cmd->getName() == "mask"){
            $this->MaskShopForm($sender);
          }
        } else {
          $sender->sendMessage($this->getConfig()->get("only-ingame"));
        }
        return true;
    }
    
  public function MaskShopForm($sender){
      BedrockEconomyAPI::legacy()->getPlayerBalance(
			$sender->getName(),
			ClosureContext::create(
				function (?int $balance) use ($sender): void {
				  $form = new SimpleForm(function (Player $sender, int $data = null){
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0:
				          $sender->sendMessage($this->getConfig()->get("quit.message"));
		    	   		break;
			         	case 1:
				          $this->FeatureMenu($sender);
  				      break;
                case 2:
                  $zombie = $this->getConfig()->get("zombie.price");
                  $name = $sender->getName();
                  $item1 = ItemFactory::getInstance()->get(397, 2, 1);
                  $item1->setCustomName("§2Zombie §eMask \n§bOwner: §c$name");
                  $sender->getInventory()->addItem($item1);
                  BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), (int) $zombie);
					        $sender->sendMessage($this->getConfig()->get("msg.shop.zombie"));
                  return true;
                  break;
                case 3:
                  $creeper = $this->getConfig()->get("creeper.price");
                  $name = $sender->getName();
                  $item2 = ItemFactory::getInstance()->get(397, 4, 1);
                  $item2->setCustomName("§aCreeper §eMask \n§bOwner: §c$name");
                  $sender->getInventory()->addItem($item2);
                  BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), (int) $creeper);
					        $sender->sendMessage($this->getConfig()->get("msg.shop.creeper"));
                  return true;
                  break;
                case 4:
                  $wither = $this->getConfig()->get("wither.price");
                  $name = $sender->getName();
                  $item3 = ItemFactory::getInstance()->get(397, 1, 1);
                  $item3->setCustomName("§7Wither §eMask \n§bOwner: §c$name");
                  $sender->getInventory()->addItem($item3);
                  BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), (int) $wither);
					        $sender->sendMessage($this->getConfig()->get("msg.shop.wither"));
                  return true; 
                  break;
                case 5:
                  $dragon = $this->getConfig()->get("dragon.price");
                  $name = $sender->getName();
                  $item4 = ItemFactory::getInstance()->get(397, 5, 1);
                  $item4->setCustomName("§cDragon §eMask \n§bOwner: §c$name");
                  $sender->getInventory()->addItem($item4);
                  BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), (int) $dragon);
					        $sender->sendMessage($this->getConfig()->get("msg.shop.dragon"));
                  return true; 
                  break;
                case 6:
                  $steve = $this->getConfig()->get("steve.price");
                  $name = $sender->getName();
                  $item5 = ItemFactory::getInstance()->get(397, 3, 1);
                  $item5->setCustomName("§3Steve §eMask \n§bOwner: §c$name");
                  $sender->getInventory()->addItem($item5);
                  BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), (int) $steve);
					        $sender->sendMessage($this->getConfig()->get("msg.shop.steve"));
                  return true; 
                  break;
                case 7:
                  $skeleton = $this->getConfig()->get("skeleton.price");
                  $name = $sender->getName();
                  $item6 = ItemFactory::getInstance()->get(397, 0, 1);
                  $item6->setCustomName("§fSkeleton §eMask \n§bOwner: §c$name");
                  $sender->getInventory()->addItem($item6);
                  BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), (int) $skeleton);
					        $sender->sendMessage($this->getConfig()->get("msg.shop.skeleton"));
                  return true;
                  break;
            }
        });
      $zombie = $this->getConfig()->get("zombie.price");
			$wither = $this->getConfig()->get("wither.price");
			$dragon = $this->getConfig()->get("dragon.price");
			$skeleton = $this->getConfig()->get("skeleton.price");
			$creeper = $this->getConfig()->get("creeper.price");
			$steve = $this->getConfig()->get("steve.price");
					
			$form->setTitle("§eMask §bShop");
			$form->setContent(str_replace(["{name}"], [$sender->getName()], "§fHello §b{name}\n§fFor know the price and the effect you will get when use the mask, you can open the §eMask §dFeatures §fmenu first"));
					
			$form->addButton("§cExit", 0);
			$form->addButton("§l§eMask §dFeatures", 1);
			$form->addButton("§l§2Zombie", 1, "https://gamepedia.cursecdn.com/minecraft_gamepedia/thumb/f/f8/Zombie_Head.png/150px-Zombie_Head.png?version=8a15fc74edd30aa4d804eb08247859a7");
			$form->addButton("§a§lCreeper", 1, "https://gamepedia.cursecdn.com/minecraft_gamepedia/thumb/9/97/Creeper_Head.png/150px-Creeper_Head.png?version=94a13fb9d962554106e25c5a777fc9fd");
			$form->addButton("§7§lWither Skeleton", 1, "https://gamepedia.cursecdn.com/minecraft_gamepedia/thumb/a/ac/Wither_Skeleton_Skull.png/150px-Wither_Skeleton_Skull.png?version=72391cd2dd387f87838d8e5af634a22f");
			$form->addButton("§c§lDragon", 1, "https://gamepedia.cursecdn.com/minecraft_gamepedia/thumb/b/b6/Dragon_Head.png/150px-Dragon_Head.png?version=0687499d687de1761e5c0319c0ef6e86");
			$form->addButton("§3§lSteve", 1);
			$form->addButton("§f§lSkeleton", 1);
			$form->sendToPlayer($sender);
				},
			)
		);
    }
    
    public function FeatureMenu($sender){
        $form = new SimpleForm(function (Player $sender, int $data = null){
			$result = $data;
			if($result === null){
			  return true;
			}
			switch($result){
				case 0:
				       $this->MaskShopForm($sender);
					break;
				case 1:
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
      $form->setContent("§6This plugin made by §fSkulZOnTheYT and Kylan1940\n\n§fSkeleton §eMask \n§fPrice: §6$skeleton \n§dEffects: \n§e-§dHaste §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dNight Vision §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dSpeed §7(§bI§7) §c*Only For 18 Minutes \n§e-§dJump Boost §7(§bII§7) §c*Only For 18 Minutes \n\n§2Zombie §eMask \n§fPrice: §6$zombie \n§dEffects: \n§e-§dStrength §7(§bI§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dJump Boost  §7(§bI§7) \n§e-§dRegeneration §7(§bI§7) \n§e-§dFire Resistance §7(§bI§7) \n\n§aCreeper §eMask \n§fPrice: §6$creeper \n§dEffects: \n§e-§dJump Boost §7(§bII§7) \n§e-§dStrength §7(§bII§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dRegeneration §7(§bII§7) \n§e-§dFire Resistance §7(§bI§7) \n§e-§dSpeed §7(§bI§7) \n\n§7Wither Skeleton §eMask \n§fPrice: §6$wither \n§dEffects: \n§e-§dSpeed §7(§bI§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dRegeneration \n§7(§bI§7) \n§e-§dHealth Boost §7(§bI§7) \n§e-§dFire Resistance §7(§bII§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n\n§3Steve §eMask \n§fPrice: §6$steve \n§dEffects: \n§e-§dStrength §7(§bIII§7) \n§e-§dSpeed §7(§bII§7) \n§e-§dRegeneration §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n\n§cDragon §eMask \n§fPrice: §6$dragon \n§dEffects: \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dSpeed §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dAbsorption §7(§bIII§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dSaturation §7(§bIII§7) \n§e-§dRegeneration §7(§bIII§7)"); 
      $form->addButton("§l§aBACK", 1);
      $form->addButton("§l§cEXIT", 2);
      $form->sendToPlayer($sender);
	}
	
          public function onRun(int $currentTick) {
          foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
              $helmet = $player->getArmorInventory()->getHelmet();
              if($helmet !== null && in_array($helmet->getId(), [Item::DRAGON_HEAD, Item::CREEPER_HEAD, Item::SKELETON_SKULL, Item::WITHER_SKELETON_SKULL, Item::PLAYER_HEAD, Item::ZOMBIE_HEAD])) {
                     switch($helmet->getId()) {
                            case Item::DRAGON_HEAD:
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
                            case Item::CREEPER_HEAD:
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 0, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
                                break;
                            case Item::SKELETON_SKULL:
                            case Item::WITHER_SKELETON_SKULL:
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
                                break;
                            case Item::STEVE_HEAD:
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
                                $sender->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
                                break;
			    case Item::ZOMBIE_HEAD:
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
                                $playerr->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
                                break;
		     }
               }
            }
        }
	
     public function onPlayerArmorEquip(PlayerArmorEquipEvent $event) {
         $player = $event->getPlayer();
         $item = $event->getItem();
         $slot = $event->getSlot();

         if($slot === 0 && in_array($item->getId(), [Item::DRAGON_HEAD, Item::CREEPER_HEAD, Item::SKELETON_SKULL, Item::WITHER_SKELETON_SKULL, Item::PLAYER_HEAD, Item::ZOMBIE_HEAD])) {
            $player->sendMessage(TF::GREEN . "Mask effect is working!!!");
        }
    }
}
