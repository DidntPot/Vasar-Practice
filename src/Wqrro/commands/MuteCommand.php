<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use Wqrro\Core;

class MuteCommand extends PluginCommand{

	private $plugin;

	public function __construct(Core $plugin){
		parent::__construct("mute", $plugin);
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("cp.command.mute")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		$this->plugin->getStaffUtils()->muteHomeForm($player);
	}
}