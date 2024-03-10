<?php

namespace angga7togk\poweressentials\permission;

use pocketmine\lang\KnownTranslationFactory as l10n;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

class PermissionsManager
{

    /**
     * @return void
     */
    public static function init(): void {
        $consoleRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_CONSOLE, l10n::pocketmine_permission_group_console()));
        $operatorRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_OPERATOR, l10n::pocketmine_permission_group_operator()), [$consoleRoot]);
        $everyoneRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_USER, l10n::pocketmine_permission_group_user()), [$operatorRoot]);
        foreach (PermissionConstant::all() as $k => $v) {
            match ($v) {
                DefaultPermissions::ROOT_USER => DefaultPermissions::registerPermission(new Permission($k), [$everyoneRoot]),
                DefaultPermissions::ROOT_OPERATOR => DefaultPermissions::registerPermission(new Permission($k), [$operatorRoot]),
                DefaultPermissions::ROOT_CONSOLE => DefaultPermissions::registerPermission(new Permission($k), [$consoleRoot])
            };
        }
    }
}