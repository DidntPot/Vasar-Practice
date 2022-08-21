<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use Wqrro\Core;
use Wqrro\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class ExecCommand extends PluginCommand
{

    private $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("exec", $plugin);
        $this->plugin = $plugin;
        $this->setPermission("cp.command.exec");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player) {
            return;
        }
        if (!$player->hasPermission("cp.command.exec")) {
            return;
        }
        Utils::offerVoteRewards(implode(" ", $args));
    }
}