<?php

namespace blugin\lullaby\command\subcommands;

use pocketmine\command\CommandSender;
use blugin\lullaby\Lullaby as Plugin;
use blugin\lullaby\command\{
  PoolCommand, SubCommand
};
use blugin\lullaby\util\Utils;

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
            $delay = Utils::toInt($args[0], null, function (int $i){
                return $i >= 0;
            });
            if ($delay === null) {
                $sender->sendMessage($this->translate('failure', $args[0]));
            } else {
                $this->plugin->getConfig()->set("delay", $delay);
                $sender->sendMessage($this->translate('success', $delay));
            }
            return true;
        }
        return false;
    }
}