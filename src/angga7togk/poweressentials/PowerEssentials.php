<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\commands\CoordinatesCommand;
use angga7togk\poweressentials\commands\FlyCommand;
use angga7togk\poweressentials\commands\gamemode\AdvantureCommand;
use angga7togk\poweressentials\commands\gamemode\CreativeCommand;
use angga7togk\poweressentials\commands\gamemode\SpectatorCommand;
use angga7togk\poweressentials\commands\gamemode\SurvivalCommand;
use angga7togk\poweressentials\commands\healfeed\FeedCommand;
use angga7togk\poweressentials\commands\healfeed\HealCommand;
use angga7togk\poweressentials\commands\home\DelHomeCommand;
use angga7togk\poweressentials\commands\home\HomeCommand;
use angga7togk\poweressentials\commands\home\SetHomeCommand;
use angga7togk\poweressentials\commands\lobby\LobbyCommand;
use angga7togk\poweressentials\commands\lobby\SetLobbyCommand;
use angga7togk\poweressentials\commands\NicknameCommand;
use angga7togk\poweressentials\commands\warp\AddWarpCommand;
use angga7togk\poweressentials\commands\warp\DelWarpCommand;
use angga7togk\poweressentials\commands\warp\WarpCommand;
use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\manager\DataManager;
use angga7togk\poweressentials\manager\UserManager;
use angga7togk\poweressentials\message\Message;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class PowerEssentials extends PluginBase
{
	private static PowerEssentials $instance;
	private DataManager $dataManager;

	/** @var UserManager[] */
	private array $userManagers = [];


	protected function onLoad(): void
	{
		self::$instance = $this;
	}

	public function onEnable(): void
	{
		PEConfig::init();
		Message::init();
		$this->loadCommands();
		$this->loadListeners();
		$this->dataManager = new DataManager($this);
	}

	public function registerUserManager(Player $player): void
	{
		$this->userManagers[$player->getName()] = new UserManager($player);
	}

	public function unregisterUserManager(Player $player): void
	{
		unset($this->userManagers[$player->getName()]);
	}

	public function getUserManager(Player $player): UserManager
	{
		return $this->userManagers[$player->getName()] ?? $this->userManagers[$player->getName()] = new UserManager($player);
	}

	public function getDataManager(): DataManager
	{
		return $this->dataManager;
	}

	private function loadListeners(): void
	{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}

	private function loadCommands(): void
	{
		$commands = [
			'lobby' => [new LobbyCommand(), new SetLobbyCommand()],
			'fly' => [new FlyCommand()],
			'gamemode' => [new AdvantureCommand(), new CreativeCommand(), new SpectatorCommand(), new SurvivalCommand()],
			'nickname' => [new NicknameCommand()],
			'home' => [new HomeCommand(), new DelHomeCommand, new SetHomeCommand()],
			'coordinates' => [new CoordinatesCommand()],
			'warp' => [new WarpCommand(), new AddWarpCommand(), new DelWarpCommand()],
			'heal' => [new HealCommand()],
			'feed' => [new FeedCommand()],
		];

		foreach ($commands as $keyCmd => $valueCmd) {
			if (!PEConfig::isCommandDisabled($keyCmd)) {
				$this->getServer()->getCommandMap()->registerAll($this->getName(), $valueCmd);
			}
		}
	}

	public static function getInstance(): self
	{
		return self::$instance;
	}
}
