<?php

namespace presentkim\lullaby\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\lullaby\{
  LullabyMain as Plugin, util\Translation, command\SubCommand
};

class SaveSubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-lullaby-save', 'lullaby.save.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        $this->owner->save();
        $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('success')));

        return true;
    }
}