<?php
namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

use DB;
use App\Http\Controllers\SiteController;
use App\Libs\DatatableUtils\TableContainer;
use App\Models\Conversation;
use App\Models\Activitymessage;
use App\Models\User;

class ConversationsController extends SiteController
{
    public function __construct()
    {
        parent::__construct();

        $this->_assetMgr::registerJsLibs([
        ]);
        $this->_assetMgr::registerJsInlines([
        ]);
        $this->_assetMgr::registerCssInlines([
            '/css/app/main/styles.css',
        ]);
    }
  
    public function store(Request $request)
    {
        $sessionUser = \Auth::user();
        $partner = User::where('username',$request->username)->firstOrFail();

        $conversation = $sessionUser->getConversationByPartner($partner);
//return \Response::json([ $conversation ]);

        if ( empty($conversation) ) {
            $conversation = DB::transaction(function () use ($sessionUser, $partner) {
                $conversation = Conversation::create();
                $conversation->users()->attach([$sessionUser->id,$partner->id]);
                return $conversation;
            });
        }

        if (!\Request::ajax()) {
            throw new \Exception('Requires AJAX');
        } else {
            return \Response::json([
                'url' => route('site.chat.show',$conversation->guid),
            ]);
        }
    }

}
