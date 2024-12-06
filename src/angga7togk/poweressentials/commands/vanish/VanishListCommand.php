<?php

namespace angga7togk\poweressentials\commands\vanish;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\command\CommandSender;

class VanishListCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('vanishlist', 'List vanished players', '/vanishlist');
    $this->setPrefix('vanish.prefix');
    $this->setPermission('vanish.see');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    $sender->sendMessage($prefix . implode(', ', VanishCommand::getVanishedPlayers()));
  }
}
