<?php

namespace angga7togk\poweressentials\commands;

use angga7togk\poweressentials\config\PEConfig;
use pocketmine\command\CommandSender;
use angga7togk\poweressentials\i18n\PELang;
use pocketmine\block\Water;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\World;

class RandomTeleport extends PECommand
{

  public function __construct()
  {
    parent::__construct('randomteleport', 'Random teleport to world', "/rtp [world]", ['rtp', 'wilderness', 'wild']);
    $this->setPrefix('rtp.prefix');
    $this->setPermission('randomteleport');
  }

  public function run(CommandSender $sender, string $prefix, PELang $lang, array $args): void
  {
    if (!$sender instanceof Player) {
      $sender->sendMessage($prefix . $lang->translateString('error.console'));
      return;
    }

    $worldName = $args[0] ?? $sender->getWorld()->getFolderName();
    $world = Server::getInstance()->getWorldManager()->getWorldByName($worldName);
    if ($world === null) {
      $sender->sendMessage($prefix . $lang->translateString('error.world.null'));
      return;
    }

    if (PEConfig::isRandomTeleportWorldBlocked($world)) {
      $sender->sendMessage($prefix . $lang->translateString('error.blacklist', [$world->getFolderName()]));
      return;
    }

    $rangeX = PEConfig::getRandomTeleportRange("x");
    $rangeZ = PEConfig::getRandomTeleportRange("z");
    $timeOut = PEConfig::getRandomTeleportTimeOut();

    $sender->sendMessage($prefix . $lang->translateString('rtp.teleporting'));

    $this->attemptTeleport($sender, $prefix, $lang, $world, $rangeX, $rangeZ, $timeOut);
  }

  private function attemptTeleport(Player $sender, string $prefix, PELang $lang, World $world, array $rangeX, array $rangeZ, int &$timeOut): void
  {
    if ($timeOut <= 0) {
      $sender->sendMessage($prefix . $lang->translateString('rtp.error.failed', [$world->getFolderName()]));
      return;
    }

    $x = random_int($rangeX[0], $rangeX[1]);
    $z = random_int($rangeZ[0], $rangeZ[1]);

    $world->orderChunkPopulation($x >> 4, $z >> 4, null)->onCompletion(
      function () use ($sender, $world, $x, $z, &$timeOut, $prefix, $lang): void {
        $y = $world->getHighestBlockAt($x, $z) + 1;
        $blockAt = $world->getBlockAt($x, $y - 2, $z);

        if (($blockAt instanceof Water || strtolower($blockAt->getName()) === 'water') && PEConfig::isRandomTeleportAntiWater()) {
          $timeOut--;
          $this->attemptTeleport($sender, $prefix, $lang, $world, PEConfig::getRandomTeleportRange("x"), PEConfig::getRandomTeleportRange("z"), $timeOut);
        } else {
          $sender->teleport(new Position($x, $y, $z, $world));
          $sender->sendMessage($prefix . $lang->translateString('rtp.success', [$world->getFolderName()]));
        }
      },
      function () use ($sender, $prefix, $lang): void {
        $sender->sendMessage($prefix . $lang->translateString('error.chunk.teleport'));
      }
    );
  }
}
