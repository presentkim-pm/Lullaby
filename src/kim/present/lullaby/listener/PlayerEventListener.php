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
use kim\present\lullaby\task\HealTask;
use pocketmine\block\Bed;
use pocketmine\event\Listener;
use pocketmine\event\player\{
	PlayerBedEnterEvent, PlayerBedLeaveEvent, PlayerInteractEvent
};
use pocketmine\lang\TranslationContainer;
use pocketmine\scheduler\TaskHandler;
use pocketmine\utils\TextFormat;

class PlayerEventListener implements Listener{
	/** @var Lullaby */
	private $owner = null;

	/** @var TaskHandler[] TaskHandler[string] */
	private $taskHandlers = [];

	public function __construct(Lullaby $owner){
		$this->owner = $owner;
	}

	/**
	 * @param PlayerBedEnterEvent $event
	 */
	public function onPlayerBedEnterEven(PlayerBedEnterEvent $event) : void{
		$player = $event->getPlayer();
		$bed = $event->getBed();
		$position = $bed->add(
			([1 => 2, 3 => -2, 9 => 2, 11 => -2][$bed->getDamage()] ?? 0) + 0.5,
			0.5,
			([0 => -2, 2 => 2, 8 => -2, 10 => 2][$bed->getDamage()] ?? 0) + 0.5
		);
		$this->taskHandlers[$player->getName()] = $this->owner->getScheduler()->scheduleRepeatingTask(new HealTask($player, $this->owner->getHealAmount(), $this->owner->getHealDelay(), $position), 2);
	}

	/**
	 * @param PlayerBedLeaveEvent $event
	 */
	public function onPlayerBedLeaveEvent(PlayerBedLeaveEvent $event) : void{
		$playerName = $event->getPlayer()->getName();
		if(isset($this->taskHandlers[$playerName])){
			$this->taskHandlers[$playerName]->cancel();
			unset($this->taskHandlers[$playerName]);
		}
	}

	/**
	 * @priority HIGHEST
	 *
	 * @param PlayerInteractEvent $event
	 */
	public function onPlayerInteractEvent(PlayerInteractEvent $event) : void{
		if(!$event->isCancelled()){
			$block = $event->getBlock();
			if($block instanceof Bed){
				$player = $event->getPlayer();
				$other = $block->getOtherHalf();
				if($other === null){
					$player->sendMessage(TextFormat::GRAY . "This bed is incomplete");
				}elseif($player->distanceSquared($block) > 4 and $player->distanceSquared($other) > 4){
					$player->sendMessage(new TranslationContainer(TextFormat::GRAY . "%tile.bed.tooFar"));
				}elseif(($b = ($block->isHeadPart() ? $block : $other))->isOccupied()){
					$player->sendMessage(new TranslationContainer(TextFormat::GRAY . "%tile.bed.occupied"));
				}else{
					$player->sleepOn($b);
				}
			}
		}
	}
}