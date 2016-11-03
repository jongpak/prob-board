<?php

namespace App\Auth\PermissionManager;

use App\Auth\PermissionManagerAbstract;
use App\Entity\Permission;
use Core\Utils\EntityUtils\EntitySelect;

class DatabasePermissionManager extends PermissionManagerAbstract
{
    public function __construct(array $settings = [])
    {
    }

    /**
     * @return array|null
     */
    public function getRolesByOperation($operation)
    {
        $permissionRole = [];

        /** @var Permission */
        $permission = EntitySelect::select(Permission::class)
            ->criteria(['operation' => $operation])
            ->findOne();

        if ($permission === null) {
            return null;
        }

        foreach ($permission->getRoles() as $item) {
            $permissionRole[] = $item->getName();
        }

        return $permissionRole;
    }
}
