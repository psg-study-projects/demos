<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Widget;
use Illuminate\Auth\Access\HandlesAuthorization;

// https://laravel.com/docs/5.6/authorization#via-middleware
class WidgetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the widget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Widget  $widget
     * @return mixed
     */
    public function view(User $user, Widget $widget)
    {
        //
    }

    /**
     * Determine whether the user can create widgets.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the widget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Widget  $widget
     * @return mixed
     */
    public function update(User $user, Widget $widget)
    {
        //
    }

    /**
     * Determine whether the user can delete the widget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Widget  $widget
     * @return mixed
     */
    public function delete(User $user, Widget $widget)
    {
        //
    }
}
