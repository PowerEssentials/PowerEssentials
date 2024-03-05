<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\commands\FlyCommand;
use angga7togk\poweressentials\commands\gamemode\AdvantureCommand;
use angga7togk\poweressentials\commands\gamemode\CreativeCommand;
use angga7togk\poweressentials\commands\gamemode\SpectatorCommand;
use angga7togk\poweressentials\commands\gamemode\SurvivalCommand;
use angga7togk\poweressentials\commands\lobby\LobbyCommand;
use angga7togk\poweressentials\commands\lobby\SetLobbyCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PowerEssentials extends PluginBase
{

	public static Config $config, $lobby;
	public static array $messages;
	public static self $instance;

	protected function onLoad(): void
	{
		self::$instance = $this;
	}

	public function onEnable(): void
	{
		$this->loadConfigs();
		$this->loadLanguage();
		$this->loadCommands();
		$this->loadListeners();
	}

	private function loadConfigs(): void
	{
		$this->saveDefaultConfig();
		$this->saveResource("lobby.yml");
		self::$config = $this->getConfig();
		self::$lobby = new Config($this->getDataFolder() . "lobby.yml", Config::YAML, []);
	}

	private function loadListeners(): void
	{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}

	private function loadCommands(): void
	{
		$commands = [
			'lobby' => [new LobbyCommand(), new SetLobbyCommand()],
			'fly' => [new FlyCommand()],
			'gamemode' => [new AdvantureCommand(), new CreativeCommand(), new SpectatorCommand(), new SurvivalCommand()]
		];

		foreach ($commands as $keyCmd => $valueCmd) {
			if (self::$config->get("commands")[$keyCmd]) {
				$this->getServer()->getCommandMap()->registerAll($this->getName(), $valueCmd);
			}
		}

	}

	private function loadLanguage(): void
	{
		$lang = self::$config->get("language", "en");
		$this->saveResource("language/$lang.yml");
		self::$messages = (new Config($this->getDataFolder() . "/language/$lang.yml"))->getAll();
	}

	public static function getInstance(): self
	{
		return self::$instance;
	}

}