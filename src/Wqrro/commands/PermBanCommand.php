<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use Wqrro\Core;

class PermBanCommand extends PluginCommand{

	private $plugin;

	public function __construct(Core $plugin){
		parent::__construct("pban", $plugin);
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("cp.command.pban")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		$this->plugin->getStaffUtils()->permanentBanHomeForm($player);
	}
}