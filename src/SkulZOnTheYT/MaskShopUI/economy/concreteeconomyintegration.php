<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskShopUI\economy;

require_once 'economyintegration.php';
use SkulZOnTheYT\MaskShopUI\economy\EconomyIntegration;

class ConcreteEconomyIntegration implements EconomyIntegration {
  
	public function getMoney() : void;

	public function addMoney() : void;

	public function removeMoney() : void;

}
