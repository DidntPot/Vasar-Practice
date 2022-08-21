<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use Wqrro\Core;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class TempBanCommand extends PluginCommand
{

    private $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("tban", $plugin);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("cp.command.tban")) {
            $player->sendMessage("§cYou cannot execute this command.");
            return;
        }
        $this->plugin->getStaffUtils()->temporaryBanHomeForm($player);
    }
}