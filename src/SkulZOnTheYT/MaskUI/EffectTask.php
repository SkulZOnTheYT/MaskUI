<?php

declare(strict_types=1);

namespace SkulZOnTheYT\MaskUI;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use SkulZOnTheYT\MaskUI\Main;

class EffectTask extends Task {

    private $player;
    
    public const DRAGON_HEAD = ItemFactory::getInstance()->get(397, 5, 1);
    public const CREEPER_HEAD = ItemFactory::getInstance()->get(397, 4, 1);
    public const WITHER_SKELETON_SKULL = ItemFactory::getInstance()->get(397, 1, 1);
    public const STEVE_HEAD = ItemFactory::getInstance()->get(397, 3, 1);
    public const SKELETON_SKULL = ItemFactory::getInstance()->get(397, 0, 1);
    public const ZOMBIE_HEAD = ItemFactory::getInstance()->get(397, 2, 1);

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function onRun(int $currentTick) {
        $helmet = $this->player->getArmorInventory()->getHelmet();

        if ($helmet !== null) {
            switch ($helmet->getId()) {
                case self::DRAGON_HEAD:
                    $this->applyDragonHeadEffects();
                    break;
                case self::CREEPER_HEAD:
                    $this->applyCreeperHeadEffects();
                    break;
                case self::SKELETON_SKULL:
                case self::WITHER_SKELETON_SKULL:
                    $this->applySkeletonHeadEffects();
                    break;
                case self::STEVE_HEAD:
                    $this->applySteveHeadEffects();
                    break;
                case self::ZOMBIE_HEAD:
                    $this->applyZombieHeadEffects();
                    break;
            }
        }
    }

    private function applyDragonHeadEffects(): void {
        $this->player->getEffects()->add(new VanillaEffects(FireResistance::class, 220, 3, false));
        $this->player->getEffects()->add(new VanillaEffects(JumpBoost::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(HealthBoost::class, 220, 4, false));
        $this->player->getEffects()->add(new VanillaEffects(Speed::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(NightVision::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(Strength::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(Saturation::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(Regeneration::class, 220, 2, false));
    }

    private function applyCreeperHeadEffects(): void {
        $this->player->getEffects()->add(new VanillaEffects(Speed::class, 220, 0, false));
        $this->player->getEffects()->add(new VanillaEffects(Strength::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(Regeneration::class, 220, 0, false));
        $this->player->getEffects()->add(new VanillaEffects(HealthBoost::class, 220, 0, false));
        $this->player->getEffects()->add(new VanillaEffects(FireResistance::class, 220, 1, false));
        $this->player->getEffects()->add(new VanillaEffects(JumpBoost::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(NightVision::class, 220, 2, false));
    }

    private function applySkeletonHeadEffects(): void {
        $this->player->getEffects()->add(new VanillaEffects(Strength::class, 220, 0, false));
        $this->player->getEffects()->add(new VanillaEffects(NightVision::class, 220, 1, false));
        $this->player->getEffects()->add(new VanillaEffects(JumpBoost::class, 220, 0, false));
        $this->player->getEffects()->add(new VanillaEffects(Regeneration::class, 220, 0, false));
        $this->player->getEffects()->add(new VanillaEffects(FireResistance::class, 220, 0, false));
    }

    private function applySteveHeadEffects(): void {
        $this->player->getEffects()->add(new VanillaEffects(Strength::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(Speed::class, 220, 1, false));
        $this->player->getEffects()->add(new VanillaEffects(Regeneration::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(HealthBoost::class, 220, 4, false));
        $this->player->getEffects()->add(new VanillaEffects(NightVision::class, 220, 2, false));
        $this->player->getEffects()->add(new VanillaEffects(FireResistance::class, 220, 3, false));
        $this->player->getEffects()->add(new VanillaEffects(JumpBoost::class, 220, 2, false));
    }

    private function applyZombieHeadEffects(): void {
        $this->player->getEffects()->add(new VanillaEffects(JumpBoost::class, 220, 1, false));
        $this->player->getEffects()->add(new VanillaEffects(Strength::class, 220, 1, false));
        $this->player->getEffects()->add(new VanillaEffects(NightVision::class, 220, 1, false));
        $this->player->getEffects()->add(new VanillaEffects(Regeneration::class, 220, 1, false));
        $this->player->getEffects()->add(new VanillaEffects(FireResistance::class, 220, 0, false));
        $this->player->getEffects()->add(new VanillaEffects(Speed::class, 220, 0, false));
    }
}
