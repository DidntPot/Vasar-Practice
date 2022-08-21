<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use Wqrro\Core;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class StaffCommand extends PluginCommand
{

    private $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("staff", $plugin);
        $this->plugin = $plugin;
        $this->setPermission("cp.command.staff");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            return;
        }
        if (!$player->hasPermission("cp.command.staff")) {
            $player->sendMessage("§cYou cannot execute this command.");
            return;
        }
        if ($this->plugin->getDuelHandler()->isInDuel($player) or $this->plugin->getDuelHandler()->isInBotDuel($player)) {
            $player->sendMessage("§cYou cannot use this command while in a duel.");
            return;
        }
        if (!$player->isOp()) {
            if ($player->isTagged()) {
                $player->sendMessage("§cYou cannot use this command while in combat.");
                return;
            }
        }
        if ($player->isInParty()) {
            $player->sendMessage("§cYou cannot use this command while in a party.");
            return;
        }
        if (!$player->isStaffMode()) {
            $this->plugin->getStaffUtils()->staffMode($player, true);
        } else {
            $this->plugin->getStaffUtils()->staffMode($player, false);
        }
    }
}