<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\item\Item;
use pocketmine\block\MobHead;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\utils\MobHeadType;
use pocketmine\inventory\ArmorInventory;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use cooldogedev\BedrockEconomy\BedrockEconomy;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\api\version\LegacyBEAPI;
use cooldogedev\BedrockEconomy\api\legacy\ClosureContext;
use SkulZOnTheYT\MaskUI\Form\{Form, SimpleForm};

class Main extends PluginBase implements Listener {
    
    /** @var Main $instance */
    private static $instance;
	
	public $plugin;
	private $activeEffects;

	public function onEnable() : void{
	    self::$instance = $this;
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $this->activeEffects = [];
      $this->saveDefaultConfig();
      $this->getResource("config.yml");
    }
	
	public static function getInstance() : self{
	    return self::$instance;
	}

  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
        if($sender instanceof Player){
          if($cmd->getName() == "mask"){
              $this->MaskShopForm($sender);
          }
        } else {
          $sender->sendMessage("This command can only be used in-game.");
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
		  $sender->sendMessage($this->getConfig()->get("quit.message"));
		  $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
		    break;
		case 1:
		  $this->FeatureMenu($sender);
		  $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
  		    break;
                case 2:
                   $name = $sender->getName();
		   $amountToSubtract = 5000;
                   BedrockEconomyAPI::legacy()->getPlayerBalance(
                     $name,
                       ClosureContext::create(
                       function (?int $balance) use ($name, $amountToSubtract): void {
			 if ($player instanceof Player) {
                          if ($balance !== null && $balance >= $amountToSubtract) {
                           BedrockEconomyAPI::legacy()->subtractFromPlayerBalance(
                             $name,
                              $amountToSubtract,
                               ClosureContext::create(
                               function (bool $wasUpdated) use ($name): void {
                               if ($wasUpdated) {
                                $player->sendMessage($this->getConfig()->get("msg.shop.skeleton"));
                              }  
                             }
                            )
                           );
	                } else {
                          $player->sendMessage(this->getConfig()->get("msg.no-money"));
                        }
                       }
		      }
                     )
                    );
		    $idInfo = new BlockIdentifier(BlockTypeIds::MOB_HEAD);
		    $breakInfo = new BlockBreakInfo(0);
		    $typeInfo = new BlockTypeInfo($breakInfo);
		    $name1 = ("Skeleton Skull");
                    $sk = new MobHead($idInfo, $name1, $typeInfo);
		    $sk->setMobHeadType(MobHeadType::SKELETON());
                    $mobHeadType = $sk->getMobHeadType();
	            $item1 = $sk->asItem();
		    $item1->setCustomName("§fSkeleton §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item1);
                    $sender->sendMessage($this->getConfig()->get("msg.shop.skeleton"));
		    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
                  return true;
                case 3:
                  if (BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), 10000, ClosureContext::create(function (bool $wasUpdated): void {var_dump($wasUpdated);},))) {
                    $name = $sender->getName();
                    $idInfo = new BlockIdentifier(BlockTypeIds::MOB_HEAD);
		    $breakInfo = new BlockBreakInfo(0);
		    $typeInfo = new BlockTypeInfo($breakInfo);
		    $name2 = ("Zombie Head");
                    $zo = new MobHead($idInfo, $name2, $typeInfo);
		    $zo->setMobHeadType(MobHeadType::ZOMBIE());
                    $mobHeadType = $zo->getMobHeadType();
	            $item2 = $zo->asItem();
		    $item2->setCustomName("§2Zombie §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item2);
		    $sender->sendMessage($this->getConfig()->get("msg.shop.zombie"));
		    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		    $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
                  }
                  return true;
                case 4:
                  if (BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), 15000, ClosureContext::create(function (bool $wasUpdated): void {var_dump($wasUpdated);},))) {
                    $name = $sender->getName();
                    $idInfo = new BlockIdentifier(BlockTypeIds::MOB_HEAD);
		    $breakInfo = new BlockBreakInfo(0);
		    $typeInfo = new BlockTypeInfo($breakInfo);
		    $name3 = ("Creeper Head");
                    $cr = new MobHead($idInfo, $name3, $typeInfo);
		    $cr->setMobHeadType(MobHeadType::CREEPER());
                    $mobHeadType = $cr->getMobHeadType();
	            $item3 = $cr->asItem();
		    $item3->setCustomName("§aCreeper §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item3);
		    $sender->sendMessage($this->getConfig()->get("msg.shop.creeper"));
		    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		    $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
                  }
                  return true;
                case 5:
                  if (BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), 20000, ClosureContext::create(function (bool $wasUpdated): void {var_dump($wasUpdated);},))) {
                    $name = $sender->getName();
                    $idInfo = new BlockIdentifier(BlockTypeIds::MOB_HEAD);
		    $breakInfo = new BlockBreakInfo(0);
		    $typeInfo = new BlockTypeInfo($breakInfo);
		    $name4 = ("Wither Skeleton Skull");
                    $wi = new MobHead($idInfo, $name4, $typeInfo);
		    $wi->setMobHeadType(MobHeadType::WITHER_SKELETON());
                    $mobHeadType = $wi->getMobHeadType();
	            $item4 = $wi->asItem();
		    $item4->setCustomName("§7Wither §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item4);
                    $sender->sendMessage($this->getConfig()->get("msg.shop.wither"));
		    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		    $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
                  }
                  return true;
                case 6:
	          if (BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), 25000, ClosureContext::create(function (bool $wasUpdated): void {var_dump($wasUpdated);},))) {
                    $name = $sender->getName();
                    $idInfo = new BlockIdentifier(BlockTypeIds::MOB_HEAD);
		    $breakInfo = new BlockBreakInfo(0);
		    $typeInfo = new BlockTypeInfo($breakInfo);
		    $name5 = ("Player Head");
                    $st = new MobHead($idInfo, $name5, $typeInfo);
		    $st->setMobHeadType(MobHeadType::PLAYER());
                    $mobHeadType = $st->getMobHeadType();
	            $item5 = $st->asItem();
		    $item5->setCustomName("§3Steve §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item5);
                    $sender->sendMessage($this->getConfig()->get("msg.shop.steve"));
		    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		    $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
                  }
                  return true;
                case 7:
                  if (BedrockEconomyAPI::legacy()->subtractFromPlayerBalance($sender->getName(), 30000, ClosureContext::create(function (bool $wasUpdated): void {var_dump($wasUpdated);},))) {
                    $name = $sender->getName();
                    $idInfo = new BlockIdentifier(BlockTypeIds::MOB_HEAD);
		    $breakInfo = new BlockBreakInfo(0);
		    $typeInfo = new BlockTypeInfo($breakInfo);
		    $name6 = ("Dragon Head");
                    $dr = new MobHead($idInfo, $name6, $typeInfo);
		    $dr->setMobHeadType(MobHeadType::DRAGON());
                    $mobHeadType = $dr->getMobHeadType();
	            $item6 = $dr->asItem();
		    $item6->setCustomName("§cDragon §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item6);
                    $sender->sendMessage($this->getConfig()->get("msg.shop.dragon"));
		    $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-money"));
		    $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
                  }
                  return true;
            }
        });
			$form->setTitle($this->getConfig()->get("title.ui.main"));
			$form->setContent(str_replace(["{name}"], [$sender->getName()], "§fHello §b{name}\n§fFor know the effect you will get when use the mask, you can open the §eMask §dFeatures §fmenu first"));
			$form->addButton("§cExit", 0, "textures/ui/cancel");
			$form->addButton("§l§eMask §dFeatures", 0, "textures/items/nether_stars");
			$form->addButton("§f§lSkeleton" , 0, "textures/items/skeleton_skull");
                        $form->addButton("§l§2Zombie" , 0, "textures/items/zombie_head");
			$form->addButton("§a§lCreeper" , 0, "textures/items/creeper_head");
			$form->addButton("§7§lWither Skeleton" , 0, "textures/items/wither_skeleton_skull");
			$form->addButton("§3§lSteve" , 0, "textures/items/player_head");
			$form->addButton("§c§lDragon" , 0, "textures/items/dragon_head");
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
				   $sender->sendMessage($this->getConfig()->get("quit.message"));
				   $sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
				  break;
			      }
		      });
      $form->setTitle($this->getConfig()->get("title.ui.feature"));
      $form->setContent("§6This plugin made by §fSkulZOnTheYT and Kylan1940\n\n§fSkeleton §eMask \n§dEffects: \n§e-§dHaste §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dNight Vision §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dSpeed §7(§bI§7) §c*Only For 18 Minutes \n§e-§dJump Boost §7(§bII§7) §c*Only For 18 Minutes \n\n§2Zombie §eMask \n§dEffects: \n§e-§dStrength §7(§bI§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dJump Boost  §7(§bI§7) \n§e-§dRegeneration §7(§bI§7) \n§e-§dFire Resistance §7(§bI§7) \n\n§aCreeper §eMask \n§dEffects: \n§e-§dJump Boost §7(§bII§7) \n§e-§dStrength §7(§bII§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dRegeneration §7(§bII§7) \n§e-§dFire Resistance §7(§bI§7) \n§e-§dSpeed §7(§bI§7) \n\n§7Wither Skeleton §eMask \n§dEffects: \n§e-§dSpeed §7(§bI§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dRegeneration \n§7(§bI§7) \n§e-§dHealth Boost §7(§bI§7) \n§e-§dFire Resistance §7(§bII§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n\n§3Steve §eMask \n§dEffects: \n§e-§dStrength §7(§bIII§7) \n§e-§dSpeed §7(§bII§7) \n§e-§dRegeneration §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n\n§cDragon §eMask \n§dEffects: \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dSpeed §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dAbsorption §7(§bIII§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dSaturation §7(§bIII§7) \n§e-§dRegeneration §7(§bIII§7)"); 
      $form->addButton("§l§aBACK", 1);
      $form->addButton("§l§cEXIT", 2);
      $form->sendToPlayer($sender);
    	}

     public function ArmorInventory(Player $player): void {
       if ($player instanceof Player) {
	 $armorInventory = $player->getArmorInventory();
	     
	   $idInfo = new BlockIdentifier(BlockTypeIds::MOB_HEAD);
	   $breakInfo = new BlockBreakInfo(0);
	   $typeInfo = new BlockTypeInfo($breakInfo);
	     
	     $name1 = ("Skeleton Skull");
             $sk = new MobHead($idInfo, $name1, $typeInfo);
	     $sk->setMobHeadType(MobHeadType::SKELETON());
             $mobHeadType = $sk->getMobHeadType();
	     $item1 = $sk->asItem();
	     
	       $name2 = ("Zombie Head");
               $zo = new MobHead($idInfo, $name2, $typeInfo);
	       $zo->setMobHeadType(MobHeadType::ZOMBIE());
               $mobHeadType = $zo->getMobHeadType();
	       $item2 = $zo->asItem();

	         $name3 = ("Creeper Head");
                 $cr = new MobHead($idInfo, $name3, $typeInfo);
		 $cr->setMobHeadType(MobHeadType::CREEPER());
                 $mobHeadType = $cr->getMobHeadType();
	         $item3 = $cr->asItem();
	     
	           $name4 = ("Wither Skeleton Skull");
                   $wi = new MobHead($idInfo, $name4, $typeInfo);
		   $wi->setMobHeadType(MobHeadType::WITHER_SKELETON());
                   $mobHeadType = $wi->getMobHeadType();
	           $item4 = $wi->asItem();
	     
	              $name5 = ("Player Head");
                      $st = new MobHead($idInfo, $name5, $typeInfo);
		      $st->setMobHeadType(MobHeadType::PLAYER());
                      $mobHeadType = $st->getMobHeadType();
	              $item5 = $st->asItem();
	     
	                $name6 = ("Dragon Head");
                        $dr = new MobHead($idInfo, $name6, $typeInfo);
		        $dr->setMobHeadType(MobHeadType::DRAGON());
                        $mobHeadType = $dr->getMobHeadType();
	                $item6 = $dr->asItem();
	     
	 if ($armorInventory->getHelmet() === $item1) {
            $this->applySkeletonHeadEffects($player);
            } else {
              $player->getEffects()->remove($this->activeEffects[$player->getName()]);
        }
	if ($armorInventory->getHelmet() === $item2) {
           $this->applyZombieHeadEffects($player);
           } else {
              $player->getEffects()->remove($this->activeEffects[$player->getName()]);
        }
	if ($armorInventory->getHelmet() === $item3) {
           $this->applyCreeperHeadEffects($player);
           } else {
              $player->getEffects()->remove($this->activeEffects[$player->getName()]);
        }
	if ($armorInventory->getHelmet() === $item4) {
           $this->applyWitherSkeletonHeadEffects($player);
           } else {
              $player->getEffects()->remove($this->activeEffects[$player->getName()]);
        } 
	if ($armorInventory->getHelmet() === $item5) {
           $this->applySteveHeadEffects($player);
           } else {
              $player->getEffects()->remove($this->activeEffects[$player->getName()]);
        }
	if ($armorInventory->getHelmet() === $item6) {
           $this->applyDragonHeadEffects($player);
           } else {
              $player->getEffects()->remove($this->activeEffects[$player->getName()]);
        }
     }
  }

      public function applySkeletonHeadEffects($player): void {
       if ($player instanceof Player) {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
    }
}

      public function applyZombieHeadEffects($player): void {
       if ($player instanceof Player) {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
    }
}

      public function applyCreeperHeadEffects($player): void {
       if ($player instanceof Player) {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
    }
}

     public function applyWitherSkeletonHeadEffects($player): void {
      if ($player instanceof Player) {
        $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 1, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 0, false));
        $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 0, false));
    }
}

      public function applySteveHeadEffects($player): void {
       if ($player instanceof Player) {
       $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 220, 1, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), 220, 4, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 220, 2, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 220, 3, false));
       $player->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 220, 2, false));
    }
}

      public function applyDragonHeadEffects($player): void {
	if ($player instanceof Player) {
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
  }
}
