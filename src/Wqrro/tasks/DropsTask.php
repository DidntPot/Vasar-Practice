<?php

declare(strict_types=1);

namespace Wqrro\tasks;

use Wqrro\bots\{EasyBot, HackerBot, HardBot, MediumBot};
use Wqrro\Core;
use Wqrro\entities\{DefaultPotion, FastPotion, Hook, Pearl};
use pocketmine\entity\Creature;
use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\entity\projectile\SplashPotion;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class DropsTask extends Task
{

    public function __construct(Core $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $tick): void
    {
        foreach ($this->plugin->getServer()->getLevels() as $levels) {
            foreach ($levels->getEntities() as $entity) {
                if (!$entity instanceof Player and !$entity instanceof Creature and !$entity instanceof EasyBot and !$entity instanceof MediumBot and !$entity instanceof HardBot and !$entity instanceof HackerBot and !$entity instanceof Arrow and !$entity instanceof EnderPearl and !$entity instanceof SplashPotion and !$entity instanceof Pearl and !$entity instanceof FastPotion and !$entity instanceof DefaultPotion and !$entity instanceof Hook) {
                    $entity->close();
                }
            }
        }
    }
}