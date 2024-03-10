<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\commands\FlyCommand;
use angga7togk\poweressentials\commands\gamemode\AdventureCommand;
use angga7togk\poweressentials\commands\gamemode\CreativeCommand;
use angga7togk\poweressentials\commands\gamemode\SpectatorCommand;
use angga7togk\poweressentials\commands\gamemode\SurvivalCommand;
use angga7togk\poweressentials\commands\lobby\LobbyCommand;
use angga7togk\poweressentials\commands\lobby\SetLobbyCommand;
use angga7togk\poweressentials\commands\NicknameCommand;
use angga7togk\poweressentials\language\LanguageManager;
use angga7togk\poweressentials\permission\PermissionsManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class PowerEssentials extends PluginBase
{
    use SingletonTrait;

    /** @var Config $lobby */
	public static Config $lobby;

	protected function onLoad(): void
	{
		self::$instance = $this;
	}

	public function onEnable(): void
	{
        PermissionsManager::init();
        $this->initResources();
//        LanguageManager::init();
		$this->loadConfigs();
		$this->loadCommands();
		$this->loadListeners();
	}

	private function loadConfigs(): void
	{
		$this->saveDefaultConfig();
		$this->saveResource("lobby.yml");
		self::$lobby = new Config($this->getDataFolder() . "lobby.yml", Config::YAML, []);
	}

	private function loadListeners(): void
	{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}

	private function loadCommands(): void
	{
        $commandPrefixes = [
            "lobby" => new LobbyCommand(),
            "setlobby" => new SetLobbyCommand(),
            "gmc" => new CreativeCommand(),
            "gms" => new SurvivalCommand(),
            "gma" => new AdventureCommand(),
            "gmspc" => new SpectatorCommand(),
            "nickname" => new NicknameCommand(),
            "fly" => new FlyCommand()
        ];

        foreach ($commandPrefixes as $k => $v) {
            if (in_array($k, $this->getConfig()->get("commands"))) {
                $this->getServer()->getCommandMap()->register($k, $v);
            }
        }

	}

    private function initResources(): void {
        if (!is_dir($this->getDataFolder() . "languages")) {
            @mkdir($this->getDataFolder() . "languages");
        }
        $this->saveResource("languages/en_US.ini");
        LanguageManager::init();
    }

}