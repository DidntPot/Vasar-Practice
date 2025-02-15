<?php

declare(strict_types=1);

namespace Wqrro\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Skin;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\protocol\types\SkinAdapterSingleton;
use pocketmine\network\mcpe\protocol\types\SkinData;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;
use Wqrro\Core;

class NickCommand extends PluginCommand{

	private $plugin;

	public function __construct(Core $plugin){
		parent::__construct("nick", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("cp.command.nick");
	}

	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player instanceof Player){
			return;
		}
		if(!$player->hasPermission("cp.command.nick")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		if(!$player->isOp()){
			if($player->isTagged()){
				$player->sendMessage("§cYou cannot use this command while in combat.");
				return;
			}
		}
		if(!isset($args[0])){
			$player->sendMessage("§cYou must provide a nick.");
			return;
		}
		switch($args[0]){
			case "off":
				$player->setDisplayName($player->getName());
				$player->sendMessage(TF::GREEN . "Your are no longer nicked.");
				$packet = new PlayerListPacket();
				$packet->type = PlayerListPacket::TYPE_ADD;
				$packet->entries[] = PlayerListEntry::createAdditionEntry($player->getUniqueId(), $player->getId(), $player->getDisplayName(), SkinAdapterSingleton::get()->toSkinData($player->getSkin()), $player->getXuid());
				foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
					$online->sendDataPacket($packet);
				}
				break;
			default:
				$nick = $args[0];
				foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
					if(strtolower($nick) == strtolower($online->getDisplayName())){
						$player->sendMessage(TF::RED . "You cannot use that nick.");
						return;
					}
				}
				if(strlen($nick) < 3){
					$player->sendMessage(TF::RED . "Your nick must have more than 3 characters.");
					return;
				}
				if(strlen($nick) > 12){
					$player->sendMessage(TF::RED . "Your nick must not have more than 12 characters.");
					return;
				}
				$player->setDisplayName($nick);
				$player->sendMessage(TF::GREEN . "You are now nicked as " . $nick . ".");
				foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
					$entry = new PlayerListEntry();
					$entry->uuid = $player->getUniqueId();
					$packet = new PlayerListPacket();
					$packet->entries[] = $entry;
					$packet->type = PlayerListPacket::TYPE_REMOVE;
					$online->sendDataPacket($packet);
					$packet2 = new PlayerListPacket();
					$packet2->type = PlayerListPacket::TYPE_ADD;
					$packet2->entries[] = PlayerListEntry::createAdditionEntry($player->getUniqueId(), $player->getId(), $player->getDisplayName(), SkinAdapterSingleton::get()->toSkinData($player->getSkin()), "");
					$online->sendDataPacket($packet2);
				}
				break;
		}
	}
}