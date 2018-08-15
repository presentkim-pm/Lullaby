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

namespace kim\present\lullaby\task;

use kim\present\lullaby\Lullaby;
use pocketmine\{
	Player, Server
};
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\{
	AddEntityPacket, RemoveEntityPacket, SetEntityDataPacket
};
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class HealTask extends Task{
	private const BAR_LENGTH = 30;

	/** @var Player */
	private $player;

	/** @var Lullaby */
	private $plugin;

	/** @var int */
	private $lastTick, $entityUniqueId;

	/**
	 * HealTask constructor.
	 *
	 * @param Player  $player
	 * @param Lullaby $plugin
	 * @param Vector3 $textPosition
	 */
	public function __construct(Player $player, Lullaby $plugin, Vector3 $textPosition){
		//Set default value of properties
		$this->entityUniqueId = Entity::$entityCount++;
		$this->lastTick = Server::getInstance()->getTick();

		//Set value of properties from arguments
		$this->player = $player;
		$this->plugin = $plugin;

		//Spawn floating text
		$pk = new AddEntityPacket();
		$pk->entityRuntimeId = $this->entityUniqueId;
		$pk->type = Entity::XP_ORB;
		$pk->position = $textPosition;
		$pk->metadata = [
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, 1 << Entity::DATA_FLAG_IMMOBILE],
			Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0.01],
			Entity::DATA_ALWAYS_SHOW_NAMETAG => [Entity::DATA_TYPE_BYTE, 1]
		];
		$player->sendDataPacket($pk);
	}

	/**
	 * @param int $currentTick
	 */
	public function onRun(int $currentTick){
		if(!$this->player->isSleeping()){
			$this->getHandler()->cancel();
			return;
		}
		$tickDiff = $currentTick - $this->lastTick;

		//Update floating text
		$pk = new SetEntityDataPacket();
		$pk->entityRuntimeId = $this->entityUniqueId;
		$pk->metadata = [
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->getInfo($currentTick)]
		];
		$this->player->sendDataPacket($pk);

		//Healing player per heal delay
		if($tickDiff > $this->plugin->getHealDelay()){
			$this->player->heal(new EntityRegainHealthEvent($this->player, $this->plugin->getHealAmount(), EntityRegainHealthEvent::CAUSE_MAGIC));
			$this->lastTick = $currentTick;
		}
	}

	/**
	 * Actions to execute if the Task is cancelled
	 */
	public function onCancel(){
		//Despawn floating text
		$pk = new RemoveEntityPacket();
		$pk->entityUniqueId = $this->entityUniqueId;
		$this->player->sendDataPacket($pk);
	}

	/**
	 * @param int $currentTick
	 *
	 * @return string
	 */
	private function getInfo(int $currentTick) : string{
		/** @var string[] $info */
		$info = [];

		//Line 1 : The animated loading mark
		$info[0] = "Healing..." . ["-", "\\", ".|.", "/"][floor($currentTick / 2) % 4];

		//Line 2 : HP bar
		$health = (int) $this->player->getHealth();
		$maxHealth = (int) $this->player->getMaxHealth();
		$percentage = (int) round($health / $maxHealth * self::BAR_LENGTH);
		$info[1] = "{$health}/{$maxHealth} ";
		$info[1] .= TextFormat::GREEN . substr_replace(str_repeat("|", self::BAR_LENGTH), TextFormat::DARK_GREEN, $percentage, 0);

		//Line 3 : Heal progress bar
		$percentage = (int) round(($currentTick - $this->lastTick) / $this->plugin->getHealDelay() * self::BAR_LENGTH);
		$info[2] = TextFormat::BOLD . TextFormat::RED . substr_replace(str_repeat(":", self::BAR_LENGTH), TextFormat::DARK_RED, $percentage, 0);

		return implode(TextFormat::RESET . "\n", $info);
	}
}