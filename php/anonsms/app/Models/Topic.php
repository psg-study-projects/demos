<?php 
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;
use PsgcLaravelPackages\Utils\SmartEnum;
use PsgcLaravelPackages\Utils\ViewHelpers;

class Topic extends BaseModel implements Sluggable, Guidable, Collectable, Nameable, Selectable
{
    use CollectableTraits, ImportableTraits;

    //use Guidable;
    protected $guarded = ['id','guid','slug','created_at','updated_at'];

    // Default Validation Rules: may be overridden in controller...but NOTE these are used in renderFormLabel() and isFieldRequired() !
    public static $vrules = [ ];

    //--------------------------------------------
    // Relations
    //--------------------------------------------

    public function users() {
        return $this->hasMany('App\Models\User');
    }

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    // %%% --- Implement Sluggable Interface ---

    public function sluggableFields() : array
    {
        return ['tname'];
    }

    // --- Implement Selectable Interface ---

    public static function getSelectOptions($includeBlank=true, $keyField='id', $filters=[]) : array
    {
        $records = self::all();

        $options = [];
        if ($includeBlank) {
            $options[''] = '';
        }
        foreach ($records as $i => $r) {
            $options[$r->{$keyField}] = $r->renderName();
        }
        return $options;
    }

    // %%% --- Implement Collectable Interface ---

    public static function filterQuery(&$query,$filters)
    {
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
            $q1->orWhere('guid', 'like', $searchStr.'%');
        });
        return $query;

    }

    public static function sortQuery(&$query,$sorting)
    {
        if ( !empty($sorting['value']) ) {
            $direction = !empty($sorting['direction']) ? $sorting['direction'] : 'asc';
            switch ($sorting['value']) {
                default:
                    $query->orderBy($sorting['value'], $direction);
            }
        }
        return $query;
    }

    // %%% --- Overrides in Model Traits (via BaseModel) ---

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

    // %%% --- Nameable Interface Overrides (via BaseModel) ---

    public function renderName() : string 
    {
        return $this->renderField('tname');
    }

    // %%% --- Other ---


}
