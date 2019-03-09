<?php 
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use PsgcLaravelPackages\Collector\Collectable;
use PsgcLaravelPackages\Collector\CollectableTraits;

class Usstate extends BaseModel implements Collectable, Selectable
{
    use CollectableTraits;

    protected $guarded = ['id','created_at','updated_at'];

    public static $vrules = [
            'state_name' => 'required|alpha',
            'state_code' => 'required|alpha|between:2,3',
            'country' => 'required|alpha_dash',
        ];


    //--------------------------------------------
    // Relations
    //--------------------------------------------

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    // %%% --- Implement Collectable Interface ---

    // queryApplyFilter
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
            $q1->orWhere('slug', 'like', $searchStr.'%');
            $q1->orWhere('period_year', 'like', $searchStr.'%');
            $q1->orWhere('week_number', 'like', $searchStr.'%');
        });
        return $query;

    } // applySearch()

    // --- Implement Selectable Interface ---

    public static function getSelectOptions($includeBlank=true, $keyField='id', $filters=[]) : array
    {
        $records = self::all(); // %TODO : add filter capability
        $options = [];
        if ($includeBlank) {
            $options[''] = '';
        }
        foreach ($records as $i => $r) {
            $options[$r->{$keyField}] = $r->state_code;
            //$options[$r->{$keyField}] = $r->state_name;
        }
        return $options;
    }


    // %%% --- Nameable Interface Overrides (via BaseModel) ---

    public function renderName() : string 
    {
        return $this->slug;
    }

}
