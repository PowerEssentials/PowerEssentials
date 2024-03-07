<?php

namespace angga7togk\poweressentials\permission;

use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

class PermissionsManager
{

    /**
     * @return void
     */
    public static function init(): void {
        foreach (PermissionConstant::all() as $permissions) {
            PermissionManager::getInstance()->addPermission(new Permission($permissions));
        }
    }
}