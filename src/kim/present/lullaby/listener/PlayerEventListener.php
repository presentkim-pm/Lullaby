<?php

/*
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0.0
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\lullaby\listener;

use kim\present\lullaby\Lullaby;
use kim\present\lullaby\task\HealBedTask;
use pocketmine\block\Bed;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBedEnterEvent;

class PlayerEventListener implements Listener{
	/** @var Lullaby */
	private $owner = null;

	/**
	 * PlayerEventListener constructor.
	 *
	 * @param Lullaby $owner
	 */
	public function __construct(Lullaby $owner){
		$this->owner = $owner;
	}

	/**
	 * @param PlayerBedEnterEvent $event
	 */
	public function onPlayerBedEnterEvent(PlayerBedEnterEvent $event) : void{
		$player = $event->getPlayer();
		$bed = $event->getBed();
		$position = $bed->asVector3()->getSide(Bed::getOtherHalfSide($bed->getDamage(), true))->add(0.5, 0.5, 0.5);
		$this->owner->getScheduler()->scheduleRepeatingTask(new HealBedTask($player, $this->owner, $position), 2);
	}
}