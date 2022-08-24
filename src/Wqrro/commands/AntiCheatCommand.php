<?php

namespace Wqrro\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use Wqrro\Core;

class AntiCheatCommand extends PluginCommand{

	private $plugin;

	public function __construct(Core $plugin){
		parent::__construct("anticheat", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("cp.command.anticheat");
		$this->setAliases(["ac"]);
	}

	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player instanceof Player){
			return;
		}
		if(!$player->hasPermission("cp.command.anticheat")){
			$player->sendMessage("§cYou can't execute this command.");
			return;
		}
		if(!$player->isAntiCheatOn()){
			$this->plugin->getStaffUtils()->anticheat($player, true);
		}else{
			$this->plugin->getStaffUtils()->anticheat($player, false);
		}
	}
}