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

namespace kim\present\lullaby\util;

class Utils{
	/**
	 * @param string $str
	 * @param array  $strs
	 *
	 * @return bool
	 */
	public static function in_arrayi(string $str, array $strs) : bool{
		foreach($strs as $key => $value){
			if(strcasecmp($str, $value) === 0){
				return true;
			}
		}
		return false;
	}

	/**
	 * @param string        $str
	 * @param int|null      $default = null
	 * @param \Closure|null $filter
	 *
	 * @return int|null
	 */
	public static function toInt(string $str, int $default = null, \Closure $filter = null) : ?int{
		if(is_numeric($str)){
			$i = (int) $str;
		}elseif(is_numeric($default)){
			$i = $default;
		}else{
			return null;
		}
		if(!$filter){
			return $i;
		}elseif($result = $filter($i)){
			return $result === -1 ? $default : $i;
		}else{
			return null;
		}
	}
}