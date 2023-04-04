<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskShopUI\libs\libEco;

use Closure;
use cooldogedev\BedrockEconomy\libs\cooldogedev\libSQL\context\ClosureContext;
use SkulZOnTheYT\MaskShopUI\libs\libEco\Utils\Utils;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\Server as PMServer;

final class libEco
{
    /**
     * @return array<string, object>
     */
    private static function getEconomy(): array
    {
        $api = PMServer::getInstance()->getPluginManager()->getPlugin('EconomyAPI');
        if ($api !== null) {
            return [Utils::ECONOMYAPI, $api];
        } else {
            $api = PMServer::getInstance()->getPluginManager()->getPlugin('BedrockEconomy');
            if ($api !== null) {
                return [Utils::BEDROCKECONOMYAPI, $api];
            } else{
                return [null];
            }
        }
    }
    
    public function isInstall(): bool
    {
        return !is_null($this->getEconomy()[0]);
    }

    /**
     * @return int
     */
    public static function myMoney(Player $player, Closure $callback): void
    {
        if (self::getEconomy()[0] === Utils::ECONOMYAPI) {
            $money = self::getEconomy()[1]->myMoney($player);
            assert(is_float($money));
            $callback($money);
        } elseif (self::getEconomy()[0] === Utils::BEDROCKECONOMYAPI) {
            self::getEconomy()[1]->getAPI()->getPlayerBalance($player->getName(), ClosureContext::create(static function (?int $balance) use ($callback): void {
                $callback($balance ?? 0);
            }));
        }
    }

    public static function addMoney(Player $player, int $amount): void
    {
        if (self::getEconomy()[0] === Utils::ECONOMYAPI) {
            self::getEconomy()[1]->addMoney($player, $amount);
        } elseif (self::getEconomy()[0] === Utils::BEDROCKECONOMYAPI) {
            self::getEconomy()[1]->getAPI()->addToPlayerBalance($player->getName(), (int) $amount);
        }
    }

    public static function reduceMoney(Player $player, int $amount, Closure $callback): void
    {
        if (self::getEconomy()[0] === Utils::ECONOMYAPI) {
            $callback(self::getEconomy()[1]->reduceMoney($player, $amount) === EconomyAPI::RET_SUCCESS);
        } elseif (self::getEconomy()[0] === Utils::BEDROCKECONOMYAPI) {
            self::getEconomy()[1]->getAPI()->subtractFromPlayerBalance($player->getName(), (int) ceil($amount), ClosureContext::create(static function (bool $success) use ($callback): void {
                $callback($success);
            }));
        }
    }
}