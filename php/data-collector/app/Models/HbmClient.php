<?php 
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Libs\Collector\Collectable;
use App\Libs\Collector\CollectableTraits;

class HbmClient extends BaseModel implements Collectable
{
    use CollectableTraits;

    protected $connection = 'cmsopen';
    protected $table      = 'hbm_client';
    protected $primaryKey = 'CLIENT_UNO';
    protected $guarded = ['CLIENT_UNO','created_at','updated_at'];

    //--------------------------------------------
    // Relations
    //--------------------------------------------

    public function matters() {
        // JOIN HBM_CLIENT B ON A.CLIENT_UNO=B.CLIENT_UNO
        return $this->hasMany('App\Models\HbmMatter','CLIENT_UNO','CLIENT_UNO');
    }

    // m2m
    public function employees() {
        // JOIN HBM_PERSNL OA ON OA.EMPL_UNO=C.EMPL_UNO
        // JOIN TBM_CLMAT_PART C ON B.CLIENT_UNO=C.CLIENT_UNO
        return $this->belongsToMany('App\Models\HbmPersnl','tbm_clmat_part','client_uno','empl_uno');
    }
    protected function origatty() 
    {
        if ( 'Intranet2.jmbm.local' === env('SERVER_ID' ) ) {
            //return $this->employees()->wherePivot('PART_CAT_CODE','orig');
            return $this->employees()->wherePivot('PART_CAT_CODE','orig')->wherePivot('TO_DATE','>=',DB::raw('CAST(GETDATE() AS DATE)'));
        } else {
            return $this->employees()->wherePivot('PART_CAT_CODE','orig')->wherePivot('TO_DATE','>=',DB::raw('CURDATE()'));
        }
    }
    public function getOrigAtty() {
        if ( 'Intranet2.jmbm.local' === env('SERVER_ID' ) ) {
            //return $this->employees()->wherePivot('PART_CAT_CODE','orig')->first();
            return $this->employees()->wherePivot('PART_CAT_CODE','orig')->wherePivot('TO_DATE','>=',DB::raw('CAST(GETDATE() AS DATE)'))->first();
        } else {
            return $this->employees()->wherePivot('PART_CAT_CODE','orig')->wherePivot('TO_DATE','>=',DB::raw('CURDATE()'))->first();
        }
    }
    public function getOrigAttys() {
        if ( 'Intranet2.jmbm.local' === env('SERVER_ID' ) ) {
            return $this->employees()->wherePivot('PART_CAT_CODE','orig')->wherePivot('TO_DATE','>=',DB::raw('CAST(GETDATE() AS DATE)'))->get();
        } else {
            return $this->employees()->wherePivot('PART_CAT_CODE','orig')->wherePivot('TO_DATE','>=',DB::raw('CURDATE()'))->get();
        }
    }

    /*
    public function clmatparts() {
        // JOIN TBM_CLMAT_PART C ON B.CLIENT_UNO=C.CLIENT_UNO
        return $this->hasMany('App\Models\TbmClmatPart','client_uno','client_uno');
    }
     */

    //--------------------------------------------
    // Methods
    //--------------------------------------------

    // %%% --- Implement Collectable Interface ---

    public static function filterQuery(&$query,$filters)
    {
        return $query;
    }


    // queryApplySearch
    public static function searchQuery(&$query,$search)
    {
        if ( empty($search) || ( is_array($search) && empty($search['value']) ) ) {
            return $query; // no search string, ignore
        }
        $searchStr = is_array($search) ? $search['value'] : $search; // latter is simple string
        $query->where( function ($q1) use($searchStr) {
            $q1->where('CLIENT_NAME', 'like', '%'.$searchStr.'%');
            $q1->orWhere('CLIENT_CODE', 'like', $searchStr.'%');
            $q1->orWhere('CLIENT_NUMBER', 'like', $searchStr.'%');
        });
        return $query;

    } // applySearch()


    // %%% --- Overrides in Model Traits (via BaseModel) ---

    public static function _renderFieldKey(string $key) : string
    {
        $key = strtolower(trim($key));
        switch ($key) {
            case 'orig_atty':
                $key = 'Orig Atty';
                break;
            case 'orig_attys':
                $key = 'Orig Attys';
                break;
            case 'client_name':
                $key = 'Client Name';
                break;
            case 'client_number':
                $key = 'Client #';
                break;
            case 'client_code':
                $key = 'Client Code ';
                break;
            case 'status_code':
                $key = 'Status Code ';
                break;
            case 'number_of_matters':
                $key = '# of Matters ';
                break;
            default:
                $key =  parent::_renderFieldKey($key);
        }
        return $key;
    }

    public function renderField(string $field) : ?string
    {
        $key = strtolower(trim($field));
        switch ($key) {
            case 'orig_atty':
                $oa = $this->getOrigAtty();
                return $oa ? $oa->renderName() : '--';
            case 'orig_attys':
                $oas = $this->getOrigAttys();
                return ($oas->count()>0) ? (function($inA) {
                    $outA = [];
                    foreach ($inA as $o) {
                        $outA[] = $o->renderName() ;
                    }
                    return implode(', ',$outA);
                })($oas) : '--';
            case 'number_of_matters':
                return count($this->matters);
            case 'status_code':
                return $this->STATUS_CODE ?? '--';
            default:
                return parent::renderField($field);
        }
    }

    // %%% --- Nameable Interface Overrides (via BaseModel) ---

    public function renderName() : string 
    {
        return $this->CLIENT_NAME;
    }

}
