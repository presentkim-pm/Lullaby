<?php

namespace kim\present\lullaby\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class SetSleepTickTask extends Task{
	public function onRun(int $currentTick){
		foreach(Server::getInstance()->getLevels() as $key => $value){
			$value->setSleepTicks(0);
		}
	}
}