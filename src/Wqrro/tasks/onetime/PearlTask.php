<?php

declare(strict_types=1);

namespace Wqrro\tasks\onetime;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use Wqrro\Core;

class PearlTask extends Task{

	public $player;

	private $timer = 11;//always set a second higher than what second you want

	public function __construct(Core $plugin, Player $player){
		$this->plugin = $plugin;
		$this->player = $player;
	}

	public function onRun(int $currentTick) : void{
		$this->timer--;
		if($this->timer == 10){
			$this->player->setEnderPearlCooldown(true);
			$percent = floatval($this->timer / 10);
			$this->player->setXpProgress($percent);
		}
		if($this->timer < 10){
			$percent = floatval($this->timer / 10);
			$this->player->setXpProgress($percent);
		}
		if($this->timer == 0){
			$this->player->setXpProgress(0.0);
			$this->player->setEnderPearlCooldown(false);
			$this->plugin->getScheduler()->cancelTask($this->getTaskId());
		}
	}
}
