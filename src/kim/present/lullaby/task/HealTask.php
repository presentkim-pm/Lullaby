<?php

namespace kim\present\lullaby\task;

use pocketmine\Player;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\scheduler\PluginTask;
use kim\present\lullaby\Lullaby;

class HealTask extends PluginTask{

    /** @var Player */
    public $player;

    public function __construct(Player $player, Lullaby $owner){
        parent::__construct($owner);
        $this->player = $player;
    }

    public function onRun(int $currentTick){
        $this->player->heal(new EntityRegainHealthEvent($this->player, ((int) $this->owner->getConfig()->get(Lullaby::HEAL_TAG)), EntityRegainHealthEvent::CAUSE_MAGIC));
    }
}