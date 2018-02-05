<?php

namespace presentkim\lullaby\task;

use pocketmine\Player;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\scheduler\PluginTask;
use presentkim\lullaby\Lullaby as Plugin;

class HealTask extends PluginTask{

    /** @var Player */
    public $player;

    public function __construct(Player $player, Plugin $owner){
        parent::__construct($owner);
        $this->player = $player;
    }

    public function onRun(int $currentTick){
        $this->player->heal(new EntityRegainHealthEvent($this->player, ((int) $this->owner->getConfig()->get("heal")), EntityRegainHealthEvent::CAUSE_MAGIC));
    }
}