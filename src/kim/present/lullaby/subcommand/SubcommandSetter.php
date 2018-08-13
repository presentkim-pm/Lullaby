<?php

/*
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0.0
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\lullaby\subcommand;

use kim\present\lullaby\Lullaby;
use kim\present\lullaby\util\Utils;
use pocketmine\command\CommandSender;

class SubcommandSetter{
	/** @var Lullaby */
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
		if(isset($args[0])){
			if(!is_numeric($args[0])){
				$sender->sendMessage($this->owner->getLanguage()->translate("commands.generic.num.notNumber", [$args[0]]));
			}else{
				$value = (float) $args[0];
				if($value < 1){
					$sender->sendMessage($this->owner->getLanguage()->translate("commands.generic.num.tooSmall", [
						$value,
						1,
					]));
				}else{
					$this->owner->getConfig()->set($this->tag, $value);
					$sender->sendMessage($this->owner->getLanguage()->translate("commands.lullaby.{$this->tag}.success", [(string) $value]));
				}
			}
		}else{
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