<?php

namespace angga7togk\poweressentials\permission;

use pocketmine\permission\DefaultPermissions;

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
            self::ESSENTIALS_COMMAND_BROADCAST => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_SETLOBBY => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_LOBBY => DefaultPermissions::ROOT_USER,
            self::ESSENTIALS_COMMAND_FLY => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_FLY_OTHER => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_GMC => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_GMC_OTHER => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_GMS => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_GMS_OTHER => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_GMA => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_GMA_OTHER => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_GMSPC => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_GMSPC_OTHER => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_NICKNAME => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_COMMAND_NICKNAME_OTHER => DefaultPermissions::ROOT_OPERATOR,
            self::ESSENTIALS_ANTINAMESPACE_BYPASS => DefaultPermissions::ROOT_OPERATOR
        ];
    }
}