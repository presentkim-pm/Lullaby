<?php

namespace blugin\lullaby\listener;

use pocketmine\Server;
use pocketmine\block\Bed;
use pocketmine\event\{
  Listener, TranslationContainer
};
use pocketmine\event\player\{
  PlayerBedEnterEvent, PlayerBedLeaveEvent, PlayerInteractEvent
};
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\TaskHandler;
use blugin\lullaby\Lullaby as Plugin;
use blugin\lullaby\task\HealTask;

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
        $this->taskHandlers[$player->getName()] = Server::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new HealTask($player, $this->owner), $delay = ((int) $this->owner->getConfig()->get("delay")), $delay);
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

    /**
     * @priority HIGHEST
     *
     * @param PlayerInteractEvent $event
     */
    public function onPlayerInteractEvent(PlayerInteractEvent $event) : void{
        if (!$event->isCancelled()) {
            $block = $event->getBlock();
            if ($block instanceof Bed) {
                $player = $event->getPlayer();
                $other = $block->getOtherHalf();
                if ($other === null) {
                    $player->sendMessage(TextFormat::GRAY . "This bed is incomplete");
                } elseif ($player->distanceSquared($block) > 4 and $player->distanceSquared($other) > 4) {
                    $player->sendMessage(new TranslationContainer(TextFormat::GRAY . "%tile.bed.tooFar"));
                } elseif (($b = ($block->isHeadPart() ? $block : $other))->isOccupied()) {
                    $player->sendMessage(new TranslationContainer(TextFormat::GRAY . "%tile.bed.occupied"));
                } else {
                    $player->sleepOn($b);
                }
            }
        }
    }
}