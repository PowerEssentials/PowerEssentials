<?php

namespace angga7togk\poweressentials\utils;

use angga7togk\poweressentials\PowerEssentials;
use pocketmine\player\GameMode;

class ConfigUtils{

	public static function isGamemodeJoin():bool{
		return PowerEssentials::$config->get("gamemode-join-enable");
	}

	public static function getGamemodeJoin():?GameMode{
		return GameMode::fromString(PowerEssentials::$config->get("gamemode-join"));
	}

	public static function isSpawnLobbyJoin():bool{
		return PowerEssentials::$config->get("spawn-lobby-join");
	}

	public static function isAntiNamespace():bool{
		return PowerEssentials::$config->get("anti-namespace");
	}
}