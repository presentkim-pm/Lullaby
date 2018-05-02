<?php

namespace blugin\lullaby;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\TaskHandler;
use blugin\lullaby\command\PoolCommand;
use blugin\lullaby\command\subcommands\{
  DelaySubCommand, HealSubCommand
};
use blugin\lullaby\listener\PlayerEventListener;
use blugin\lullaby\task\SetSleepTickTask;
use blugin\lullaby\util\Translation;

class Lullaby extends PluginBase{

    /** @var Lullaby */
    private static $instance = null;

    /** @var TaskHandler */
    private $taskHandler = null;

    /** @return Lullaby */
    public static function getInstance() : Lullaby{
        return self::$instance;
    }

    /** @var PoolCommand */
    private $command;

    public function onLoad() : void{
        if (self::$instance === null) {
            self::$instance = $this;
            Translation::loadFromResource($this->getResource('lang/eng.yml'), true);
        }
    }

    public function onEnable() : void{
        $dataFolder = $this->getDataFolder();
        if (!file_exists($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }
        $this->reloadConfig();

        $langfilename = $dataFolder . 'lang.yml';
        if (!file_exists($langfilename)) {
            $resource = $this->getResource('lang/eng.yml');
            fwrite($fp = fopen("{$dataFolder}lang.yml", "wb"), $contents = stream_get_contents($resource));
            fclose($fp);
            Translation::loadFromContents($contents);
        } else {
            Translation::load($langfilename);
        }

        if ($this->command == null) {
            $this->command = new PoolCommand($this, 'lullaby');
            $this->command->createSubCommand(DelaySubCommand::class);
            $this->command->createSubCommand(HealSubCommand::class);
        }
        $this->command->updateTranslation();
        $this->command->updateSudCommandTranslation();
        if ($this->command->isRegistered()) {
            $this->getServer()->getCommandMap()->unregister($this->command);
        }
        $this->getServer()->getCommandMap()->register(strtolower($this->getName()), $this->command);

        $this->getServer()->getPluginManager()->registerEvents(new PlayerEventListener(), $this);

        $this->taskHandler = $this->getServer()->getScheduler()->scheduleRepeatingTask(new SetSleepTickTask($this), 30);
    }

    public function onDisable() : void{
        $dataFolder = $this->getDataFolder();
        if (!file_exists($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }
        $this->saveConfig();

        $this->taskHandler->cancel();
    }

    /**
     * @param string $name = ''
     *
     * @return PoolCommand
     */
    public function getCommand(string $name = '') : PoolCommand{
        return $this->command;
    }

    /** @param PoolCommand $command */
    public function setCommand(PoolCommand $command) : void{
        $this->command = $command;
    }
}
