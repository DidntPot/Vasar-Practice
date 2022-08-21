<?php

declare(strict_types=1);

namespace Wqrro\tasks;

use Wqrro\Core;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\scheduler\Task;

class VanishTask extends Task
{

    public function __construct(Core $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $tick): void
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if ($player->spawned) {
                if ($player->isVanished()) {
                    $player->sendTip("You are currently in §bVANISH");
                    foreach ($this->plugin->getServer()->getOnlinePlayers() as $online) {
                        if ($online->hasPermission("cp.bypass.vanishsee")) {
                            $online->showPlayer($player);
                        } else {
                            $online->hidePlayer($player);
                            $entry = new PlayerListEntry();
                            $entry->uuid = $player->getUniqueId();
                            $packet = new PlayerListPacket();
                            $packet->entries[] = $entry;
                            $packet->type = PlayerListPacket::TYPE_REMOVE;
                            $online->sendDataPacket($packet);
                        }
                    }
                }
            }
        }
    }
}