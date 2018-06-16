<?php

namespace kim\present\lullaby\task;

use kim\present\lullaby\Lullaby;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;

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