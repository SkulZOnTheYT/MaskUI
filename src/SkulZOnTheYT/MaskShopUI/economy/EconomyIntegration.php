<?php

declare(strict_types=1);

namespace AlexPads\CustomShopUI\economy;

use Closure;
use pocketmine\player\Player;

interface EconomyIntegration{

	/**
	 * @param array $config
	 *
	 * @phpstan-param array<string, mixed> $config
	 */
	public function init(array $config) : void;

	/**
	 * Returns how much money the player has.
	 *
	 * @param Player $player
	 * @param Closure $callback
	 *
	 * @phpstan-param Closure(float) : void $callback
	 */
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

	/**
	 * Formats money.
	 *
	 * @param float $money
	 * @return string
	 */
	public function formatMoney(float $money) : string;
}