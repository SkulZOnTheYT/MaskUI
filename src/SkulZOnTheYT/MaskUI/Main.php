<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\utils\MobHeadType;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\world\sound\EndermanTeleportSound;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use SkulZOnTheYT\MaskUI\Form\SimpleForm;

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

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		if(!$sender instanceof Player){
			$sender->sendMessage("This command can only be used in-game...");
			return true;
		}

		if(strtolower($cmd->getName()) === "mask"){
			if(!isset($args[0])){
				$sender->sendMessage("§eUse §f/mask help §efor a list of commands.");
				return true;
			}

			switch(strtolower($args[0])){
				case "open":
					$this->MaskShopForm($sender);
					break;

				case "wiki":
					$this->FeatureMenu($sender);
					break;

				case "help":
					$sender->sendMessage("§aMaskUI Command List\n------------------------\n"
						. "§f/mask open §7- Open the Mask Shop UI\n"
						. "§f/mask wiki §7- Open the Mask Wiki UI\n"
						. "§f/mask help §7- Show command list\n"
						. "§f/mask github §7- Get the GitHub source link");
					break;

				case "github":
					$sender->sendMessage("§aSource Code: §bhttps://github.com/SkulZOnTheYT/MaskUI");
					break;

				default:
					$sender->sendMessage("§cInvalid argument! Use §f/mask help §cfor command info.");
					break;
			}
		}
		return true;
	}
    
  	public function MaskShopForm(Player $sender) : void {
    	$form = new SimpleForm(function (Player $sender, ?int $data) {
			if ($data === null) {
				return;
			}

			$name = $sender->getName();
			$xuid = $sender->getXuid();

			$prices = [
				0 => ["skeleton.price", MobHeadType::SKELETON(), "msg.shop.skeleton"],
				1 => ["zombie.price", MobHeadType::ZOMBIE(), "msg.shop.zombie"],
				2 => ["creeper.price", MobHeadType::CREEPER(), "msg.shop.creeper"],
				3 => ["piglin.price", MobHeadType::PIGLIN(), "msg.shop.piglin"],
				4 => ["steve.price", MobHeadType::PLAYER(), "msg.shop.steve"],
				5 => ["wither.price", MobHeadType::WITHER_SKELETON(), "msg.shop.wither"],
				6 => ["dragon.price", MobHeadType::DRAGON(), "msg.shop.dragon"],
			];

			if ($data === 7) {
				$sender->sendMessage($this->getConfig()->get("quit.message"));
				$sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
				return;
			}

			if (!isset($prices[$data])) {
				return;
			}

			[$priceKey, $mobType, $msgKey] = $prices[$data];
			$amountToSubtract = $this->getConfig()->get($priceKey);

			BedrockEconomyAPI::CLOSURE()->get(
				xuid: $xuid,
				username: $name,
				onSuccess: function (array $result) use ($sender, $xuid, $name, $mobType, $msgKey, $amountToSubtract): void {
					if ($result["amount"] >= $amountToSubtract) {
						BedrockEconomyAPI::CLOSURE()->subtract(
							xuid: $xuid,
							username: $name,
							amount: $amountToSubtract,
							decimals: 0,
							onSuccess: function () use ($sender, $name, $mobType, $msgKey): void {
								$item = VanillaBlocks::MOB_HEAD()->setMobHeadType($mobType)->asItem();
								$item->setCustomName("§fMask \n§bOwner: §c$name");
								$sender->getInventory()->addItem($item);
								$sender->sendMessage($this->getConfig()->get($msgKey));
								$sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
							},
							onError: function (\Throwable $e) use ($sender): void {
								$sender->sendMessage($this->getConfig()->get("msg.transactions-failed"));
								$sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
							}
						);
					} else {
						$sender->sendMessage($this->getConfig()->get("msg.no-money"));
						$sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
					}
				},
				onError: function (\Throwable $e) use ($sender): void {
					$sender->sendMessage("§cDatabase error: " . $e->getMessage());
					$sender->getWorld()->addSound($sender->getPosition(), new AnvilFallSound());
				}
			);
		});

		$zombie   = $this->getConfig()->get("zombie.price");
		$wither   = $this->getConfig()->get("wither.price");
		$dragon   = $this->getConfig()->get("dragon.price");
		$skeleton = $this->getConfig()->get("skeleton.price");
		$creeper  = $this->getConfig()->get("creeper.price");
		$steve    = $this->getConfig()->get("steve.price");
		$piglin   = $this->getConfig()->get("piglin.price");

		$form->setTitle($this->getConfig()->get("title.ui.main"));
		$form->setContent(str_replace(
			["{name}"],
			[$sender->getName()],
			"§fHello §b{name}\n§fFor know the effect you will get when use the mask, you can open the §eMask §dFeatures §fmenu first"
		));

		$form->addButton("§f§lSkeleton \n§fPrice: §6$skeleton", 1, "https://raw.githubusercontent.com/SkulZOnTheYT/MaskUI/main/resources/Skeleton.jpg");
		$form->addButton("§l§2Zombie \n§fPrice: §6$zombie", 1, "https://raw.githubusercontent.com/SkulZOnTheYT/MaskUI/main/resources/Zombie.png");			
		$form->addButton("§a§lCreeper \n§fPrice: §6$creeper", 1, "https://raw.githubusercontent.com/SkulZOnTheYT/MaskUI/main/resources/creeper.jpg");
		$form->addButton("§6§lPiglin \n§fPrice: §6$piglin", 1, "https://raw.githubusercontent.com/SkulZOnTheYT/MaskUI/main/resources/piglin.jpg");
		$form->addButton("§3§lSteve \n§fPrice: §6$steve", 1, "https://raw.githubusercontent.com/SkulZOnTheYT/MaskUI/main/resources/steve.png");
		$form->addButton("§5§lWither Skeleton \n§fPrice: §6$wither", 1, "https://raw.githubusercontent.com/SkulZOnTheYT/MaskUI/main/resources/wither.png");
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
