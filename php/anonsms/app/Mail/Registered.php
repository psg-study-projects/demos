<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;

class Registered extends Mailable
{
    use Queueable, SerializesModels;

    protected $_user = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->_user = $user;
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
            ->markdown('emails.users.registered', [
                'user'=>$this->_user,
            ]);
    }
}
