<?php

namespace blugin\lullaby\command\subcommands;

use pocketmine\command\CommandSender;
use blugin\lullaby\Lullaby as Plugin;
use blugin\lullaby\command\{
  PoolCommand, SubCommand
};
use blugin\lullaby\util\Utils;

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
                $sender->sendMessage($this->translate('failure', $args[0]));
            } else {
                $this->plugin->getConfig()->set("heal", $heal);
                $sender->sendMessage($this->translate('success', $heal));
            }
            return true;
        }
        return false;
    }
}