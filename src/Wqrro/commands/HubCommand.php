<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use Wqrro\Core;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class HubCommand extends PluginCommand
{

    private $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("hub", $plugin);
        $this->plugin = $plugin;
        $this->setAliases(["lobby", "spawn"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
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
        if ($this->plugin->getDuelHandler()->isInDuel($player) or $this->plugin->getDuelHandler()->isInBotDuel($player)) {
            $player->sendMessage("§cYou cannot use this command while in a duel.");
            return;
        }
        $duel = $this->plugin->getDuelHandler()->getDuel($player);
        if (!is_null($duel)) $duel->removeSpectator($player);
        $player->sendTo(0, true);
    }
}