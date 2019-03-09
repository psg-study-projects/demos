<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class HomeController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->_assetMgr::registerJsLibs([
        ]);
        $this->_assetMgr::registerJsInlines([
            '/js/app/admin/inline/initDataTables.js',
        ]);
        $this->_assetMgr::registerCssInlines([
            //'/css/app/main/styles.css',
        ]);
    }

    public function index()
    {
        $data = [];

        return \View::make('admin.home.index', $data);
    }
}
