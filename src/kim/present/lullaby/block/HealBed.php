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
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://opensource.org/licenses/MIT MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\lullaby\block;

use pocketmine\block\Bed;
use pocketmine\item\Item;
use pocketmine\lang\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class HealBed extends Bed{
	/**
	 * @Override for always sleep support
	 *
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null) : bool{
		if($player !== null){
			$other = $this->getOtherHalf();
			if($other === null){
				$player->sendMessage(TextFormat::GRAY . "This bed is incomplete");
			}elseif($player->distanceSquared($this) > 4 and $player->distanceSquared($other) > 4){
				$player->sendMessage(new TranslationContainer(TextFormat::GRAY . "%tile.bed.tooFar"));
			}else{
				$b = ($this->isHeadPart() ? $this : $other);
				if($b->isOccupied()){
					$player->sendMessage(new TranslationContainer(TextFormat::GRAY . "%tile.bed.occupied"));
				}else{
					$player->sleepOn($b);
				}
			}
		}
		return true;
	}
}
