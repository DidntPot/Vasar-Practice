<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use Wqrro\Core;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class ReplyCommand extends PluginCommand
{

    private $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("reply", $plugin);
        $this->plugin = $plugin;
        $this->setAliases(["r"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            return;
        }
        if (!$player->hasRe()) {
            $player->sendMessage("§cYou have no one to reply to.");
            return;
        }
        if (count($args) < 1) {
            $player->sendMessage("§cYou must provide a message.");
            return;
        }
        $target = $player->getRe();
        $message = implode(" ", $args);
        $sn = $player->getDisplayName();
        $tn = $target->getDisplayName();
        if ($target instanceof Player) {
            $player->sendMessage("§7(To §f" . $tn . "§7) " . $message);
            $target->sendMessage("§7(From §f" . $sn . "§7) " . $message);
            $target->setRe($player);
        }
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $online) {
            if ($online->isMessages()) {
                $online->sendMessage("§8[STAFF] §7(From §f" . $sn . " §7To §f" . $tn . "§7) " . $message);
            }
        }
    }
}