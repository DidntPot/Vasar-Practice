<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use Wqrro\Core;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class AnnounceCommand extends PluginCommand
{

    private $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("announce", $plugin);
        $this->plugin = $plugin;
        $this->setPermission("cp.command.announce");
        $this->setAliases(["ano"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("cp.command.announce")) {
            $player->sendMessage("§cYou cannot execute this command.");
            return;
        }
        if ($this->plugin->getDatabaseHandler()->isMuted($player->getName())) {
            $player->sendMessage("§cYou are muted.");
            return;
        }
        $message = implode(" ", $args);
        $this->plugin->getServer()->broadcastMessage($message);
    }
}