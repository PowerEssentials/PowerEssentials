<?php

namespace angga7togk\poweressentials\commands\healfeed;

use angga7togk\poweressentials\commands\PECommand;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class FeedCommand extends PECommand
{

  public function __construct()
  {
    parent::__construct('feed', 'feeding', '/feed', ['eat']);
    $this->setPermission('feed');
    $this->setPrefix('healfeed.prefix');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {

    if (isset($args[0])) {
      if (!$sender->hasPermission(self::PREFIX_PERMISSION . 'feed.other')) {
        $sender->sendMessage($prefix . $lang->translateString('error.permission'));
        return;
      }
      $target = Server::getInstance()->getPlayerExact($args[0]);
      if ($target == null) {
        $sender->sendMessage($prefix . $lang->translateString('error.player.null'));
        return;
      }
      $target->getHungerManager()->setFood($target->getHungerManager()->getMaxFood());
      $target->sendMessage($prefix . $lang->translateString('healfeed.feed'));
      $sender->sendMessage($prefix . $lang->translateString('healfeed.feed.other', [$target->getName()]));
    } else {
      if ($sender instanceof Player) {
        $sender->getHungerManager()->setFood($sender->getHungerManager()->getMaxFood());
        $sender->sendMessage($prefix . $lang->translateString('healfeed.feed'));
      } else {
        $sender->sendMessage($prefix . $lang->translateString('error.console'));
      }
    }
  }
}
