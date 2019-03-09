<?php
namespace App\Libs;

use \App\Models\Cmscontent;
use \App\Libs\Utils;

class NavigationTaxonomy
{
    private $_navigationTaxonomy = [];

    public function __construct($toJson=false)
    {
        $cmscontents = Cmscontent::all();
        $this->_navigationTaxonomy = $this->_buildStruct($cmscontents, $toJson);
    }

    public function get()
    {
        return $this->_navigationTaxonomy;
    }

    public function toJson()
    {
        $cmscontents = Cmscontent::all();
        $json = $this->_buildStruct($cmscontents, true);
        return $json;
    }

    public function render()
    {
        $html = '<ul class="crate-navtax">';
        // $subTax = subtree taxonomy (root is equiv. to ->children)
        foreach ($this->_navigationTaxonomy as $slug => $subTax) {
            $html .= $this->_renderChild($subTax, $slug, 1);
        }
        $html .= '</ul>';
        return $html;
    }

    protected function _renderChild($subTax, $slug, $level)
    {
        if ($level > 3) {
            throw new \Exception('recursion level > 3');
        }

        $html = "<li>";
        if ( !empty($subTax['cms_obj']) ) {
            /*
            $html .= $subTax['cms_obj']->ctitle;
             */
            $html .= link_to_route('site.cms.show', $subTax['cms_obj']->ctitle, explode('.',$subTax['cms_obj']->ckey), ['class'=>''])->toHtml();
        } else {
            if ( !empty($slug) ) {
                $html .= Utils::unslugify($slug); // Can be better, may need LUT, but this is a good first approximation
            }
        }
        if ( !empty($subTax['children']) ) {
            $html .= '<ul>';
            foreach ($subTax['children'] as $_slug => $_subTax) {
                $html .= $this->_renderChild($_subTax, $_slug, $level+1);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    protected function _buildStruct($cmscontents, $isJson=false) 
    {
        $nt = [];
        foreach ($cmscontents as $cmsObj) {

            $args = $cmsObj->ckeyToArray(); // %TODO: alt impl is to assign to array, and use count(...)
            $arg0 = count($args)>0 ? $args[0] : null;
            $arg1 = count($args)>1 ? $args[1] : null;
            $arg2 = count($args)>2 ? $args[2] : null;

            if ( empty($arg0) ) {
                continue; // %FIXME: avoid continue?
            }
            if ( !array_key_exists($arg0, $nt) ) {
                // init
                $nt[$arg0]['children'] = []; 
                $nt[$arg0]['cms_obj'] = null; 
            }

            if ( empty($arg1) ) {
                $nt[$arg0]['cms_obj'] = $isJson ? $cmsObj->toJson() : $cmsObj;
                continue;
            }

            if ( !array_key_exists($arg1, $nt[$arg0]['children']) ) {
                // init
                $nt[$arg0]['children'][$arg1]['children'] = [];
                $nt[$arg0]['children'][$arg1]['cms_obj'] = null; 
            }

            if ( empty($arg2) ) {
                $nt[$arg0]['children'][$arg1]['cms_obj'] = $isJson ? $cmsObj->toJson() : $cmsObj;
                continue;
            }

            $nt[$arg0]['children'][$arg1]['children'][$arg2]['children'] = null;
            $nt[$arg0]['children'][$arg1]['children'][$arg2]['cms_obj'] = $isJson ? $cmsObj->toJson() : $cmsObj;

            /*
            if ( !array_key_exists($arg2, $nt[$arg0][$arg1]) ) {
            } else {
            }
             */
        }

        return $nt;
    }

}
