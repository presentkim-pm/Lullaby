<?php

namespace presentkim\lullaby\task;

use pocketmine\Player;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\scheduler\Task;
use presentkim\lullaby\Lullaby as Plugin;

class HealTask extends Task{

    /** @var Player */
    public $player;

    /** @var Plugin */
    private $owner;

    public function __construct(Player $player, Plugin $owner){
        $this->player = $player;
        $this->owner = $owner;
    }

    public function onRun(int $currentTick){
        $this->player->heal(new EntityRegainHealthEvent($this->player, ((int) $this->owner->getConfig()->get("heal")), EntityRegainHealthEvent::CAUSE_MAGIC));
    }
}