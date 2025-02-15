<?php

declare(strict_types=1);

namespace Wqrro\duels\groups;

use pocketmine\Player;
use Wqrro\Utils;

class MatchedGroup{

	private $playerName;
	private $opponentName;
	private $queue;
	private $ranked;

	public function __construct($player, $opponent, string $queue, bool $ranked = false){
		$pName = Utils::getPlayerName($player);
		$oName = Utils::getPlayerName($opponent);
		if(!is_null($pName)) $this->playerName = $pName;
		if(!is_null($oName)) $this->opponentName = $oName;
		$this->queue = $queue;
		$this->ranked = $ranked;
	}

	public function isRanked() : bool{
		return $this->ranked;
	}

	public function getPlayer(){
		return Utils::getPlayer($this->playerName);
	}

	public function isPlayerOnline(){
		$player = $this->getOpponent();
		return !is_null($player) and $player->isOnline();
	}

	public function getOpponent(){
		return Utils::getPlayer($this->opponentName);
	}

	public function isOpponentOnline(){
		$opponent = $this->getOpponent();
		return !is_null($opponent) and $opponent->isOnline();
	}

	public function equals($object) : bool{
		$result = false;
		if($object instanceof MatchedGroup){
			if($this->getPlayerName() === $object->getPlayerName() and $this->getOpponentName() === $object->getOpponentName()){
				$result = $this->getQueue() === $object->getQueue();
			}
		}
		return $result;
	}

	public function getPlayerName() : string{
		return $this->playerName;
	}

	public function getOpponentName() : string{
		return $this->opponentName;
	}

	public function getQueue() : string{
		return $this->queue;
	}
}