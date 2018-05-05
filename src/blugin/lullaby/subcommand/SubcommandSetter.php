<?php

namespace blugin\lullaby\subcommand;

use pocketmine\command\CommandSender;
use blugin\lullaby\Lullaby;
use blugin\lullaby\util\Utils;

class SubcommandSetter{

    /** @var Plugin */
    public $owner;

    /** @var string */
    public $label;

    /** @var string[] */
    public $aliases;

    /** @var string */
    public $usage;

    /** @var string */
    public $tag;

    /**
     * SubCommand constructor.
     *
     * @param Lullaby $owner
     * @param string  $tag
     */
    public function __construct(Lullaby $owner, string $tag){
        $this->owner = $owner;
        $this->tag = $tag;

        $lang = $owner->getLanguage();
        $this->label = $lang->translate("commands.lullaby.{$tag}");
        $this->usage = $lang->translate("commands.lullaby.{$tag}.usage");
        $this->aliases = $lang->getArray("commands.lullaby.{$tag}.aliases");
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     */
    public function execute(CommandSender $sender, array $args) : void{
        if (isset($args[0])) {
            $value = Utils::toInt($args[0], null, function (int $i){
                return $i >= 0;
            });
            if ($value === null) {
                $sender->sendMessage($this->owner->getLanguage()->translate("commands.lullaby.{$this->tag}.failure", [$args[0]]));
            } else {
                $this->owner->getConfig()->set($this->tag, $value);
                $sender->sendMessage($this->owner->getLanguage()->translate("commands.lullaby.{$this->tag}.success", [(string) $value]));
            }
        } else {
            $sender->sendMessage($this->usage);
        }
    }

    /**
     * @param string $label
     *
     * @return bool
     */
    public function validate(string $label) : bool{
        return strcasecmp($label, $this->label) === 0 || $this->aliases && Utils::in_arrayi($label, $this->aliases);
    }
}