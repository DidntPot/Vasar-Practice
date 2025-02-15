<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use Wqrro\Core;

class AliasCommand extends PluginCommand{

	private $plugin;

	public function __construct(Core $plugin){
		parent::__construct("alias", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("cp.command.alias");
	}

	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("cp.command.alias")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		if(!isset($args[0])){
			$player->sendMessage("§cYou must provide a player.");
			return;
		}
		if($this->plugin->getServer()->getPlayer($args[0]) === null){
			$player->sendMessage("§cPlayer not found.");
			return;
		}
		$target = $this->plugin->getServer()->getPlayer($args[0]);
		//if($target instanceof Player and $target->isOnline){
		$ip = $target->getAddress();
		$cid = $target->getClientId();
		$contentsip = file_get_contents($this->plugin->getDataFolder() . "aliases/" . $ip, true);
		$listip = implode(", ", array_unique(explode(", ", $contentsip)));

		$contentscid = file_get_contents($this->plugin->getDataFolder() . "aliases/" . $cid, true);
		$listcid = implode(", ", array_unique(explode(", ", $contentscid)));

		$player->sendMessage("§bAccounts under " . $target->getName() . "'s IP: \n§7" . $listip);
		$player->sendMessage("§bAccounts under " . $target->getName() . "'s Client ID: \n§7" . $listcid);
		//}
	}
}