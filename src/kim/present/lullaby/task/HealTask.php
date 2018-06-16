<?php

namespace kim\present\lullaby\task;

use kim\present\lullaby\Lullaby;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class HealTask extends Task{
	/**
	 * @var Lullaby
	 */
	private $owner;

	/** @var Player */
	public $player;

	public function __construct(Player $player, Lullaby $owner){
		$this->player = $player;
		$this->owner = $owner;
	}

	public function onRun(int $currentTick){
		$this->player->heal(new EntityRegainHealthEvent($this->player, ((int) $this->owner->getConfig()->get(Lullaby::HEAL_TAG)), EntityRegainHealthEvent::CAUSE_MAGIC));
	}
}