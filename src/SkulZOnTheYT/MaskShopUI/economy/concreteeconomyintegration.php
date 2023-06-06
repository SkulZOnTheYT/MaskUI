<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskShopUI\economy;

require_once 'economyintegration.php';
use SkulZOnTheYT\MaskShopUI\economy\EconomyIntegration;

class ConcreteEconomyIntegration implements EconomyIntegration {
  
	public function getMoney(Player $player, Closure $callback) : void;

	/**
	 * Adds a given amount of money to the player.
	 *
	 * @param Player $player
	 * @param float $money
	 */
	public function addMoney(Player $player, float $money) : void;

	/**
	 * Removes a given amount of money from the player.
	 *
	 * @param Player $player
	 * @param Closure $callback
	 *
	 * @phpstan-param Closure(bool) : void $callback
	 */
	public function removeMoney(Player $player, float $money, Closure $callback) : void;

}
