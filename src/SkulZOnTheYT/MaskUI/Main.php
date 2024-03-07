<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\scheduler\Task;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\utils\MobHeadType;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\world\sound\EndermanTeleportSound;
use cooldogedev\BedrockEconomy\BedrockEconomy;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\api\type\LegacyBEAPI;
use cooldogedev\BedrockEconomy\api\util\ClosureContext;
use SkulZOnTheYT\MaskUI\Form\{Form, SimpleForm};

class Main extends PluginBase implements Listener {
    
    /** @var Main $instance */
    private static $instance;
	
	public $plugin;

	public function onEnable() : void{
	    self::$instance = $this;
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $this->saveDefaultConfig();
      $this->getResource("config.yml");
      $this->getScheduler()->scheduleRepeatingTask(new EffectTask(), 20);
}
	
	public static function getInstance() : self{
	    return self::$instance;
	}

  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
        if($sender instanceof Player){
          if($cmd->getName() == "mask"){
	    if(isset($args[0])) {
            $arg = strtolower($args[0]);
            switch($arg) {
		case "open":
		    $this->MaskShopForm($sender);
		    break;
                case "wiki":
                    $this->FeatureMenu($sender);
                    break;
		case "help":
		    $sender->sendMessage("§ashowing list command MaskUI \n------------------------ \n§f/mask open - open mask shop ui \n/mask wiki - open wiki ui \n/mask help - list command \n/mask github - send message source code in github");
		    break;
		case "github":
		    $sender->sendMessage("You can see in https://github.com/SkulZOnTheYT/MaskUI");
		    break;
                default:
                    $sender->sendMessage("please enter the correct options!!");
                    break;
             }
           } else {
             $sender->sendMessage("use /mask help for information commands!!");
          } 
	 }
        } else {
          $sender->sendMessage("This command can only be used in-game...");
        }
        return true;
    }
    
  public function MaskShopForm($sender){
	$form = new SimpleForm(function (Player $sender, int $data = null){
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0:
		  if ($sender instanceof Player) { 
                   $name = $sender->getName();
		   $amountToSubtract = $this->getConfig()->get("skeleton.price");
                   BedrockEconomyAPI::legacy()->getPlayerBalance(
                     $name,
                       ClosureContext::create(
                       function (?int $balance) use ($sender, $name, $amountToSubtract): void {
                         if ($balance !== null && $balance >= $amountToSubtract) {
                           BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
                             $name,
                              $amountToSubtract,
                               ClosureContext::create(
                               function (bool $wasUpdated) use ($sender, $name): void {
                                if ($wasUpdated) {
	                            $item1 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::SKELETON())->asItem();
		                    $item1->setCustomName("§fSkeleton §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item1);
                                    $sender->sendMessage($this->getConfig()->get("msg.shop.skeleton"));
				    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->getConfig()->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		        }
		       }
		      )
		     );
		    }
                  return true;
                case 1:
		  if ($sender instanceof Player) {
                   $name = $sender->getName();
		   $amountToSubtract = $this->getConfig()->get("zombie.price");
                   BedrockEconomyAPI::legacy()->getPlayerBalance(
                     $name,
                       ClosureContext::create(
                       function (?int $balance) use ($sender, $name, $amountToSubtract): void {
                         if ($balance !== null && $balance >= $amountToSubtract) {
                           BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
                             $name,
                              $amountToSubtract,
                               ClosureContext::create(
                               function (bool $wasUpdated) use ($sender, $name): void {
                                if ($wasUpdated) {
	                            $item2 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::ZOMBIE())->asItem();
		                    $item2->setCustomName("§2Zombie §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item2);
		                    $sender->sendMessage($this->getConfig()->get("msg.shop.zombie"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->getConfig()->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
		  }
                  return true;
                case 2:
		  if ($sender instanceof Player) {
                   $name = $sender->getName();
		   $amountToSubtract = $this->getConfig()->get("creeper.price");
                   BedrockEconomyAPI::legacy()->getPlayerBalance(
                     $name,
                       ClosureContext::create(
                       function (?int $balance) use ($sender, $name, $amountToSubtract): void {
                         if ($balance !== null && $balance >= $amountToSubtract) {
                           BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
                             $name,
                              $amountToSubtract,
                               ClosureContext::create(
                               function (bool $wasUpdated) use ($sender, $name): void {
                                if ($wasUpdated) {
	                            $item3 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::CREEPER())->asItem();
		                    $item3->setCustomName("§aCreeper §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item3);
		                    $sender->sendMessage($this->getConfig()->get("msg.shop.creeper"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->getConfig()->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
		  }
                  return true;
		case 3:
		  if ($sender instanceof Player) {
                   $name = $sender->getName();
		   $amountToSubtract = $this->getConfig()->get("piglin.price");
                   BedrockEconomyAPI::legacy()->getPlayerBalance(
                     $name,
                       ClosureContext::create(
                       function (?int $balance) use ($sender, $name, $amountToSubtract): void {
                         if ($balance !== null && $balance >= $amountToSubtract) {
                           BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
                             $name,
                              $amountToSubtract,
                               ClosureContext::create(
                               function (bool $wasUpdated) use ($sender, $name): void {
                                if ($wasUpdated) {
				 $item4 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PIGLIN())->asItem();
				 $item4->setCustomName("§6Piglin §eMask \n§bOwner: §c$name");
                                 $sender->getInventory()->addItem($item4);
                                 $sender->sendMessage($this->getConfig()->get("msg.shop.piglin"));
		                 $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->getConfig()->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
	          }
                  return true;
		case 4:
		  if ($sender instanceof Player) {
	           $name = $sender->getName();
		   $amountToSubtract = $this->getConfig()->get("steve.price");
                   BedrockEconomyAPI::legacy()->getPlayerBalance(
                     $name,
                       ClosureContext::create(
                       function (?int $balance) use ($sender, $name, $amountToSubtract): void {
                         if ($balance !== null && $balance >= $amountToSubtract) {
                           BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
                             $name,
                              $amountToSubtract,
                               ClosureContext::create(
                               function (bool $wasUpdated) use ($sender, $name): void {
                                if ($wasUpdated) {
	                            $item5 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PLAYER())->asItem();
		                    $item5->setCustomName("§3Steve §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item5);
                                    $sender->sendMessage($this->getConfig()->get("msg.shop.steve"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->getConfig()->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
		  }
                  return true;
                case 5:
		  if ($sender instanceof Player) {
                   $name = $sender->getName();
		   $amountToSubtract = $this->getConfig()->get("wither.price");
                   BedrockEconomyAPI::legacy()->getPlayerBalance(
                     $name,
                       ClosureContext::create(
                       function (?int $balance) use ($sender, $name, $amountToSubtract): void {
                         if ($balance !== null && $balance >= $amountToSubtract) {
                           BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
                             $name,
                              $amountToSubtract,
                               ClosureContext::create(
                               function (bool $wasUpdated) use ($sender, $name): void {
                                if ($wasUpdated) {
	                            $item6 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::WITHER_SKELETON())->asItem();
		                    $item6->setCustomName("§7Wither §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item6);
                                    $sender->sendMessage($this->getConfig()->get("msg.shop.wither"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->getConfig()->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
	          }
                  return true;
                case 6:
		  if ($sender instanceof Player) {
                   $name = $sender->getName();
		   $amountToSubtract = $this->getConfig()->get("dragon.price");
                   BedrockEconomyAPI::legacy()->getPlayerBalance(
                     $name,
                       ClosureContext::create(
                       function (?int $balance) use ($sender, $name, $amountToSubtract): void {
                         if ($balance !== null && $balance >= $amountToSubtract) {
                           BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
                             $name,
                              $amountToSubtract,
                               ClosureContext::create(
                               function (bool $wasUpdated) use ($sender, $name): void {
                                if ($wasUpdated) {
	                            $item7 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem();
		                    $item7->setCustomName("§cDragon §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item7);
                                    $sender->sendMessage($this->getConfig()->get("msg.shop.dragon"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->getConfig()->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
		  }
                  return true;
		case 7:
		  $sender->sendMessage($this->getConfig()->get("quit.message"));
		  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		    break;
            }
        });

	                $zombie = $this->getConfig()->get("zombie.price");
			$wither = $this->getConfig()->get("wither.price");
			$dragon = $this->getConfig()->get("dragon.price");
			$skeleton = $this->getConfig()->get("skeleton.price");
			$creeper = $this->getConfig()->get("creeper.price");
			$steve = $this->getConfig()->get("steve.price");
	                $piglin = $this->getConfig()->get("piglin.price");
	  
			$form->setTitle($this->getConfig()->get("title.ui.main"));
			$form->setContent(str_replace(["{name}"], [$sender->getName()], "§fHello §b{name}\n§fFor know the effect you will get when use the mask, you can open the §eMask §dFeatures §fmenu first"));
			$form->addButton("§f§lSkeleton \n§fPrice: §6$skeleton", 1, "https://cdn.imgbin.com/24/7/2/imgbin-minecraft-pocket-edition-skeleton-video-game-mob-minecraft-skeleton-VpUb2HtYSA1Jcptn7RT3PbSdt.jpg");
                        $form->addButton("§l§2Zombie \n§fPrice: §6$zombie", 1, "https://minecraft-heads.com/media/k2/items/cache/8cdfe61457f16442e8acf54df5822c40_XS.jpg");			
			$form->addButton("§a§lCreeper \n§fPrice: §6$creeper", 1, "https://static.wikia.nocookie.net/minecraft_gamepedia/images/e/ed/Creeper_Head_%288%29.png/revision/latest/scale-to-width/360?cb=20220101051304");
	                $form->addButton("§6§lPiglin \n§fPrice: §6$piglin", 1, "https://minecraftfaces.com/wp-content/bigfaces/big-piglin-face.jpg");
	                $form->addButton("§3§lSteve \n§fPrice: §6$steve", 0, "textures/ui/icon_steve");
			$form->addButton("§5§lWither Skeleton \n§fPrice: §6$wither", 1, "https://minecraft-heads.com/media/k2/items/cache/eefd4fe8f589e64e0e66a4f2937ae4ae_XS.jpg");
			$form->addButton("§c§lDragon \n§fPrice: §6$dragon", 1, "https://static.wikia.nocookie.net/minecraft/images/9/9d/DragonHead.png/revision/latest?cb=20190915182439");
	                $form->addButton("§cExit", 0, "textures/ui/cancel");
	                $form->sendToPlayer($sender);
	}
	
	public function FeatureMenu($sender){
        $form = new SimpleForm(function (Player $sender, int $data = null){
			$result = $data;
			if($result === null){
			  return true;
			}
			switch($result){
				case 0:
				   $sender->sendMessage($this->getConfig()->get("quit.message"));
				   $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
				  break;
			      }
		      });
          $form->setTitle($this->getConfig()->get("title.ui.feature"));
          $form->setContent("§6This plugin made by §fSkulZOnTheYT and Kylan1940\n\n§fSkeleton §eMask \n§dEffects: \n§e-§dHaste §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dNight Vision §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dSpeed §7(§bI§7) §c*Only For 18 Minutes \n§e-§dJump Boost §7(§bII§7) §c*Only For 18 Minutes \n\n§2Zombie §eMask \n§dEffects: \n§e-§dStrength §7(§bI§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dJump Boost  §7(§bI§7) \n§e-§dRegeneration §7(§bI§7) \n§e-§dFire Resistance §7(§bI§7) \n\n§aCreeper §eMask \n§dEffects: \n§e-§dJump Boost §7(§bII§7) \n§e-§dStrength §7(§bII§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dRegeneration §7(§bII§7) \n§e-§dFire Resistance §7(§bI§7) \n§e-§dSpeed §7(§bI§7) \n\n§7Wither Skeleton §eMask \n§dEffects: \n§e-§dSpeed §7(§bI§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dRegeneration \n§7(§bI§7) \n§e-§dHealth Boost §7(§bI§7) \n§e-§dFire Resistance §7(§bII§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n\n§3Steve §eMask \n§dEffects: \n§e-§dStrength §7(§bIII§7) \n§e-§dSpeed §7(§bII§7) \n§e-§dRegeneration §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n\n§cDragon §eMask \n§dEffects: \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dSpeed §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dAbsorption §7(§bIII§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dSaturation §7(§bIII§7) \n§e-§dRegeneration §7(§bIII§7)"); 
          $form->addButton("§l§cEXIT", 0, "textures/ui/cancel");
          $form->sendToPlayer($sender);
	}
}
