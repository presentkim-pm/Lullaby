<?php

namespace presentkim\lullaby\task;

use pocketmine\Server;
use pocketmine\scheduler\Task;

class SetSleepTickTask extends Task{

    public function onRun(int $currentTick){
        foreach (Server::getInstance()->getLevels() as $key => $value) {
            $value->setSleepTicks(0);
        }
    }
}