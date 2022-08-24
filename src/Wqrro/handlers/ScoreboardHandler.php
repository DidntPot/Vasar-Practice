<?php

declare(strict_types=1);

namespace Wqrro\handlers;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;
use pocketmine\Server;
use Wqrro\Core;
use Wqrro\Utils;

class ScoreboardHandler{

	private $plugin;
	private $scoreboard = [];
	private $main = [];
	private $duel = [];
	private $spectator = [];
	private $ffa = [];

	public function __construct(){
		$this->plugin = Core::getInstance();
	}

	public function sendMainScoreboard($player, string $title = "Practice") : void{
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			$this->removeScoreboard($player);
		}
		$this->lineTitle($player, "  §l§bVASAR§r");
		$this->lineCreate($player, 0, ("§r§r§r§r§r§r§r§7--------------------"));
		$this->lineCreate($player, 1, ("§bQueued: §f" . $this->plugin->getDuelHandler()->getNumberOfDuelsInProgress()));
		$this->lineCreate($player, 2, ("§bPlaying: §f" . $this->plugin->getDuelHandler()->getNumberOfQueuedPlayers()));
		$this->lineCreate($player, 3, ("§r"));
		$this->lineCreate($player, 4, "§o§b" . $this->plugin->getIp());
		$this->lineCreate($player, 5, ("§r§7--------------------"));
		$this->scoreboard[$player->getName()] = $player->getName();
		$this->main[$player->getName()] = $player->getName();
	}

	public function isPlayerSetScoreboard($player) : bool{
		$name = Utils::getPlayerName($player);
		return ($name !== null) and isset($this->scoreboard[$name]);
	}

	public function removeScoreboard($player){
		$player = Utils::getPlayer($player);
		$packet = new RemoveObjectivePacket();
		$packet->objectiveName = "objective";
		$player->sendDataPacket($packet);
		unset($this->scoreboard[$player->getName()]);
		unset($this->main[$player->getName()]);
		unset($this->duel[$player->getName()]);
		unset($this->spectator[$player->getName()]);
		unset($this->ffa[$player->getName()]);
	}

	public function lineTitle($player, string $title){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		$packet = new SetDisplayObjectivePacket();
		$packet->displaySlot = "sidebar";
		$packet->objectiveName = "objective";
		$packet->displayName = $title;
		$packet->criteriaName = "dummy";
		$packet->sortOrder = 0;
		$player->sendDataPacket($packet);
	}

	public function lineCreate($player, int $line, string $content){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		$packetline = new ScorePacketEntry();
		$packetline->objectiveName = "objective";
		$packetline->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
		$packetline->customName = " " . $content . "   ";
		$packetline->score = $line;
		$packetline->scoreboardId = $line;
		$packet = new SetScorePacket();
		$packet->type = SetScorePacket::TYPE_CHANGE;
		$packet->entries[] = $packetline;
		$player->sendDataPacket($packet);
	}

	public function sendFFAScoreboard($player) : void{
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			$this->removeScoreboard($player);
		}
		$this->lineTitle($player, "  §l§bVASAR§r");
		$this->lineCreate($player, 0, ("§r§r§r§r§r§r§r§7--------------------"));
		$this->lineCreate($player, 1, "§bK: §f" . $this->plugin->getDatabaseHandler()->getKills($player) . " §bD: §f" . $this->plugin->getDatabaseHandler()->getDeaths($player));
		$this->lineCreate($player, 2, "§bKDR: §f" . $this->plugin->getDatabaseHandler()->getKdr($player));
		$this->lineCreate($player, 3, "§bKillstreak: §f" . $this->plugin->getDatabaseHandler()->getKillstreak($player) . " §7(" . $this->plugin->getDatabaseHandler()->getBestKillstreak($player) . ")");
		$this->lineCreate($player, 4, "         ");
		$this->lineCreate($player, 5, "§o§b" . $this->plugin->getIp());
		$this->lineCreate($player, 6, ("§r§7--------------------"));
		$this->scoreboard[$player->getName()] = $player->getName();
		$this->ffa[$player->getName()] = $player->getName();
	}

	public function sendDuelScoreboard($player, string $type, string $queue, string $opponent) : void{
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			$this->removeScoreboard($player);
		}
		$this->lineTitle($player, "  §l§bVASAR§r");
		$this->lineCreate($player, 0, ("§r§r§r§r§r§r§r§7--------------------"));
		$this->lineCreate($player, 1, "§b" . $type . ": §f" . $queue);
		$this->lineCreate($player, 2, "    ");
		$this->lineCreate($player, 3, "§bFighting: §f" . $opponent);
		$this->lineCreate($player, 4, "§bDuration: §f00:00");
		$this->lineCreate($player, 5, "         ");
		$this->lineCreate($player, 6, "§o§b" . $this->plugin->getIp());
		$this->lineCreate($player, 7, ("§r§7--------------------"));
		$this->scoreboard[$player->getName()] = $player->getName();
		$this->duel[$player->getName()] = $player->getName();
	}

	public function sendPartyDuelScoreboard($player, string $queue, int $alive, int $playing) : void{
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			$this->removeScoreboard($player);
		}
		$this->lineTitle($player, "  §l§bVASAR§r");
		$this->lineCreate($player, 0, ("§r§r§r§r§r§r§r§7--------------------"));
		$this->lineCreate($player, 1, "§bParty: §f" . $queue);
		$this->lineCreate($player, 2, "    ");
		$this->lineCreate($player, 3, "§bAlive: §f" . $alive . "/" . $playing);
		$this->lineCreate($player, 4, "§bDuration: §f00:00");
		$this->lineCreate($player, 5, "         ");
		$this->lineCreate($player, 6, "§o§b" . $this->plugin->getIp());
		$this->lineCreate($player, 7, ("§r§7--------------------"));
		$this->scoreboard[$player->getName()] = $player->getName();
		$this->duel[$player->getName()] = $player->getName();
	}

	public function sendBotDuelScoreboard($player, string $opponent) : void{
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			$this->removeScoreboard($player);
		}
		$this->lineTitle($player, "  §l§bVASAR§r");
		$this->lineCreate($player, 0, ("§r§r§r§r§r§r§r§7--------------------"));
		$this->lineCreate($player, 1, "§bFighting: §f" . $opponent);
		$this->lineCreate($player, 2, "§bDuration: §f00:00");
		$this->lineCreate($player, 3, "         ");
		$this->lineCreate($player, 4, "§o§b" . $this->plugin->getIp());
		$this->lineCreate($player, 5, ("§r§7--------------------"));
		$this->scoreboard[$player->getName()] = $player->getName();
		$this->duel[$player->getName()] = $player->getName();
	}

	public function sendDuelSpectateScoreboard($player, string $type, string $queue, string $duelplayer, string $duelopponent) : void{
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			$this->removeScoreboard($player);
		}
		$this->lineTitle($player, "  §l§bVASAR§r");
		$this->lineCreate($player, 0, ("§r§r§r§r§r§r§r§7--------------------"));
		$this->lineCreate($player, 1, "§b" . $type . ": §f" . $queue);
		$this->lineCreate($player, 2, "    ");
		$this->lineCreate($player, 3, "§bMatch: §a" . $duelplayer . " §fvs §c" . $duelopponent);
		$this->lineCreate($player, 4, "         ");
		$this->lineCreate($player, 5, "§o§b" . $this->plugin->getIp());
		$this->lineCreate($player, 6, ("§r§7--------------------"));
		$this->scoreboard[$player->getName()] = $player->getName();
		$this->spectator[$player->getName()] = $player->getName();
	}

	public function sendPartyDuelSpectateScoreboard($player, string $queue, string $leader) : void{
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			$this->removeScoreboard($player);
		}
		$this->lineTitle($player, "  §l§bVASAR§r");
		$this->lineCreate($player, 0, ("§r§r§r§r§r§r§r§7--------------------"));
		$this->lineCreate($player, 1, "§bParty: §f" . $queue);
		$this->lineCreate($player, 2, "    ");
		$this->lineCreate($player, 3, "§bLeader: §f" . $leader);
		$this->lineCreate($player, 4, "         ");
		$this->lineCreate($player, 5, "§o§b" . $this->plugin->getIp());
		$this->lineCreate($player, 6, ("§r§7--------------------"));
		$this->scoreboard[$player->getName()] = $player->getName();
		$this->spectator[$player->getName()] = $player->getName();
	}

	public function updateMainLinePing($player){
		//
	}

	public function updateMainLineKillsDeaths($player){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			if($this->isPlayerSetFFA($player)){
				$this->lineRemove($player, 1);
				$this->lineCreate($player, 1, "§bK: §f" . $this->plugin->getDatabaseHandler()->getKills($player) . " §bD: §f" . $this->plugin->getDatabaseHandler()->getDeaths($player));
			}
		}
	}

	public function isPlayerSetFFA($player) : bool{
		$name = Utils::getPlayerName($player);
		return ($name !== null) and isset($this->ffa[$name]);
	}

	public function lineRemove($player, int $line){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		$entry = new ScorePacketEntry();
		$entry->objectiveName = "objective";
		$entry->score = $line;
		$entry->scoreboardId = $line;
		$packet = new SetScorePacket();
		$packet->type = SetScorePacket::TYPE_REMOVE;
		$packet->entries[] = $entry;
		$player->sendDataPacket($packet);
	}

	public function updateMainLineKdr($player){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			if($this->isPlayerSetFFA($player)){
				$this->lineRemove($player, 2);
				$this->lineCreate($player, 2, "§bKDR: §f" . $this->plugin->getDatabaseHandler()->getKdr($player));
			}
		}
	}

	public function updateMainLineKillstreak($player){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			if($this->isPlayerSetFFA($player)){
				$this->lineRemove($player, 3);
				$this->lineCreate($player, 3, "§bKillstreak: §f" . $this->plugin->getDatabaseHandler()->getKillstreak($player) . " §7(" . $this->plugin->getDatabaseHandler()->getBestKillstreak($player) . ")");
			}
		}
	}

	public function updateMainLineCombat($player, $timer){
		//
	}

	public function updateBotDuelDuration($player, $duration){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			if($this->isPlayerSetDuel($player)){
				$this->lineRemove($player, 2);
				$this->lineCreate($player, 2, "§bDuration: §f" . $duration);
			}
		}
	}

	public function isPlayerSetDuel($player) : bool{
		$name = Utils::getPlayerName($player);
		return ($name !== null) and isset($this->duel[$name]);
	}

	public function updateDuelDuration($player, $duration){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			if($this->isPlayerSetDuel($player)){
				$this->lineRemove($player, 4);
				$this->lineCreate($player, 4, "§bDuration: §f" . $duration);
			}
		}
	}

	public function updatePartyDuelAlive($player, $alive, $playing){
		$player = Utils::getPlayer($player);
		if(Utils::isScoreboardEnabled($player) == false){
			return;
		}
		if($this->isPlayerSetScoreboard($player)){
			if($this->isPlayerSetDuel($player)){
				$this->lineRemove($player, 3);
				$this->lineCreate($player, 3, "§bAlive: §f" . $alive . "/" . $playing);
			}
		}
	}

	public function isPlayerSetMain($player) : bool{
		$name = Utils::getPlayerName($player);
		return ($name !== null) and isset($this->main[$name]);
	}

	public function isPlayerSetSpectator($player) : bool{
		$name = Utils::getPlayerName($player);
		return ($name !== null) and isset($this->spectator[$name]);
	}
}
