<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // common to all site controllers
        $this->_assetMgr::registerJsLibs([
            //'/js/vendor/app.js',
            '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js',
            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
            '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js',
            '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',
            '//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js', // <<=== not needed? Seems we can just use the bootstrap+datatables css beloww
            //'//cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js', // <<== causes JS error
            '/js/app/common/libs/siteUtils.js',
         ]);
/*
https://code.jquery.com/jquery-1.12.4.js
https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js
https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js
https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js
https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js
*/

        $this->_assetMgr::registerJsInlines([
           //'/js/app/admin/inline/initCommon.js',
           //'/js/app/admin/initAdmin.js',
           '/js/app/common/inline/initDeleteUtils.js',
         ]);

        $this->_assetMgr::registerCssInlines([
            '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css',
            '//fonts.googleapis.com/css?family=Lato:100,300,400,700',
            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
            '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css',
            '//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css',
            '//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css',
            //'//cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css', // <<== causes JS error
            '/css/app/common/datatables.css',
            '/css/app/common/base.css',
            '/css/app/admin/styles.css',
         ]);
/*
https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css
https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css
https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css
*/

        $this->middleware(function ($request, $next) {
            session(['g_module' => 'admin']);
            return $next($request);
        });
    }
}
