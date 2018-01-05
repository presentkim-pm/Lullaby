<?php

namespace presentkim\lullaby\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\lullaby\{
  LullabyMain as Plugin, util\Translation, command\SubCommand
};
use function presentkim\lullaby\util\toInt;

class DelaySubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-lullaby-delay', 'lullaby.default.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        if (isset($args[0])) {
            $delay = toInt($args[0], null, function (int $i){
                return $i >= 0;
            });
            if ($delay === null) {
                $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('failure'), $args[0]));
            } else {
                $this->owner->getConfig()->set("delay", $delay);
                $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('success'), $delay));
            }
            return true;
        }
        return false;
    }
}