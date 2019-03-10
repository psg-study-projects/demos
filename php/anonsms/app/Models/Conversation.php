<?php 
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;
use PsgcLaravelPackages\Utils\SmartEnum;

class Conversation extends BaseModel implements Guidable, Collectable, Deletable
{
    use CollectableTraits;

    //use Guidable;
    protected $guarded = ['id','guid','slug','created_at','updated_at'];

    public static $vrules = [];


    //--------------------------------------------
    // Relations
    //--------------------------------------------

    public function users() {
        return $this->belongsToMany('\App\Models\User');
    }
    public function activitymessages() {
        return $this->hasMany('\App\Models\Activitymessage');
    }

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    public function isDeletable() : bool
    {
        return true; // or some condition...ie, all relations deleted first
    }

    // %%% --- Implement Collectable Interface ---

    public static function filterQuery(&$query,$filters)
    {
        if ( !empty($filters['sender']) ) {
            $query->where('sender_id',$filters['sender']['id']);
        }
        if ( !empty($filters['receiver']) ) {
            $query->where('receiver_id',$filters['receiver']['id']);
        }
        return $query;
    }


    // queryApplySearch
    public static function searchQuery(&$query,$search)
    {
        if ( empty($search) || ( is_array($search) && !array_key_exists('value',$search) ) ) {
            return $query; // no search string, ignore
        }
        $searchStr = is_array($search) ? $search['value'] : $search; // latter is simple string
        $query->where( function ($q1) use($searchStr) {
            $q1->where('guid', 'like', '%'.$searchStr.'%');
        });
        return $query;

    } // applySearch()


    // %%% --- Model Traits Overrides (via BaseModel) ---

    public static function _renderFieldKey(string $key) : string
    {
        $key = trim($key);
        switch ($key) {
            default:
                $key =  parent::_renderFieldKey($key);
        }
        return $key;
    }

    public function renderField(string $field) : ?string
    {
        $key = trim($field);
        switch ($key) {
            default:
                return parent::renderField($field);
        }
    }

    // %%% --- Other ---

    // Retrieve the 1 other participant (ie, other than the session user)
    public function getPartner()
    {
        $sessionUser = \Auth::user();
        foreach ( $this->users as $u ) {
            if ( $sessionUser->id !== $u->id ) {
                return $u;
            }
        }
        return null; // not found, probably an error
    }

}
