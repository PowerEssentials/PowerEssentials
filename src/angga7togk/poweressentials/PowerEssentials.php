<?php

namespace angga7togk\poweressentials;

use angga7togk\poweressentials\commands\AFKCommand;
use angga7togk\poweressentials\commands\banitem\BanItemCommand;
use angga7togk\poweressentials\commands\banitem\BanItemListCommand;
use angga7togk\poweressentials\commands\banitem\UnbanItemCommand;
use angga7togk\poweressentials\commands\BlessCommand;
use angga7togk\poweressentials\commands\CoordinatesCommand;
use angga7togk\poweressentials\commands\FlyCommand;
use angga7togk\poweressentials\commands\gamemode\AdvantureCommand;
use angga7togk\poweressentials\commands\gamemode\CreativeCommand;
use angga7togk\poweressentials\commands\gamemode\SpectatorCommand;
use angga7togk\poweressentials\commands\gamemode\SurvivalCommand;
use angga7togk\poweressentials\commands\GetPositionCommand;
use angga7togk\poweressentials\commands\healfeed\FeedCommand;
use angga7togk\poweressentials\commands\healfeed\HealCommand;
use angga7togk\poweressentials\commands\home\DelHomeCommand;
use angga7togk\poweressentials\commands\home\HomeCommand;
use angga7togk\poweressentials\commands\home\SetHomeCommand;
use angga7togk\poweressentials\commands\ItemIDCommand;
use angga7togk\poweressentials\commands\KickAllCommand;
use angga7togk\poweressentials\commands\lobby\LobbyCommand;
use angga7togk\poweressentials\commands\lobby\SetLobbyCommand;
use angga7togk\poweressentials\commands\NicknameCommand;
use angga7togk\poweressentials\commands\OneSleepCancelCommand;
use angga7togk\poweressentials\commands\RepairCommand;
use angga7togk\poweressentials\commands\RTPCommand;
use angga7togk\poweressentials\commands\SendItemCommand;
use angga7togk\poweressentials\commands\SizeCommand;
use angga7togk\poweressentials\commands\SudoCommand;
use angga7togk\poweressentials\commands\TPACommand;
use angga7togk\poweressentials\commands\TPAllCommand;
use angga7togk\poweressentials\commands\vanish\VanishCommand;
use angga7togk\poweressentials\commands\vanish\VanishListCommand;
use angga7togk\poweressentials\commands\warp\AddWarpCommand;
use angga7togk\poweressentials\commands\warp\DelWarpCommand;
use angga7togk\poweressentials\commands\warp\WarpCommand;
use angga7togk\poweressentials\commands\WorldProtectCommand;
use angga7togk\poweressentials\config\PEConfig;
use angga7togk\poweressentials\i18n\PELang;
use angga7togk\poweressentials\listeners\EventListener;
use angga7togk\poweressentials\listeners\WorldProtectListener;
use angga7togk\poweressentials\manager\DataManager;
use angga7togk\poweressentials\manager\UserManager;
use angga7togk\poweressentials\task\VanishTask;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class PowerEssentials extends PluginBase
{
	private static PowerEssentials $instance;
	private DataManager $dataManager;

	/** @var UserManager[] */
	private array $userManagers = [];

	private PELang $lang;


	protected function onLoad(): void
	{
		self::$instance = $this;
	}

	public function onEnable(): void
	{
		$this->loadResources();
		$this->dataManager = new DataManager($this);
		$this->loadCommands();
		$this->loadTasks();
		$this->loadListeners();
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

	private function loadResources(): void
	{
		/** place this on first */
		PEConfig::init();

		$oldLanguageDir = $this->getDataFolder() . "language";
		if (file_exists($oldLanguageDir)) {
			$this->unlinkRecursive($oldLanguageDir);
		}

		$resources = $this->getResources();
		foreach ($resources as $resource) {
			$fileName = $resource->getFileName();
			$extension = $this->getFileExtension($fileName);

			if ($extension !== PELang::LANGUAGE_EXTENSION) continue;

			$lang = new PELang($resource);
			$this->getLogger()->debug("Loaded language file: {$lang->getLang()}.ini");
		}
		PELang::setConsoleLocale(PEConfig::getLang());
		$this->lang = PELang::fromConsole();
		$message = $this->lang->translateString("language.selected", [
			$this->lang->getName(),
			$this->lang->getLang(),
		]);
		$this->getLogger()->info($message);
	}

	private function unlinkRecursive(string $dir): bool
	{
		$files = array_diff(scandir($dir), [".", ".."]);
		foreach ($files as $file) {
			$path = $dir . DIRECTORY_SEPARATOR . $file;
			is_dir($path) ? $this->unlinkRecursive($path) : unlink($path);
		}
		return rmdir($dir);
	}

	private function getFileExtension(string $path): string
	{
		$exploded = explode(".", $path);
		return $exploded[array_key_last($exploded)];
	}

	private function loadTasks(): void
	{
		$this->getScheduler()->scheduleRepeatingTask(new VanishTask(), 35);
	}

	private function loadListeners(): void
	{
		$this->getServer()->getPluginManager()->registerEvents(new WorldProtectListener($this), $this);
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
			'sudo' => [new SudoCommand()],
			'banitem' => [new BanItemCommand(), new UnbanItemCommand(), new BanItemListCommand()],
			'worldprotect' => [new WorldProtectCommand()],
			'vanish' => [new VanishCommand(), new VanishListCommand()],
			'rtp' => [new RTPCommand()],
			'size' => [new SizeCommand()],
			'afk' => [new AFKCommand()],
			'onesleep' => [new OneSleepCancelCommand()],
			'tpa' => [new TPACommand()],
			'tpall' => [new TPAllCommand()],
			'itemid' => [new ItemIDCommand()],
			'repair' => [new RepairCommand()],
			'senditem' => [new SendItemCommand()],
			'getpos' => [new GetPositionCommand()],
			'bless' => [new BlessCommand()],
			'kickall' => [new KickAllCommand()]
		];

		foreach ($commands as $keyCmd => $valueCmd) {
			if (!PEConfig::isCommandDisabled($keyCmd)) {
				$this->getServer()->getCommandMap()->registerAll($this->getName(), $valueCmd);
			}
		}
	}

	public static function getInstance(): PowerEssentials
	{
		return self::$instance;
	}
}
