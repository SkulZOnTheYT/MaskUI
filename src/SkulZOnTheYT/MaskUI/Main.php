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
use pocketmine\block\utils\MobHeadType;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use SkulZOnTheYT\MaskUI\Form\{Form, SimpleForm};
use SkulZOnTheYT\MaskUI\EffectTask;

class Main extends PluginBase implements Listener {
    
    /** @var Main $instance */
    private static $instance;
	
	public $plugin;

	public function onEnable() : void{
	    self::$instance = $this;
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $this->saveDefaultConfig();
      $this->getResource("config.yml");
      $server = Server::getInstance();
        foreach ($server->getOnlinePlayers() as $player) {
           $this->getScheduler()->scheduleRepeatingTask(new EffectTask($player), 20);
	}
    }
	
	public static function getInstance() : self{
	    return self::$instance;
	}
     
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
        if($sender instanceof Player){
          if($cmd->getName() == "mask"){
            if ($sender -> hasPermission("mask.ui")) {
              $this->MaskShopForm($sender);
            } else {
              $sender->sendMessage($this->getConfig()->get("msg.no-permission"));
            }
          }
        } else {
          $sender->sendMessage($this->getConfig()->get("only-ingame"));
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
		    	   		break;
			         	case 1:
				          $this->FeatureMenu($sender);
  				      break;
                case 2:
                  if ($sender -> hasPermission("mask.skeleton")) {
                    $name = $sender->getName();
                    $sk = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::SKELETON(), ("Skeleton Skull"));
	            $item1 = $sk->asItem();
                    $item1->setCustomName("§fSkeleton §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item1);
                    $sender->sendMessage($this->getConfig()->get("msg.shop.skeleton")); 
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-permission"));
                  }
                  return true;
                case 3:
                  if ($sender -> hasPermission("mask.zombie")) {
                    $name = $sender->getName();
                    $zo = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::ZOMBIE(), ("Zombie Head"));
	            $item2 = $zo->asItem();
                    $item2->setCustomName("§2Zombie §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item2);
		                $sender->sendMessage($this->getConfig()->get("msg.shop.zombie"));
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-permission"));
                  }
                  return true;
                case 4:
                  if ($sender -> hasPermission("mask.creeper")) {
                    $name = $sender->getName();
                    $cr = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::CREEPER(), ("Creeper Head"));
	            $item3 = $cr->asItem();
                    $item3->setCustomName("§aCreeper §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item3);
		                $sender->sendMessage($this->getConfig()->get("msg.shop.creeper"));
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-permission"));
                  }
                  return true;
                case 5:
                  if ($sender -> hasPermission("mask.wither")) {
                    $name = $sender->getName();
                    $wi = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::WITHER_SKELETON(), ("Wither Skull"));
	            $item4 = $wi->asItem();
                    $item4->setCustomName("§7Wither §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item4);
                    $sender->sendMessage($this->getConfig()->get("msg.shop.wither"));
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-permission"));
                  }
                  return true;
                case 6:
	                if ($sender -> hasPermission("mask.steve")) {
                    $name = $sender->getName();
                    $st = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::PLAYER(), ("Player Head"));
	            $item5 = $st->asItem();
                    $item5->setCustomName("§3Steve §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item5);
                    $sender->sendMessage($this->getConfig()->get("msg.shop.steve"));
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-permission"));
                  }
                  return true;
                case 7:
                  if ($sender -> hasPermission("mask.dragon")) {
                    $name = $sender->getName();
                    $dr = new MobHead(BlockTypeIds::MOB_HEAD, MobHeadType::DRAGON(), ("Dragon Head"));
	            $item6 = $dr->asItem();
                    $item6->setCustomName("§cDragon §eMask \n§bOwner: §c$name");
                    $sender->getInventory()->addItem($item6);
                    $sender->sendMessage($this->getConfig()->get("msg.shop.dragon"));
                  } else {
                    $sender->sendMessage($this->getConfig()->get("msg.no-permission"));
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
					break;
				case 1:
				   $sender->sendMessage($this->getConfig()->get("quit.message"));
				  break;
			      }
		      });
      $form->setTitle($this->getConfig()->get("title.ui.feature"));
      $form->setContent("§6This plugin made by §fSkulZOnTheYT and Kylan1940\n\n§fSkeleton §eMask \n§dEffects: \n§e-§dHaste §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dNight Vision §7(§bIII§7) §c*Only For 18 Minutes \n§e-§dSpeed §7(§bI§7) §c*Only For 18 Minutes \n§e-§dJump Boost §7(§bII§7) §c*Only For 18 Minutes \n\n§2Zombie §eMask \n§dEffects: \n§e-§dStrength §7(§bI§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dJump Boost  §7(§bI§7) \n§e-§dRegeneration §7(§bI§7) \n§e-§dFire Resistance §7(§bI§7) \n\n§aCreeper §eMask \n§dEffects: \n§e-§dJump Boost §7(§bII§7) \n§e-§dStrength §7(§bII§7) \n§e-§dNight Vision §7(§bII§7) \n§e-§dRegeneration §7(§bII§7) \n§e-§dFire Resistance §7(§bI§7) \n§e-§dSpeed §7(§bI§7) \n\n§7Wither Skeleton §eMask \n§dEffects: \n§e-§dSpeed §7(§bI§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dRegeneration \n§7(§bI§7) \n§e-§dHealth Boost §7(§bI§7) \n§e-§dFire Resistance §7(§bII§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n\n§3Steve §eMask \n§dEffects: \n§e-§dStrength §7(§bIII§7) \n§e-§dSpeed §7(§bII§7) \n§e-§dRegeneration §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n\n§cDragon §eMask \n§dEffects: \n§e-§dFire Resistance §7(§bIV§7) \n§e-§dJump Boost §7(§bIII§7) \n§e-§dHealth Boost §7(§bV§7) \n§e-§dSpeed §7(§bIII§7) \n§e-§dNight Vision §7(§bIII§7) \n§e-§dAbsorption §7(§bIII§7) \n§e-§dStrength §7(§bIII§7) \n§e-§dSaturation §7(§bIII§7) \n§e-§dRegeneration §7(§bIII§7)"); 
      $form->addButton("§l§aBACK", 1);
      $form->addButton("§l§cEXIT", 2);
      $form->sendToPlayer($sender);
    	}
    }
