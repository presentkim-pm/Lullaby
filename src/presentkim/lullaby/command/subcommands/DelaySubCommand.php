<?php

namespace presentkim\lullaby\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\lullaby\{
  command\PoolCommand, LullabyMain as Plugin, command\SubCommand
};
use function presentkim\lullaby\util\toInt;

class DelaySubCommand extends SubCommand{

    public function __construct(PoolCommand $owner){
        parent::__construct($owner, 'delay');
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        if (isset($args[0])) {
            $delay = toInt($args[0], null, function (int $i){
                return $i >= 0;
            });
            if ($delay === null) {
                $sender->sendMessage(Plugin::$prefix . $this->translate('failure', $args[0]));
            } else {
                $this->owner->getConfig()->set("delay", $delay);
                $sender->sendMessage(Plugin::$prefix . $this->translate('success', $delay));
            }
            return true;
        }
        return false;
    }
}