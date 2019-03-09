<?php
namespace App\Http\Middleware;

use PsgcLaravelPackages\AccessControl\AccessManager;
use App\Models\User;
use App\Models\Widget;
use App\Models\Ownable;

class CheckApiRole extends AccessManager
{

    protected function setSuperadminRoles()
    {
        $this->_superadminRoles = ['admin'];
    }

    protected function accessMatrix() {

        // keyed by route names...

        return [

            // ---Accounts ---

            'api.accounts.*'=>[
                'manager'=>true,
                'owner'=>true,
                'mts'=>true,
            ],
            'api.accounts.show'=>[
                //'manager'=>true,
                'owner'=>false,
                //'mts'=>false,
            ],
            // HERE WEDNESDAY


            // --- Widgets ---

            'api.widgets.index'=>[
                'manager'=>true,
                'owner'=>true,
            ],
            'api.widgets.show'=>[
                'manager'=>function($user,$routeParams,$queryParams) { // delegate as closure
                    // %FIXME %TODO: delegate this callback/lambda to model class (?)
                    $widget = Widget::findOrFail($routeParams['widget']);
                    if ($widget instanceof Ownable) {
                        $isAllowed = $widget->isOwnedBy();
                        //$isAllowed = ($widget->owner_id == $this->_sessionUser->id);
                    }
                    return $isAllowed;
                },
                'owner'=>function($user,$routeParams,$queryParams) { // delegate as closure
                    // %FIXME %TODO: delegate this callback/lambda to model class (?)
                    $widget = Widget::findOrFail($routeParams['widget']);
                    if ($widget instanceof Ownable) {
                        $isAllowed = $widget->isOwnedBy();
                        //$isAllowed = ($widget->owner_id == $this->_sessionUser->id);
                    }
                    return $isAllowed;
                },
            ],
            /*
            ...
            'site.profiles.show'=>[
                //'super-admin'=>'all',
                'fielder'=>function($user,$routeParams,$queryParams) {
                    $user = \App\Models\User::findByUsername($routeParams['username']);
                    $isAllowed = ($user->id == $this->_sessionUser->id);
                    return $isAllowed;
                },
                'project-manager'=>function($user,$routeParams,$queryParams) {
                    $user = \App\Models\User::findByUsername($routeParams['username']);
                    $isAllowed = ($user->id == $this->_sessionUser->id);
                    return $isAllowed;
                },
            ],
            ...
            'agency.formcomponents.show'=>[
                //'super-admin'=>'all',
                'agency-admin'=>function($user,$routeParams,$queryParams) {
                    $agency = $user->ofAgency();
                    $formcomponent = \App\Models\Formcomponent::findBySlug($routeParams['formcomponent']);
                    $isAllowed = \App\Libs\AccessControl::isOperationOnFormcomponentByOrganizationAllowed($formcomponent,$agency,'read');
                    return $isAllowed;
                },
                'department-admin'=>function($user,$routeParams,$queryParams) {
                $department = $user->ofDepartment();
                    $formcomponent = \App\Models\Formcomponent::findBySlug($routeParams['formcomponent']);
                    $isAllowed = \App\Libs\AccessControl::isOperationOnFormcomponentByOrganizationAllowed($formcomponent,$department,'read');
                    return $isAllowed;
                },
            ],
            ...
             */
        ];
    }

}
