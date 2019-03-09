<?php
// add comment here
namespace App\Http\Controllers;

class SiteController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // common to all site controllers
        $this->_assetMgr::registerJsLibs([
            '//code.jquery.com/jquery-3.3.1.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js',
            '//stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js',
            '/js/app/common/libs/siteUtils.js',
         ]);

        $this->_assetMgr::registerJsInlines([
            '/js/app/common/inline/initCommon.js',
         ]);

        $this->_assetMgr::registerCssInlines([
            '//use.fontawesome.com/releases/v5.7.1/css/all.css',
            '//fonts.googleapis.com/css?family=Lato:100,300,400,700',
            '//stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',
            '/css/app/common/base.css',

         ]);


        /*
        $this->middleware(function ($request, $next) {
            //session(['g_module' => 'pmanager']);
            return $next($request);
        });
         */
    }
}
