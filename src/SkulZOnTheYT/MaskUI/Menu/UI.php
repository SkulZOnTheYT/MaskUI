<?php

namespace SkulZOnTheYT\MaskUi\Menu;

use pocketmine\Server;
use pocketmine\player\Player;

use SkulZOnTheYT\MaskUI\Form\{Form, SimpleForm};
use cooldogedev\BedrockEconomy\BedrockEconomy;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\api\version\LegacyBEAPI;
use cooldogedev\BedrockEconomy\api\legacy\ClosureContext;
use SkulZOnTheYT\MaskUI\Main;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\scheduler\ClosureTask;

class UI
{
  
  /** @var Instance */
  private static $instance;
 
  
  public function __construct(Main $source)
  {
    self::$instance = $this;
    $this->config = $source->getInstance()->getConfigFile();
  }
  
  public static function getInstance(): UI
  {
    return self::$instance;
  }
  
  public function MaskShopForm($sender){
	$form = new SimpleForm(function (Player $sender, int $data = null){
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0:
		  $sender->sendMessage($this->config->get("quit.message"));
		  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		    break;
		case 1:
		  $this->FeatureMenu($sender);
		  $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
  		    break;
                case 2:
		  if ($sender instanceof Player) { 
                   $name = $sender->getName();
		   $amountToSubtract = $this->config->get("skeleton.price");
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
	                            $item1 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::ZOMBIE())->asItem();
		                    $item1->setCustomName("§fSkeleton §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item1);
                                    $sender->sendMessage($this->config->get("msg.shop.skeleton"));
				    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->config->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->config->get("msg.no-money"));
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
		   $amountToSubtract = $this->config->get("zombie.price");
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
		                    $sender->sendMessage($this->config->get("msg.shop.zombie"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->config->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->config->get("msg.no-money"));
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
		   $amountToSubtract = $this->config->get("creeper.price");
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
		                    $sender->sendMessage($this->config->get("msg.shop.creeper"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->config->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->config->get("msg.no-money"));
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
		   $amountToSubtract = $this->config->get("piglin.price");
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
                                 $sender->sendMessage($this->config->get("msg.shop.piglin"));
		                 $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->config->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->config->get("msg.no-money"));
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
		   $amountToSubtract = $this->config->get("wither.price");
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
	                            $item4 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::WITHER_SKELETON())->asItem();
		                    $item4->setCustomName("§7Wither §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item4);
                                    $sender->sendMessage($this->config->get("msg.shop.wither"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->config->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->config->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
	          }
                  return true;
                case 7:
		  if ($sender instanceof Player) {
	           $name = $sender->getName();
		   $amountToSubtract = $this->config->get("steve.price");
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
                                    $sender->sendMessage($this->config->get("msg.shop.steve"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->config->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->config->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
		  }
                  return true;
                case 8:
		  if ($sender instanceof Player) {
                   $name = $sender->getName();
		   $amountToSubtract = $this->config->get("dragon.price");
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
	                            $item6 = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::DRAGON())->asItem();
		                    $item6->setCustomName("§cDragon §eMask \n§bOwner: §c$name");
                                    $sender->getInventory()->addItem($item6);
                                    $sender->sendMessage($this->config->get("msg.shop.dragon"));
		                    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
			     } else {
				  $sender->sendMessage($this->config->get("msg.transactions-failed"));
		                  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
			    }
			   }
	                  ) 
			 );
			} else {
			     $sender->sendMessage($this->config->get("msg.no-money"));
		             $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		       }
		      }
		    )
		   );
		  }
                  return true;
            }
        });
			$form->setTitle($this->config->get("title.ui.main"));
			$form->setContent(str_replace(["{name}"], [$sender->getName()], "§fHello §b{name}\n§fFor know the effect you will get when use the mask, you can open the §eMask §dFeatures §fmenu first"));
			$form->addButton("§cExit", 0, "textures/ui/cancel");
			$form->addButton("§l§eMask §dFeatures");
			$form->addButton("§f§lSkeleton");
            $form->addButton("§l§2Zombie");
			$form->addButton("§a§lCreeper");
	        $form->addButton("§6§lPiglin");
			$form->addButton("§5§lWither Skeleton");
			$form->addButton("§3§lSteve");
			$form->addButton("§c§lDragon");
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
				   $this->MaskShopForm($sender);
				   $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
					break;
				case 1:
				   $sender->sendMessage($this->config->get("quit.message"));
				   $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
				  break;
			      }
		      });
      $form->setTitle($this->config->get("title.ui.feature"));
      $form->setContent("§6This plugin made by §fSkulZOnTheYT and Kylan1940\n\n§fSkeleton §eMask \n§dEffects: \n§e-§dHaste §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dNight Vision §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dSpeed §7(§bI§7) §c*Only For 18 Minutes \n§e-§dJump Boost §7(§bII§7) §c*Only For 18 Minutes \n\n§2Zombie §eMask \n§dEffects: \n§e-§dStrength §7(§bI§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dJump Boost  §7(§bI§7) \n§e-§dRegeneration §7(§bI§7) \n§e-§dFire Resistance §7(§bI§7) \n\n§aCreeper §eMask \n§dEffects: \n§e-§dJump Boost §7(§bII§7) \n§e-§dStrength §7(§bII§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dRegeneration §7(§bII§7) \n§e-§dFire Resistance §7(§bI§7) \n§e-§dSpeed §7(§bI§7) \n\n§7Wither Skeleton §eMask \n§dEffects: \n§e-§dSpeed §7(§bI§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dRegeneration \n§7(§bI§7) \n§e-§dHealth Boost §7(§bI§7) \n§e-§dFire Resistance §7(§bII§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n\n§3Steve §eMask \n§dEffects: \n§e-§dStrength §7(§bIII§7) \n§e-§dSpeed §7(§bII§7) \n§e-§dRegeneration §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n\n§cDragon §eMask \n§dEffects: \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dSpeed §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dAbsorption §7(§bIII§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dSaturation §7(§bIII§7) \n§e-§dRegeneration §7(§bIII§7)"); 
      $form->addButton("§l§aBACK", 1);
      $form->addButton("§l§cEXIT", 2);
      $form->sendToPlayer($sender);
    	}
}
