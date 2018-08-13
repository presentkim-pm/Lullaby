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

namespace kim\present\lullaby;

use kim\present\lullaby\lang\PluginLang;
use kim\present\lullaby\listener\PlayerEventListener;
use kim\present\lullaby\subcommand\SubcommandSetter;
use kim\present\lullaby\task\SetSleepTickTask;
use pocketmine\command\{
	Command, CommandExecutor, CommandSender, PluginCommand
};
use pocketmine\plugin\PluginBase;

class Lullaby extends PluginBase implements CommandExecutor{
	public const HEAL_TAG = "heal";
	public const DELAY_TAG = "delay";

	/** @var Lullaby */
	private static $instance = null;

	/**
	 * @return Lullaby
	 */
	public static function getInstance() : Lullaby{
		return self::$instance;
	}

	/** @var PluginCommand */
	private $command;

	/** @var PluginLang */
	private $language;

	/** @var SubcommandSetter[] */
	private $subcommands = [];

	/**
	 * Called when the plugin is loaded, before calling onEnable()
	 */
	public function onLoad() : void{
		self::$instance = $this;
	}

	/**
	 * Called when the plugin is enabled
	 */
	public function onEnable() : void{
		$dataFolder = $this->getDataFolder();
		if(!file_exists($dataFolder)){
			mkdir($dataFolder, 0777, true);
		}
		$this->reloadConfig();
		$this->language = new PluginLang($this);

		if($this->command !== null){
			$this->getServer()->getCommandMap()->unregister($this->command);
		}
		$this->command = new PluginCommand($this->language->translate("commands.lullaby"), $this);
		$this->command->setPermission("lullaby.cmd");
		$this->command->setDescription($this->language->translate("commands.lullaby.description"));
		$this->command->setUsage($this->language->translate("commands.lullaby.usage"));
		if(is_array($aliases = $this->language->getArray("commands.lullaby.aliases"))){
			$this->command->setAliases($aliases);
		}
		$this->getServer()->getCommandMap()->register("lullaby", $this->command);

		$this->subcommands[] = new SubcommandSetter($this, self::HEAL_TAG);
		$this->subcommands[] = new SubcommandSetter($this, self::DELAY_TAG);

		$this->getServer()->getPluginManager()->registerEvents(new PlayerEventListener($this), $this);
		$this->getScheduler()->scheduleRepeatingTask(new SetSleepTickTask(), 30);
	}

	/**
	 * Called when the plugin is disabled
	 * Use this to free open things and finish actions
	 */
	public function onDisable() : void{
		$dataFolder = $this->getDataFolder();
		if(!file_exists($dataFolder)){
			mkdir($dataFolder, 0777, true);
		}
		$this->saveConfig();
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
		if(isset($args[0])){
			$tag = array_shift($args);
			foreach($this->subcommands as $key => $value){
				if($value->validate($tag)){
					$value->execute($sender, $args);
					return true;
				}
			}
			return false;
		}
		return false;
	}

	/**
	 * @param string $name = ""
	 *
	 * @return PluginCommand
	 */
	public function getCommand(string $name = "") : PluginCommand{
		return $this->command;
	}

	/**
	 * @return PluginLang
	 */
	public function getLanguage() : PluginLang{
		return $this->language;
	}

	/**
	 * @return string
	 */
	public function getSourceFolder() : string{
		$pharPath = \Phar::running();
		if(empty($pharPath)){
			return dirname(__FILE__, 4) . DIRECTORY_SEPARATOR;
		}else{
			return $pharPath . DIRECTORY_SEPARATOR;
		}
	}
}