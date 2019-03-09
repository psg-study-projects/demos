<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\SiteController;

class PagesController extends SiteController
{
    public function __construct()
    {
        parent::__construct();
        $this->_assetMgr::registerJsLibs([
            //'/js/app/common/libs/NlFormUtils.js',
            //'/js/app/applicant/inline/sendContactform.js',
        ]);
        $this->_assetMgr::registerCssInlines([
            //'/css/app/common/static_pages.css',
            '/css/app/main/styles.css',
        ]);
        //dd($this->_assetMgr->_cssInlinePaths);
    }

    public function show($slug)
    {
        $data = [];
        switch ($slug) {
            case 'home':
                return redirect('/');
                break;
            default:
                //\App::abort(404);
                $viewFile = 'site.pages.'.$slug;
        }

        try {
            return \View::make($viewFile, $data);
        } catch (\Exception $e) {
            \App::abort(404);
        }
    } // show()

    public function test($slug)
    {
        $data = [];
        $viewFile = 'site.test.'.$slug;
        return \View::make($viewFile, $data);
    }
}
