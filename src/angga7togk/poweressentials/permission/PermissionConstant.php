<?php

namespace angga7togk\poweressentials\permission;

class PermissionConstant
{

    public const ESSENTIALS_COMMAND_BROADCAST = "essentials.broadcast";
    public const ESSENTIALS_COMMAND_SETLOBBY = "essentials.setlobby";
    public const ESSENTIALS_COMMAND_LOBBY = "essentials.lobby";
    public const ESSENTIALS_COMMAND_FLY = "essentials.fly";
    public const ESSENTIALS_COMMAND_FLY_OTHER = "essentials.fly.other";
    public const ESSENTIALS_COMMAND_GMC = "essentials.gmc";
    public const ESSENTIALS_COMMAND_GMC_OTHER = "essentials.gmc.other";
    public const ESSENTIALS_COMMAND_GMS = "essentials.gms";
    public const ESSENTIALS_COMMAND_GMS_OTHER = "essentials.gms.other";
    public const ESSENTIALS_COMMAND_GMA = "essentials.gma";
    public const ESSENTIALS_COMMAND_GMA_OTHER = "essentials.gma.other";
    public const ESSENTIALS_COMMAND_GMSPC = "essentials.gmspc";
    public const ESSENTIALS_COMMAND_GMSPC_OTHER = "essentials.gmspc.other";
    public const ESSENTIALS_COMMAND_NICKNAME = "essentials.nickname";
    public const ESSENTIALS_COMMAND_NICKNAME_OTHER = "essentials.nickname.other";
    public const ESSENTIALS_ANTINAMESPACE_BYPASS = "essentials.antinamespace.bypass";

    /**
     * @return array
     */
    public static function all(): array {
        return [
            self::ESSENTIALS_COMMAND_BROADCAST,
            self::ESSENTIALS_COMMAND_SETLOBBY,
            self::ESSENTIALS_COMMAND_LOBBY,
            self::ESSENTIALS_COMMAND_FLY,
            self::ESSENTIALS_COMMAND_FLY_OTHER,
            self::ESSENTIALS_COMMAND_GMC,
            self::ESSENTIALS_COMMAND_GMC_OTHER,
            self::ESSENTIALS_COMMAND_GMS,
            self::ESSENTIALS_COMMAND_GMS_OTHER,
            self::ESSENTIALS_COMMAND_GMA,
            self::ESSENTIALS_COMMAND_GMA_OTHER,
            self::ESSENTIALS_COMMAND_GMSPC,
            self::ESSENTIALS_COMMAND_GMSPC_OTHER,
            self::ESSENTIALS_COMMAND_NICKNAME,
            self::ESSENTIALS_COMMAND_NICKNAME_OTHER,
            self::ESSENTIALS_ANTINAMESPACE_BYPASS
        ];
    }
}