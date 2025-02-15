<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use Wqrro\Core;

class PingCommand extends PluginCommand{

	private $plugin;

	public function __construct(Core $plugin){
		parent::__construct("ping", $plugin);
		$this->plugin = $plugin;
		$this->setAliases(["ms"]);
	}

	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!isset($args[0]) and $player instanceof Player){
			$player->sendMessage("§aYour ping is " . $player->getPing() . "ms.");
			return;
		}
		if(isset($args[0]) and $target = $this->plugin->getServer()->getPlayer($args[0]) === null){
			$player->sendMessage("§cPlayer not found.");
			return;
		}
		$target = $this->plugin->getServer()->getPlayer($args[0]);
		if($target instanceof Player){
			$player->sendMessage("§a" . $target->getName() . "'s ping is " . $target->getPing() . "ms.");
		}
	}
}