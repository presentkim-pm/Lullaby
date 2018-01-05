<?php

namespace presentkim\lullaby\command\subcommands;

namespace presentkim\lullaby\command;

use pocketmine\command\{
  Command, CommandExecutor, CommandSender
};
use presentkim\lullaby\LullabyMain as Plugin;
use presentkim\lullaby\command\subcommands\{
  HealSubCommand, DelaySubCommand, LangSubCommand, ReloadSubCommand, SaveSubCommand
};

class CommandListener implements CommandExecutor{

    /** @var Plugin */
    protected $owner;

    /**
     * SubComamnd[] $subcommands
     */
    protected $subcommands = [];

    /** @param Plugin $owner */
    public function __construct(Plugin $owner){
        $this->owner = $owner;

        $this->subcommands = [
          new HealSubCommand($this->owner),
          new DelaySubCommand($this->owner),
          new LangSubCommand($this->owner),
          new ReloadSubCommand($this->owner),
          new SaveSubCommand($this->owner),
        ];
    }

    /**
     * @param CommandSender $sender
     * @param Command       $command
     * @param string        $label
     * @param string[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if (!isset($args[0])) {
            return false;
        } else {
            $label = array_shift($args);
            foreach ($this->subcommands as $key => $value) {
                if ($value->checkLabel($label)) {
                    $value->execute($sender, $args);
                    return true;
                }
            }
            return false;
        }
    }
}