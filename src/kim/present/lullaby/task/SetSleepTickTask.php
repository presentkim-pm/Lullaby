<?php

namespace kim\present\lullaby\task;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;

class SetSleepTickTask extends PluginTask{

	public function onRun(int $currentTick){
		foreach(Server::getInstance()->getLevels() as $key => $value){
			$value->setSleepTicks(0);
		}
	}
}