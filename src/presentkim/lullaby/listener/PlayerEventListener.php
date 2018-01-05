<?php

namespace presentkim\lullaby\listener;

use pocketmine\event\{
  entity\EntityRegainHealthEvent, Listener, player\PlayerBedEnterEvent, player\PlayerBedLeaveEvent
};
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskHandler;
use pocketmine\Server;
use presentkim\lullaby\LullabyMain as Plugin;

class PlayerEventListener implements Listener{

    /** @var Plugin */
    private $owner = null;

    /** @var TaskHandler[] TaskHandler[string] */
    private $taskHandlers = [];

    public function __construct(){
        $this->owner = Plugin::getInstance();
    }

    /** @param PlayerBedEnterEvent $event */
    public function onPlayerBedEnterEven(PlayerBedEnterEvent $event) : void{
        $player = $event->getPlayer();
        $this->taskHandlers[$player->getName()] = Server::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new class($player, $this->owner) extends Task{

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
        }, $delay = ((int) $this->owner->getConfig()->get("delay")), $delay);
    }

    /** @param PlayerBedLeaveEvent $event */
    public function onPlayerBedLeaveEvent(PlayerBedLeaveEvent $event) : void{
        $player = $event->getPlayer();
        $plyerName = $player->getName();
        if (isset($this->taskHandlers[$plyerName])) {
            $this->taskHandlers[$plyerName]->cancel();
            unset($this->taskHandlers[$plyerName]);
        }
    }
}