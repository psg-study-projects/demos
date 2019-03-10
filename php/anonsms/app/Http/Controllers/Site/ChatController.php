<?php
namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

use DB;
use App\Http\Controllers\SiteController;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Activitymessage;

class ChatController extends SiteController
{
    public function __construct()
    {
        parent::__construct();

        $this->_assetMgr::registerJsLibs([ ]);
        $this->_assetMgr::registerJsInlines([ ]);
        $this->_assetMgr::registerCssInlines([
            '/css/app/main/styles.css',
        ]);
    }
  
    public function show(Request $request, $conversation)
    {
        $sessionUser = \Auth::user();
        $conversation = Conversation::where('guid',$conversation)->firstOrFail();

        \View::share('g_php2jsVars',$this->_php2jsVars);

        return \View::make('site.chat.show', [
            'session_user'  => $sessionUser,
            'partner' => $conversation->getPartner(),
            'conversation' => $conversation,
            'messages' => $conversation->activitymessages->sortByDesc('created_at'),
        ]);
    }
}
