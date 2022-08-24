<?php

declare(strict_types=1);

namespace Wqrro\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use Wqrro\Core;

class CombatTask extends Task{

	public function __construct(Core $plugin){
		$this->plugin = $plugin;
	}

	public function onRun(int $currentTick) : void{
		foreach($this->plugin->taggedPlayer as $name => $time){
			$player = $this->plugin->getServer()->getPlayerExact($name);
			$time--;
			if($player->isTagged()){
				$player->sendTip("In combat, please wait " . $time . "s");
			}
			if($time <= 0){
				$player->setTagged(false);
				$player->sendMessage("§aYou are no longer in combat.");
				return;
			}
			$this->plugin->taggedPlayer[$name]--;
		}
	}
}
