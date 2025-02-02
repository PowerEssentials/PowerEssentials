<?php

namespace angga7togk\poweressentials\manager\user;

trait MuteTrait
{
    private array $mutes = [];

    public function mutePlayer(string $playerName, string $reason): void
    {
        $this->mutes[$playerName] = $reason;
        $this->saveMuteList();
    }

    public function unmutePlayer(string $playerName): void
    {
        unset($this->mutes[$playerName]);
        $this->saveMuteList();
    }

    public function isMuted(string $playerName): bool
    {
        return isset($this->mutes[$playerName]);
    }

    private function saveMuteList(): void
    {
        $config = PowerEssentials::getInstance()->getConfig();
        $config->set("muted_players", $this->mutes);
        $config->save();
    }

    public function loadMuteList(): void
    {
        $config = PowerEssentials::getInstance()->getConfig();
        $this->mutes = $config->get("muted_players", []);
    }
}
