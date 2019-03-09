<?php 
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;
use PsgcLaravelPackages\Utils\SmartEnum;

class Activitymessage extends BaseModel implements Guidable, Sluggable, Collectable, Deletable
{
    use CollectableTraits;

    //use Guidable;
    protected $guarded = ['id','guid','slug','created_at','updated_at'];

    // Default Validation Rules: may be overridden in controller...but NOTE these are used in renderFormLabel() and isFieldRequired() !
    public static $vrules = [];


    //--------------------------------------------
    // Relations
    //--------------------------------------------

    public function sender() {
        return $this->belongsTo('\App\Models\User', 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo('\App\Models\User', 'receiver_id');
    }

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    public static function makeCslug($sender,$receiver) 
    {
        /*
        return ( $sender->id < $receiver->id ) 
                    ? ($sender->username.'-'.$receiver->username)
                    : ($receiver->username.'-'.$sender->username);
         */
        return ( $sender->id < $receiver->id ) 
                    ? ($sender->id.'-'.$receiver->id)
                    : ($receiver->id.'-'.$sender->id);

    }

    // %%% --- Implement Sluggable Interface ---

    public function sluggableFields() : array
    {
        return ['sender_id','receiver_id'];
    }


    // %%% --- Implement Deletable Interface ---

    public function isDeletable() : bool
    {
        return true; // or some condition...ie, all relations deleted first
    }

    // %%% --- Implement Collectable Interface ---

    // queryApplyFilter
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
            $q1->where('slug', 'like', '%'.$searchStr.'%');
            $q1->orWhere('guid', 'like', $searchStr.'%');
            $q1->orWhere('amcontent', 'like', '%'.$searchStr.'%');
        });
        return $query;

    } // applySearch()


    // %%% --- Model Traits Overrides (via BaseModel) ---

    public static function _renderFieldKey(string $key) : string
    {
        $key = trim($key);
        switch ($key) {
            case 'sender_id':
                $key = 'Sender';
                break;
            case 'receiver_id':
                $key = 'Receiver';
                break;
            default:
                $key =  parent::_renderFieldKey($key);
        }
        return $key;
    }

    public function renderField(string $field) : ?string
    {
        $key = trim($field);
        switch ($key) {
            case 'sender_id':
                if ( $this->sender instanceof Namable ) {
                    return $this->sender->renderName();
                } else {
                    throw new \Exception("Sender must implement Nameable");
                }
                break;
            case 'receiver_id':
                if ( $this->receiver instanceof Namable ) {
                    return $this->receiver->renderName();
                } else {
                    throw new \Exception("Receiver must implement Nameable");
                }
                break;
            default:
                return parent::renderField($field);
        }
    }

}

// See:
    // https://stackoverflow.com/questions/39246777/how-to-avoid-manually-passing-my-registry-container-into-constructor-of-every-n/39256879#39256879
    // https://stackoverflow.com/questions/28954168/php-how-to-use-a-class-function-as-a-callback
