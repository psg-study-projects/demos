<?php
namespace App\Observers;

use PsgcLaravelPackages\Utils\Guid;
use App\Models\Invite;

// %FIXME: should this be in or under Models folder (?)
//  ~ leaning toward 'yes'
//  ~ or even in Invite model's boot method (see BaseModel for ex)
class InviteObserver
{
    /**
     * Listen to the Invite creating event.
     *
     * @param  Invite  $invite
     * @return void
     */
    public function creating(Invite $invite)
    {
        //dd($invite->toArray());
        $invite->token = $this->generateToken();
    }

    /**
     * Listen to the Invite created event.
     *
     * @param  Invite  $invite
     * @return void
     */
    public function created(Invite $invite)
    {
        //event(new NewInviteWasCreated($invite));
    }

    /**
     * Generate random token, check if unique, if not regenerate.
     *
     * @return string $token
     */
    protected function generateToken()
    {
        $token = str_random(10);
        if ( Invite::where('token', $token)->first() ) {
            return $this->generateToken();
        }
        return $token;
    }
}
