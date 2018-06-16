<?php

namespace kim\present\lullaby;

use kim\present\lullaby\lang\PluginLang;
use kim\present\lullaby\listener\PlayerEventListener;
use kim\present\lullaby\subcommand\SubcommandSetter;
use kim\present\lullaby\task\SetSleepTickTask;
use pocketmine\command\{
	Command, CommandExecutor, CommandSender, PluginCommand
};
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\TaskHandler;

class Lullaby extends PluginBase implements CommandExecutor{

	public const HEAL_TAG = 'heal';
	public const DELAY_TAG = 'delay';

	/** @var Lullaby */
	private static $instance = null;

	/** @var TaskHandler */
	private $taskHandler = null;

	/** @return Lullaby */
	public static function getInstance() : Lullaby{
		return self::$instance;
	}

	/** @var PluginCommand */
	private $command;

	/** @var PluginLang */
	private $language;

	/** @var SubcommandSetter[] */
	private $subcommands = [];

	public function onLoad() : void{
		self::$instance = $this;
	}

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
		$this->command = new PluginCommand($this->language->translate('commands.lullaby'), $this);
		$this->command->setPermission('lullaby.cmd');
		$this->command->setDescription($this->language->translate('commands.lullaby.description'));
		$this->command->setUsage($this->language->translate('commands.lullaby.usage'));
		if(is_array($aliases = $this->language->getArray('commands.lullaby.aliases'))){
			$this->command->setAliases($aliases);
		}
		$this->getServer()->getCommandMap()->register('lullaby', $this->command);

		$this->subcommands[] = new SubcommandSetter($this, self::HEAL_TAG);
		$this->subcommands[] = new SubcommandSetter($this, self::DELAY_TAG);

		$this->getServer()->getPluginManager()->registerEvents(new PlayerEventListener($this), $this);
		$this->taskHandler = $this->getServer()->getScheduler()->scheduleRepeatingTask(new SetSleepTickTask($this), 30);
	}

	public function onDisable() : void{
		$dataFolder = $this->getDataFolder();
		if(!file_exists($dataFolder)){
			mkdir($dataFolder, 0777, true);
		}
		$this->saveConfig();

		$this->taskHandler->cancel();
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
	 * @param string $name = ''
	 *
	 * @return PluginCommand
	 */
	public function getCommand(string $name = '') : PluginCommand{
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
