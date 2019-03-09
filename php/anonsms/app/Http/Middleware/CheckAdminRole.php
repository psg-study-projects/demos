<?php
namespace App\Http\Middleware;

use PsgcLaravelPackages\AccessControl\AccessManager;

class CheckAdminRole extends AccessManager
{

    protected function setSuperadminRoles()
    {
        $this->_superadminRoles = ['admin'];
    }

    protected function accessMatrix() {

        // keyed by route names...

        return []; // should disallow everyone except admin
    }

}
