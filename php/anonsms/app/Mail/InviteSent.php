<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;

// %FIXME: rename to InviteCreated
class InviteSent extends Mailable
{
    use Queueable, SerializesModels;

    protected $_invite = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invite)
    {
        $this->_invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromEmail = User::getSiteFromEmail();
        return $this->from($fromEmail)
            ->markdown('emails.invites.created', [
                'invite'=>$this->_invite,
            ]);
    }
}
