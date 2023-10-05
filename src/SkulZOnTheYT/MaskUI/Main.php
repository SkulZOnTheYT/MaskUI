<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use SkulZOnTheYT\MaskUI\Menu\UI;
use pocketmine\scheduler\Task;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\block\utils\MobHeadType;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\entity\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
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
	
	public static function getInstance() : Self
   {
	    return self::$instance;
	}

    public function getUI(): UI
      {
        $ui = new UI($this);
        return $ui->getInstance();
       }
       
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
        if($sender instanceof Player){
          if($cmd->getName() == "mask"){
              $this->getUI->MaskShopForm($sender);
          }
        } else {
          $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
  }
}
