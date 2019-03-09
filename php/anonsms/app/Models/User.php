<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Zizaco\Entrust\Traits\EntrustUserTrait;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;
use PsgcLaravelPackages\Utils\ModelTraits; // use here as User does *not* extend BaseModel
use App\Libs\DatatableUtils\FieldRenderable;
use App\Libs\UserTraits;

class User extends Authenticatable implements Collectable, Deletable, Selectable, Nameable, FieldRenderable
{
    use Notifiable, UserTraits, EntrustUserTrait, CollectableTraits, ModelTraits;

    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden = [ 'password', 'remember_token', 'token' ];

    //--------------------------------------------
    // Boot
    //--------------------------------------------
    public static function boot()
    {
        parent::boot();

        // http://stackoverflow.com/questions/14174070/automatically-deleting-related-rows-in-laravel-eloquent-orm
        // https://laravel.com/docs/5.2/eloquent-relationships   Attaching/Detaching
        static::deleting(function ($model) {
            $model->roles()->detach(); //remove roles (pivots) for this user
        });
    }


    //--------------------------------------------
    // Relations
    //--------------------------------------------

    public function usercustom() {
        return $this->hasOne('App\Models\Usercustom');
    }

    public function employee() {
        return $this->hasOne('App\Models\Employee');
    }

    public function accounts() {
        return $this->hasMany('App\Models\Account','owner_id');
    }

    // Activity Messages sent/received
    public function sentmessages() {
        return $this->morphMany('\App\Models\Activitymessage', 'sender');
    }
    public function receivedmessages() {
        return $this->morphMany('\App\Models\Activitymessage', 'receiver');
    }

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    // --- Implement Selectable Interface ---

    public static function getSelectOptions($includeBlank=true, $keyField='id', $filters=[]) : array
    {
        if ( count($filters) > 0 ) {

            $query = self::query();

            if ( !empty($filters['role']) ) {
                $query->whereHas('roles', function ($q1) use($filters) { 
                    $q1->where('name', $filters['role']);
                });
            }
            $records = $query->get();

        } else {
            $records = self::all(); // %TODO : add filter capability
        }

        $options = [];
        if ($includeBlank) {
            $options[''] = '';
        }
        foreach ($records as $i => $r) {
            $options[$r->{$keyField}] = $r->renderFullname().' ('.$r->renderName().')';
        }
        return $options;
    }

    // --- Implement Collectable Interface ---

    // queryApplyFilter
    public static function filterQuery(&$query,$filters)
    {
        if ( !empty($filters['is_confirmed']) ) {
            $query->where('is_confirmed',$filters['is_confirmed']);
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
//dd($searchStr);
        $query->where( function ($q1) use($searchStr) {
            $q1->where('username', 'like', '%'.$searchStr.'%');
            $q1->orWhere('email', 'like', $searchStr.'%');
            $q1->orWhere('firstname', 'like', $searchStr.'%');
            $q1->orWhere('lastname', 'like', $searchStr.'%');
            $q1->orWhereHas('roles', function($q2) use($searchStr) {
                $q2->where('name','like', $searchStr.'%');
            });
        });
        return $query;

    } // applySearch()


    // --- Implement Deletable Interface ---

    public function isDeletable() : bool
    {
        return false; // or some condition...ie, all relations deleted first
    }

    // %%% --- Implement Nameable Interface ---

    public function renderName() : string 
    {
        return $this->username;
    }


    // %%% --- Overrides in Model Traits (via BaseModel) ---

    public static function _renderFieldKey(string $key) : string
    {
        $key = trim($key);
        switch ($key) {
            case 'is_confirmed':
                $key = 'Confirmed?';
                break;
            case 'id':
                $key = 'PKID';
                break;
            case 'primaryrole': // virtual/pseudo-column
                $key = 'Role (primary)';
                break;
            case 'created_at':
                $key = 'Created';
                break;
            case 'updated_at':
                $key = 'Updated';
                break;
            default:
                $key = ucwords($key);
        }
        return $key;
    }

    public function renderField(string $field) : ?string
    {
        $key = trim($field);
        switch ($key) {
            case 'is_confirmed':
                return ViewHelpers::makeNiceBinary($this->is_confirmed);
            case 'primaryrole': // vitual/pseudo-column
                return $this->renderRoles();
            case 'created_at':
            case 'updated_at':
            case 'deleted_at':
                return ViewHelpers::makeNiceDate($this->{$field},1,1); // number format, include time
            default:
                return $this->{$field};
        }
    }

    // --- Misc. ---

    public function isAdmin()
    {
        $is = $this->hasRole('admin');
        return $is;
    }

    public function renderFullname() 
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function hasRole($roleSlug)
    {
        return $this->roles->contains('name', $roleSlug);
    }

    public function renderRoles() 
    {
        // render as a comma-separated string
        return implode( ', ',$this->roles->pluck('name')->all() );
    }

    public static function getRedirectRoute()
    {
        $sessionUser = \Auth::user();
        $firstRole = $sessionUser->roles->first();

        switch ($firstRole) {
            case 'admin':
                return route('admin.home.index');
        }

        return route('home.welcome');

    } // getRedirectRoute()

    // email to use for messages sent from the site
    public static function getSiteFromEmail()
    {
        $from = self::where('username','pgorgone')->firstOrFail();
        return $from->email;
    }

    // Send a direct message to another user
    public function sendDM(User $to, string $subject, string $message) : Activitymessage
    {
        $obj = Activitymessage::create([
            'msgtype' => MsgtypeEnum::DM,
            'sender_type' => 'users',
            'sender_id' => $this->id,
            'receiver_type' => 'users',
            'receiver_id' => $to->id,
            'subject' => $subject,
            'message' => $message,
        ]);
        return $obj;
    }

    public function countActivitymessages() : int
    {
        //return 4;
        return count($this->receivedmessages);
    }

    public function listActivitymessagesByType(string $amtype, int $take=null) : Collection
    {
        //return $this->receivedmessages->filter(function($r) use($amtype) { return $amtype===$r->msgtype; })->sortByDesc('created_at')->take($take);
        return Activitymessage::where('msgtype',$amtype)->sortByDesc('created_at')->take($take);
    }

    public function countActivitymessagesByType(string $amtype) : int
    {
        //return $this->receivedmessages->filter(function($r) use($amtype) { return $amtype===$r->msgtype; })->count();
        //return Activitymessage::where('msgtype',$amtype)->count();
        return $this->activitymessages()->where('msgtype',$amtype)->wherePivot('is_read',0)->count();
    }
}
