<?php

namespace presentkim\lullaby\command\subcommands;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use presentkim\lullaby\{
  LullabyMain as Plugin, util\Translation, command\SubCommand
};
use function presentkim\lullaby\util\toInt;
use function strtolower;

class HealSubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-lullaby-heal', 'lullaby.set.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        if (isset($args[0])) {
            $heal = toInt($args[0], null, function (int $i){
                return $i >= 1;
            });
            if ($heal === null) {
                $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('failure'), $args[0]));
            } else {
                $this->owner->getConfig()->set("heal", $heal);
                $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('success'), $heal));
            }
            return true;
        }
        return false;
    }
}