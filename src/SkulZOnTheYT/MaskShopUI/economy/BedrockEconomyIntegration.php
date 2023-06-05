<?php

declare(strict_types=1);

namespace AlexPads\CustomShopUI\economy;

use Closure;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\BedrockEconomy;
use cooldogedev\BedrockEconomy\libs\cooldogedev\libSQL\context\ClosureContext;
use InvalidArgumentException;
use pocketmine\player\Player;
use AlexPads\CustomShopUI\Main;
use pocketmine\Server;
use function assert;

final class BedrockEconomyIntegration implements EconomyIntegration{

	private BedrockEconomy $plugin;

	public function __construct(){
		/** @var BedrockEconomy|null $plugin */
		$plugin = Server::getInstance()->getPluginManager()->getPlugin("BedrockEconomy");
		if($plugin === null){
			throw new InvalidArgumentException("BedrockEconomy plugin was not found");
		}

		$this->plugin = $plugin;
	}

    public function init(array $config) : void{
	}

	public function getMoney(Player $player, Closure $callback) : void{
		BedrockEconomyAPI::getInstance()->getPlayerBalance($player->getName(), ClosureContext::create(static function(?int $balance) use($callback) : void{
			$callback($balance ?? 0);
		}));
	}

	public function addMoney(Player $player, float $money) : void{
		BedrockEconomyAPI::getInstance()->addToPlayerBalance($player->getName(), (int) ceil($money));
	}

	//Thanks for the Repair @cooldogedev
	public function removeMoney(Player $player, float $money, Closure $callback) : void{
		BedrockEconomyAPI::getInstance()->subtractFromPlayerBalance($player->getName(), (int) ceil($money), ClosureContext::create(static function(bool $success) use($callback) : void{
			$callback($success);
		}));
	}

	public function formatMoney(float $money) : string{
		return $this->plugin->getCurrencyManager()->getSymbol() . number_format($money);
	}
}