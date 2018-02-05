<?php

namespace presentkim\lullaby\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\lullaby\{
  command\PoolCommand, LullabyMain as Plugin, command\SubCommand, util\Utils
};

class HealSubCommand extends SubCommand{

    public function __construct(PoolCommand $owner){
        parent::__construct($owner, 'heal');
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        if (isset($args[0])) {
            $heal = Utils::toInt($args[0], null, function (int $i){
                return $i >= 1;
            });
            if ($heal === null) {
                $sender->sendMessage(Plugin::$prefix . $this->translate('failure', $args[0]));
            } else {
                $this->plugin->getConfig()->set("heal", $heal);
                $sender->sendMessage(Plugin::$prefix . $this->translate('success', $heal));
            }
            return true;
        }
        return false;
    }
}