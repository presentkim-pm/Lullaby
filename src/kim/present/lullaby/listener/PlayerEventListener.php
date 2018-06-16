<?php

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
	/**
	 * @var Lullaby
	 */
	private $owner = null;

	/**
	 * @var TaskHandler[] TaskHandler[string]
	 */
	private $taskHandlers = [];

	public function __construct(Lullaby $owner){
		$this->owner = $owner;
	}

	/**
	 * @param PlayerBedEnterEvent $event
	 */
	public function onPlayerBedEnterEven(PlayerBedEnterEvent $event) : void{
		$player = $event->getPlayer();
		$this->taskHandlers[$player->getName()] = $this->owner->getScheduler()->scheduleDelayedRepeatingTask(new HealTask($player, $this->owner), $delay = ((int) $this->owner->getConfig()->get(Lullaby::DELAY_TAG)), $delay);
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