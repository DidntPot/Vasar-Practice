<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use Wqrro\Core;

class KickAllCommand extends PluginCommand{

	private $plugin;

	public function __construct(Core $plugin){
		parent::__construct("kickall", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("cp.command.kickall");
	}

	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->isOp()){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
			if(!$online->isOp()){
				$online->kick("Everyone has been kicked, you may rejoin soon.", false);
			}
		}
	}
}