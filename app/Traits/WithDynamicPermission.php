<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait WithDynamicPermission
{
    protected array $permissionMap = [];

    protected function checkPermission(string $method)
    {
        if (!isset($this->permissionMap[$method])) {
            return;
        }

        $requiredPermissions = $this->permissionMap[$method];

        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $permissionsViaRoles = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        foreach ($requiredPermissions as $perm) {
            if (!in_array($perm, $permissionsViaRoles)) {
                Log::warning("Permission via Role denied for user {$user->id} on method: $method, permission: $perm");
                return false;
            }
        }

        return true;
    }

    protected function authorizePermission(string $method): bool
    {
        if (!$this->checkPermission($method)) {
            return false;
        }

        return true;
    }
}
