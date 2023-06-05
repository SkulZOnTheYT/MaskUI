<?php

declare(strict_types=1);

namespace AlexPads\CustomShopUI\economy;

use InvalidArgumentException;
use AlexPads\CustomShopUI\Main;
use pocketmine\utils\Utils;

final class EconomyManager{

	/**
	 * @var string[]
	 *
	 * @phpstan-var array<class-string<EconomyIntegration>>
	 */
	private static array $integrations = [];

	private static EconomyIntegration $integrated;

	public static function init(Main $loader) : void{
		self::registerDefaults();

		$config = $loader->getConfig();
		$plugin = $config->getNested("economy.plugin", "BedrockEconomy");
		if(!isset(self::$integrations[$plugin])){
			throw new InvalidArgumentException("{$loader->getName()} does not support the economy plugin {$plugin}");
		}

		self::$integrated = new self::$integrations[$plugin]();
		self::$integrated->init($config->getNested("economy." . $plugin, []));
	}

	private static function registerDefaults() : void{
		self::register("BedrockEconomy", BedrockEconomyIntegration::class);
		self::register("EconomyAPI", EconomyAPIIntegration::class);
	}

	/**
	 * @param string $plugin
	 * @param string $class
	 *
	 * @phpstan-param class-string<EconomyIntegration> $class
	 */
	public static function register(string $plugin, string $class) : void{
		Utils::testValidInstance($class, EconomyIntegration::class);
		self::$integrations[$plugin] = $class;
	}

	public static function get() : EconomyIntegration{
		return self::$integrated;
	}
}