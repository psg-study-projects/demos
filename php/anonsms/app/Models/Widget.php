<?php 
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;
use PsgcLaravelPackages\Utils\SmartEnum;
//use App\Models\Ownable;

class Widget extends BaseModel implements Guidable, Sluggable, Collectable, Ownable, Deletable
{
    use CollectableTraits;

    //use Guidable;
    protected $guarded = ['id','guid','slug','created_at','updated_at'];

    // Default Validation Rules: may be overridden in controller...but NOTE these are used in renderFormLabel() and isFieldRequired() !
    public static $vrules = [
            'wname' => 'required',
            'account_id' => 'required|integer',
            'wstate' => 'required', // %TODO: force it to be in set range of enum values
            // https://stackoverflow.com/questions/28793716/how-add-custom-validation-rules-when-using-form-request-validation-in-laravel-5
        ];

    /*
    protected $dispatchesEvents = [
        'creating' => UserSaved::class,
        //'deleted' => UserDeleted::class,
    ];
     */

    //--------------------------------------------
    // Relations
    //--------------------------------------------

    public function account() { // owner
        return $this->belongsTo('App\Models\Account');
    }

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    // %%% --- Implement Sluggable Interface ---

    public function sluggableFields() : array
    {
        return ['wname'];
    }


    // %%% --- Implement Deletable Interface ---

    public function isDeletable() : bool
    {
        return false; // or some condition...ie, all relations deleted first
    }

    // %%% --- Implement Ownable Interface ---

    // if $obj is null, checking will default to the session user
    public function isOwnedBy(Model $obj=null) : bool
    {
        // Widgets are owned by accounts. For a user to have access to a widget, that widget's account must be owned by the user
        $is = false;

        // poor-man's switch() statement
        do {
            if ( is_null($obj) && !Auth::guest() ) {
                // assume logged in user
                $is = $this->account->owner->id === Auth::user()->id; // user owns widget's account
                break;
            }
            if ($obj instanceof Account) {
                $is = $this->account_id === $obj->id;
                break;
            }
        } while(0);

        return $is;
    }

    public static function getOwnerSelectOptions($includeBlank=true, $keyField='id', $filters=[]) : array
    {
        $options = Account::getSelectOptions($includeBlank,$keyField,$filters);
        return $options;
    }

    // %%% --- Implement Collectable Interface ---

    // queryApplyFilter
    public static function filterQuery(&$query,$filters)
    {
        if ( !empty($filters['account_id']) ) {
            $query->where('account_id',$filters['account_id']);
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
            $q1->orWhere('wname', 'like', $searchStr.'%');
        });
        return $query;

    } // applySearch()


    // %%% --- Overrides in Model Traits (via BaseModel) ---

    public static function _renderFieldKey(string $key) : string
    {
        $key = trim($key);
        switch ($key) {
            case 'account_id':
                $key = 'Account';
                break;
            case 'wname':
                $key = 'Widget Name';
                break;
            case 'wstate':
                $key = 'Widget Status';
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
            case 'account_id':
                return $this->account->aname;
                break;
            case 'wstate':
                if ( empty($this->wstate) ) {
                    return 'N/A';
                } else {
                    return WstateEnum::render($this->wstate);
                }
                break;
            default:
                return parent::renderField($field);
        }
    }

    // %%% --- Nameable Interface Overrides (via BaseModel) ---

    public function renderName() : string 
    {
        return $this->wname;
    }

}

// See:
    // https://stackoverflow.com/questions/39246777/how-to-avoid-manually-passing-my-registry-container-into-constructor-of-every-n/39256879#39256879
    // https://stackoverflow.com/questions/28954168/php-how-to-use-a-class-function-as-a-callback
