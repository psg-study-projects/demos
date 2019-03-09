<?php 
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;
use PsgcLaravelPackages\Utils\SmartEnum;
//use App\Models\Ownable;

class MsgtypeEnum extends SmartEnum implements Selectable {

    const DM          = 'dm';
    const OP_WIDGET   = 'op_widghet';
    const OP_ACCOUNT  = 'op_account';

    public static $keymap = [
        self::DM         => 'Direct Message',
        self::OP_WIDGET  => 'Widget Operation',
        self::OP_ACCOUNT => 'Account Operation',
    ];

}

// %TODOS:
//  [ ] delete/trash: is tricky because receiver may delete but sender may keep. One way is to have a join
//        table that tracks 'delete' state per party involved (delete state here is more like 'visibility')
class Activitymessage extends BaseModel implements Guidable, Sluggable, Collectable, Deletable
{
    use CollectableTraits;

    //use Guidable;
    protected $guarded = ['id','guid','slug','created_at','updated_at'];

    // Default Validation Rules: may be overridden in controller...but NOTE these are used in renderFormLabel() and isFieldRequired() !
    public static $vrules = [
            'msgtype'      => 'required|alpha_dash',
            'sender_type'   => 'required|alpha_dash',
            'sender_id'    => 'required|integer',
            'receiver_type' => 'required|alpha_dash',
            'receiver_id'  => 'required|integer',
            'subject'      => 'required',
        ];

    /*
    protected $dispatchesEvents = [
        'creating' => UserSaved::class,
        //'deleted' => UserDeleted::class,
    ];
     */

    //--------------------------------------------
    // Boot
    //--------------------------------------------
    public static function boot()
    {
        parent::boot();

        // Handle encoding/decoding of json fields
        static::creating(function ($model) {
            if ( array_key_exists('meta', $model->toArray()) ) {
                $model->meta = json_encode($model->meta);
            }
        });
        static::retrieved(function ($model) {
            if ( array_key_exists('meta', $model->toArray()) ) {
                $model->meta = json_decode($model->meta,true);
            }
        });
    }

    //--------------------------------------------
    // Relations
    //--------------------------------------------

    public function sender() {
        return $this->morphTo();
    }

    public function receiver() {
        return $this->morphTo();
    }

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    // %%% --- Implement Sluggable Interface ---

    public function sluggableFields() : array
    {
        return ['subject'];
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
        if ( !empty($filters['msgtype']) ) {
            $query->where('msgtype',$filters['msgtype']);
        }
        if ( !empty($filters['sender']) ) {
            $query->where('sender_id',$filters['sender']['id']);
            $query->where('sender_type',$filters['sender']['type']);
        }
        if ( !empty($filters['receiver']) ) {
            $query->where('receiver_id',$filters['receiver']['id']);
            $query->where('receiver_type',$filters['receiver']['type']);
        }
        return $query;
    }


    // queryApplySearch
    public static function searchQuery(&$query,$search)
    {
//dd('Widget::searchQuery()',$search);
        if ( empty($search) || ( is_array($search) && !array_key_exists('value',$search) ) ) {
            return $query; // no search string, ignore
        }
        $searchStr = is_array($search) ? $search['value'] : $search; // latter is simple string
        $query->where( function ($q1) use($searchStr) {
            $q1->where('slug', 'like', '%'.$searchStr.'%');
            $q1->orWhere('guid', 'like', $searchStr.'%');
            $q1->orWhere('subject', 'like', $searchStr.'%');
            $q1->orWhere('message', 'like', $searchStr.'%');
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
