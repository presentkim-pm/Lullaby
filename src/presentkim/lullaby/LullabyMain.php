<?php

namespace presentkim\lullaby;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\{
  Task, TaskHandler
};
use presentkim\lullaby\util\Translation;
use presentkim\lullaby\listener\PlayerEventListener;
use presentkim\lullaby\command\PoolCommand;
use presentkim\lullaby\command\subcommands\{
  DelaySubCommand, HealSubCommand, LangSubCommand, ReloadSubCommand, SaveSubCommand
};

class LullabyMain extends PluginBase{

    /** @var self */
    private static $instance = null;

    /** @var PoolCommand */
    private $command;

    /** @var TaskHandler */
    private $taskHandler = null;

    /** @return self */
    public static function getInstance() : self{
        return self::$instance;
    }

    public function onLoad() : void{
        if (self::$instance === null) {
            self::$instance = $this;
            $this->getServer()->getLoader()->loadClass('presentkim\lullaby\util\Utils');
            Translation::loadFromResource($this->getResource('lang/eng.yml'), true);
        }
    }

    public function onEnable() : void{
        $this->load();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEventListener(), $this);

        $this->taskHandler = Server::getInstance()->getScheduler()->scheduleRepeatingTask(new class() extends Task{

            public function onRun(int $currentTick){
                foreach (Server::getInstance()->getLevels() as $key => $value) {
                    $value->setSleepTicks(0);
                }
            }
        }, 30);
    }

    public function onDisable() : void{
        $this->save();
        $this->taskHandler->cancel();
    }

    public function load() : void{
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

        $this->reloadCommand();
    }

    public function save() : void{
        $dataFolder = $this->getDataFolder();
        if (!file_exists($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }

        $this->saveConfig();
    }

    public function reloadCommand(){
        if ($this->command == null) {
            $this->command = new PoolCommand($this, 'lullaby');
            $this->command->createSubCommand(DelaySubCommand::class);
            $this->command->createSubCommand(HealSubCommand::class);
            $this->command->createSubCommand(LangSubCommand::class);
            $this->command->createSubCommand(ReloadSubCommand::class);
            $this->command->createSubCommand(SaveSubCommand::class);
        }
        $this->command->updateTranslation();
        $this->command->updateSudCommandTranslation();
        if ($this->command->isRegistered()) {
            $this->getServer()->getCommandMap()->unregister($this->command);
        }
        $this->getServer()->getCommandMap()->register(strtolower($this->getName()), $this->command);
    }
}
