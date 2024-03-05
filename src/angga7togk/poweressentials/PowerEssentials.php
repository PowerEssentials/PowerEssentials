<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\commands\lobby\LobbyCommand;
use angga7togk\poweressentials\commands\lobby\SetLobbyCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PowerEssentials extends PluginBase {

    public static Config $config, $lobby;
    public static array $messages;
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
		$this->saveResource("lobby.yml");
        self::$config = $this->getConfig();
		self::$lobby = new Config($this->getDataFolder() . "lobby.yml", Config::YAML, []);

        $this->loadLanguage();
        $this->loadCommands();
    }

    private function loadCommands():void{
		$commands = [
			'lobby' => [new LobbyCommand(), new SetLobbyCommand()]
		];

		foreach($commands as $keyCmd => $valueCmd){
			if(self::$config->get("commands")[$keyCmd]){
				$this->getServer()->getCommandMap()->registerAll($this->getName(), $valueCmd);
			}
		}

    }

    private function loadLanguage(): void{
        $lang = self::$config->get("language", "en");
        $this->saveResource("language/$lang.yml");
        self::$messages = (new Config($this->getDataFolder() . "/language/$lang.yml"))->getAll();
    }

}