<?php

namespace blugin\lullaby\task;

use pocketmine\Server;
use pocketmine\scheduler\PluginTask;

class SetSleepTickTask extends PluginTask{

    public function onRun(int $currentTick){
        foreach (Server::getInstance()->getLevels() as $key => $value) {
            $value->setSleepTicks(0);
        }
    }
}