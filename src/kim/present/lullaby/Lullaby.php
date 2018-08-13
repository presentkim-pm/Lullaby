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

use kim\present\lullaby\listener\PlayerEventListener;
use kim\present\lullaby\task\SetSleepTickTask;
use pocketmine\plugin\PluginBase;

class Lullaby extends PluginBase{
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

	/** @var int */
	private $healAmount, $healDelay;

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
		//Load config file
		$config = $this->getConfig();
		$this->healAmount = (int) $config->get(Lullaby::HEAL_TAG);
		$this->healDelay = (int) $config->get(Lullaby::DELAY_TAG);

		//Register event listeners
		$this->getServer()->getPluginManager()->registerEvents(new PlayerEventListener($this), $this);

		//Register repeating task
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
	 * @Override for multilingual support of the config file
	 *
	 * @return bool
	 */
	public function saveDefaultConfig() : bool{
		$resource = $this->getResource("lang/{$this->getServer()->getLanguage()->getLang()}/config.yml");
		if($resource === null){
			$resource = $this->getResource("lang/eng/config.yml");
		}

		if(!file_exists($configFile = $this->getDataFolder() . "config.yml")){
			$ret = stream_copy_to_stream($resource, $fp = fopen($configFile, "wb")) > 0;
			fclose($fp);
			fclose($resource);
			return $ret;
		}
		return false;
	}

	/**
	 * @return int
	 */
	public function getHealAmount() : int{
		return $this->healAmount;
	}

	/**
	 * @param int $healAmount
	 */
	public function setHealAmount(int $healAmount) : void{
		$this->healAmount = $healAmount;
	}

	/**
	 * @return int
	 */
	public function getHealDelay() : int{
		return $this->healDelay;
	}

	/**
	 * @param int $healDelay
	 */
	public function setHealDelay(int $healDelay) : void{
		$this->healDelay = $healDelay;
	}
}